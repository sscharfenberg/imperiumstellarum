<script>
/******************************************************************************
 * PageComponent: RenderStar
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
import StarInfoModal from "./StarInfoModal/StarInfoModal";
export default {
    name: "RenderStar",
    props: {
        zoom: Number, // 0..4
        id: String,
        bgPos: String,
        top: String,
        left: String,
        ownerId: String,
        ownerColour: String,
        ticker: String,
        name: String,
        numFleets: Number,
        hasShipyard: Boolean,
        transitFleets: Number,
        relationStatus: Number,
        foreignFleetsHostile: Number,
        foreignFleetsAllied: Number,
        foreignFleetsNeutral: Number,
    },
    components: { Icon, StarInfoModal },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const showModal = ref(false);
        const empireId = computed(() => store.state.empireId);
        const starClass = computed(() =>
            empireId.value === props.ownerId ? "my" : ""
        );
        const bgColour = computed(() =>
            props.zoom <= 2 ? props.ownerColour : "transparent"
        );
        const ownSystem = computed(
            () => props.ownerId === store.state.empireId
        );
        const starTitle = computed(() => {
            let title = `${props.name}: ${i18n.t(
                "starchart.star.buttonLabel"
            )}`;
            if (props.ownerId) title = `[${props.ticker}] ${title}`;
            return title;
        });
        const playerColour = computed(() => "#" + store.state.colour);
        const dead = computed(() => store.state.dead);
        return {
            showModal,
            bgColour,
            starClass,
            starTitle,
            playerColour,
            ownSystem,
            dead,
        };
    },
};
</script>

<template>
    <button
        class="star"
        :class="starClass"
        :style="{
            '--bgPos': bgPos,
            '--ownerColour': ownerColour,
            top: top,
            left: left,
            backgroundColor: bgColour,
        }"
        :title="starTitle"
        :aria-label="starTitle"
        :disabled="dead"
        @click="showModal = true"
    >
        <span class="ticker" v-if="ticker">{{ ticker }}</span>
        <span class="fleets" v-if="numFleets > 0 && zoom > 2">
            <span v-if="numFleets > 1">{{ numFleets }}</span>
            <icon name="fleets" :style="{ '--fleetColour': playerColour }" />
        </span>
        <span class="shipyard" v-if="hasShipyard && zoom > 2">
            <icon name="shipyards" />
        </span>
        <span
            v-if="foreignFleetsHostile > 0 && zoom > 2"
            class="foreign-fleets foreign-fleets--hostile"
            >{{ foreignFleetsHostile }}</span
        >
        <span
            v-if="foreignFleetsAllied > 0 && zoom > 2"
            class="foreign-fleets foreign-fleets--allied"
            >{{ foreignFleetsAllied }}</span
        >
        <span
            v-if="foreignFleetsNeutral > 0 && zoom > 2"
            class="foreign-fleets foreign-fleets--neutral"
            >{{ foreignFleetsNeutral }}</span
        >
        <span class="transit" v-if="transitFleets > 0 && zoom > 2">
            <span v-if="transitFleets > 1">{{ transitFleets }}</span>
            <icon name="transit" :style="{ '--fleetColour': playerColour }" />
        </span>
        <span
            class="diplomatic-relation"
            v-if="!ownSystem && relationStatus >= 0"
            :title="$t('diplomacy.status.' + relationStatus)"
            :class="{
                allied: relationStatus === 2,
                neutral: relationStatus === 1,
                hostile: relationStatus === 0,
            }"
        ></span>
    </button>
    <star-info-modal
        v-if="showModal"
        @close="showModal = false"
        :star-id="id"
    />
</template>

<style lang="scss" scoped>
.star {
    position: absolute;

    box-sizing: content-box;
    overflow: hidden;
    width: var(--cssTileSize);
    height: var(--cssTileSize);
    padding: 0;
    border-width: var(--borderWidth);

    background: transparent url("@/theme/spectral-types.png") var(--bgPos)
        no-repeat;
    background-size: var(--cssTileSize);
    border-style: dashed;
    border-color: var(--ownerColour);

    cursor: pointer;

    text-align: right;

    &.my {
        border-style: solid;
    }

    &:active,
    &:focus {
        outline: 0;
    }
}

.ticker {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;

    padding: 2px 4px;

    font-size: calc(var(--cssTileSize) / 5);
    text-align: center;

    @include themed() {
        background: rgba(t("g-sunken"), 0.9);
        color: t("g-white");
    }
}

.fleets {
    display: flex;
    position: absolute;
    right: 0;
    bottom: 0;
    align-items: center;
    justify-content: center;

    padding: 0 calc(var(--cssTileSize) / 15);

    font-size: calc(var(--cssTileSize) / 6);

    @include themed() {
        background: rgba(t("g-sunken"), 0.9);
        color: t("g-white");
    }

    .icon {
        width: calc(var(--cssTileSize) / 4);
        height: calc(var(--cssTileSize) / 4);

        fill: var(--fleetColour);
    }
}

.shipyard {
    display: flex;
    position: absolute;
    bottom: 0;
    left: 0;
    align-items: center;
    justify-content: center;

    padding: 2px 4px;

    @include themed() {
        background: rgba(t("g-sunken"), 0.9);
    }

    .icon {
        width: calc(var(--cssTileSize) / 4);
        height: calc(var(--cssTileSize) / 4);

        @include themed() {
            color: t("b-christine");
        }
    }
}

.transit {
    display: flex;
    position: absolute;
    top: 50%;
    right: 50%;
    align-items: center;
    justify-content: center;

    padding: 0 calc(var(--cssTileSize) / 15);
    transform: translate3d(50%, -50%, 0);

    font-size: calc(var(--cssTileSize) / 6);

    @include themed() {
        background: rgba(t("g-sunken"), 0.9);
        color: t("t-light");
    }

    .icon {
        width: calc(var(--cssTileSize) / 4);
        height: calc(var(--cssTileSize) / 4);

        fill: var(--fleetColour);
    }
}

.diplomatic-relation {
    display: flex;
    position: absolute;
    bottom: 1px;
    left: 1px;
    align-items: center;
    justify-content: center;

    width: calc(var(--cssTileSize) / 4);
    height: calc(var(--cssTileSize) / 4);

    border-radius: 50%;

    &.allied {
        @include themed() {
            background-color: t("s-success");
        }
    }
    &.hostile {
        @include themed() {
            background-color: t("s-error");
        }
    }
    &.neutral {
        @include themed() {
            background-color: t("s-building");
        }
    }
}

.foreign-fleets {
    position: absolute;

    padding: calc(var(--cssTileSize) / 20) calc(var(--cssTileSize) / 15);

    font-size: calc(var(--cssTileSize) / 6);

    &--hostile {
        top: 50%;
        right: 1px;

        transform: translate3d(0, -50%, 0);

        @include themed() {
            background: t("s-error");
            color: t("g-white");
        }
    }

    &--allied {
        top: 50%;
        left: 1px;

        transform: translate3d(0, -50%, 0);

        @include themed() {
            background: t("s-success");
            color: t("g-white");
        }
    }

    &--neutral {
        top: 50%;
        left: 50%;

        transform: translate3d(-50%, -50%, 0);

        @include themed() {
            background: rgba(t("g-sunken"), 0.9);
            color: t("g-white");
        }
    }
}
</style>
