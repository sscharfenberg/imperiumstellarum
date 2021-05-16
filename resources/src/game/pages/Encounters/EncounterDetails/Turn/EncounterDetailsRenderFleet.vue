<script>
/******************************************************************************
 * Page: EncounterDetailsRenderFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { ref, computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "EncounterDetailsRenderFleet",
    props: {
        name: String,
        ships: Object,
        damage: Number,
        ownerId: String,
        columnWidth: Number,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const domFleet = ref(null);
        const owner = computed(() =>
            store.getters["encounters/playerById"](props.ownerId)
        );
        return { domFleet, owner };
    },
};
</script>

<template>
    <span class="fleet" :style="{ width: columnWidth + 'px' }" ref="domFleet">
        <span
            class="fleet__owner"
            :style="{ '--owner-colour': '#' + owner.colour }"
            >{{ owner.ticker }}</span
        >
        <span class="fleet__name">{{ name }}</span>
        <ul v-if="Object.keys(ships).length !== 0" class="fleet__ships">
            <li
                v-for="(amount, type) in ships"
                :key="type"
                :class="{ margin: type !== 'ark' && type !== 'small' }"
            >
                {{ amount }}
                <icon :name="`hull-${type}`" :size="1" />
            </li>
        </ul>
        <span v-if="damage" class="fleet__damage">-{{ damage }}</span>
    </span>
</template>

<style lang="scss" scoped>
.fleet {
    display: block;
    position: relative;

    overflow: hidden;

    border: 1px solid transparent;

    /* this is set on the parent EocunterDetailsFleetRow.vue */
    margin-left: var(--fleet-margin);

    transition: margin-left map-get($animation-speeds, "fast") linear;

    @include themed() {
        border-color: t("g-deep");
    }

    &__owner {
        display: block;

        padding: 2px 0;

        background: var(--owner-colour);

        font-size: 12px;
        line-height: 1;
        text-align: center;
        letter-spacing: 2px;

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
        }

        @include respond-to("medium") {
            padding: 2px;

            font-size: 16px;
            font-weight: 600;
        }
    }

    &__name {
        display: none;

        padding: 2px;

        font-size: 14px;
        text-align: center;

        @include respond-to("small") {
            display: block;
        }
    }

    &__ships {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;

        padding: 2px 0;
        border-top: 1px solid transparent;
        margin: 0;
        gap: 2px;

        list-style: none;

        font-size: 12px;

        @include respond-to("medium") {
            padding: 2px;
            gap: 4px;

            font-size: 14px;
        }

        @include themed() {
            border-color: t("g-deep");
        }

        .margin > .icon {
            @include respond-to("medium") {
                margin-left: 4px;
            }
        }

        .icon {
            width: 16px;
            height: 16px;

            @include respond-to("medium") {
                width: 20px;
                height: 20px;
            }
        }
    }

    &__damage {
        display: block;

        border-top: 1px solid transparent;

        font-weight: 600;
        text-align: center;

        @include themed() {
            color: t("s-error");
            border-color: t("g-deep");

            text-shadow: 1px 1px t("s-warning"), -1px 1px t("s-warning"),
                1px -1px t("s-warning"), -1px -1px t("s-warning");
        }
    }
}
</style>
