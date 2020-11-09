<script>
/******************************************************************************
 * PageComponent: ShowShipyard
 *****************************************************************************/
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import { ref, computed } from "vue";
import ShipyardInfo from "./ShipyardInfo";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowShipyard",
    props: {
        planetId: String,
    },
    components: { Icon, ShipyardInfo },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const shipyard = computed(() =>
            store.getters["empire/shipyardByPlanetId"](props.planetId)
        );
        const showModal = ref(false);
        const btnTitle = computed(() => {
            const planetName = store.getters["empire/planetNameById"](
                props.planetId
            );
            return i18n.t("empire.planet.shipyard.info.title", {
                type: i18n.t("empire.planet.shipyard.types." + type.value),
                name: planetName,
            });
        });
        const type = computed(() => {
            if (shipyard.value.xlarge) return "xlarge";
            if (shipyard.value.large) return "large";
            if (shipyard.value.medium) return "medium";
            return "small";
        });
        return {
            shipyard,
            showModal,
            type,
            btnTitle,
        };
    },
};
</script>

<template>
    <button
        class="planet__shipyard"
        @click="showModal = true"
        :title="btnTitle"
        :aria-label="btnTitle"
        :class="{
            building: shipyard.untilComplete !== 0,
            active: shipyard.untilComplete === 0,
        }"
    >
        <icon name="shipyards" />
        <icon v-if="type === 'small'" name="hull-small" />
        <icon v-if="type === 'medium'" name="hull-medium" />
        <icon v-if="type === 'large'" name="hull-large" />
        <icon v-if="type === 'xlarge'" name="hull-xlarge" />
    </button>
    <shipyard-info
        v-if="showModal"
        :planet-id="planetId"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
.planet__shipyard {
    display: flex;
    align-items: center;

    height: 4rem;
    padding: 0.5rem 1rem;
    border: 2px solid transparent;
    margin: 0 0.5rem 0.5rem 0;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear,
        border-style map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-deep"), 0.7);
        color: t("t-light");
        border-color: t("g-asher");
    }

    &:hover:not([disabled]) {
        opacity: 0.8;

        @include themed() {
            background: t("g-ebony");
            border-style: solid;
            border-color: t("g-fog");
        }
    }

    &.building {
        @include themed() {
            border-color: t("s-building");
        }
    }

    &.active {
        @include themed() {
            border-color: t("s-active");
        }
    }

    .icon:nth-child(1) {
        margin-right: 1rem;

        @include themed() {
            color: t("b-viking");
        }
    }
}
</style>
