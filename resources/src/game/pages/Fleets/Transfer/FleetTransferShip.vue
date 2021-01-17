<script>
/******************************************************************************
 * PageComponent: FleetTransferShip
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetTransferShipGrid",
    props: {
        shipId: String,
        columnIndex: Number, // 0..1, left or right.
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const ship = computed(() =>
            store.getters["fleets/shipById"](props.shipId)
        );
        const isDisabled = computed(
            () =>
                !store.state.fleets.transferTargetId ||
                !store.state.fleets.transferSourceId
        );
        return { ship, isDisabled };
    },
};
</script>

<template>
    <button
        class="ship"
        :class="{ left: columnIndex === 0, right: columnIndex === 1 }"
        :disabled="isDisabled"
    >
        <icon :name="`hull-${ship.hullType}`" />
        <span class="ship__name">{{ ship.name }}</span>
    </button>
</template>

<style lang="scss" scoped>
.ship {
    display: flex;
    align-items: center;

    width: 100%;
    border: 1px solid transparent;
    margin-bottom: 2px;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include respond-to("medium") {
        margin-bottom: 4px;
    }

    @include themed() {
        background-color: t("g-deep");
        color: t("t-tint");
        border-color: t("g-abbey");
    }

    &:hover:not([disabled]) {
        @include themed() {
            background-color: t("g-sunken");
            color: t("t-light");
            border-color: t("g-deep");
        }
    }

    .icon {
        height: 24px;
        flex: 0 0 24px;
    }

    &.left .icon {
        margin-right: 4px;

        @include respond-to("medium") {
            margin-right: 8px;
        }
    }

    &.right {
        justify-content: flex-end;

        .icon {
            order: 2;

            margin-left: 4px;
            transform: scale(-1, 1);
            //transform: rotate(180deg);

            @include respond-to("medium") {
                margin-left: 8px;
            }
        }
    }

    &__name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &:last-child {
        margin-bottom: 0;
    }

    &[disabled] {
        opacity: 0.3;

        cursor: not-allowed;
    }
}
</style>
