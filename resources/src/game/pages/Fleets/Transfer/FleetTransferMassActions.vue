<script>
/******************************************************************************
 * PageComponent: FleetTransferMassActions
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetTransferMassActions",
    components: { Icon },
    setup() {
        const store = useStore();
        const sourceShips = computed(
            () => store.state.fleets.transferSourceShipIds
        );
        const targetShips = computed(
            () => store.state.fleets.transferTargetShipIds
        );
        const hasTarget = computed(
            () => store.state.fleets.transferTargetId.length > 0
        );
        const transferLtr = () => {
            store.commit(
                "fleets/TRANSFER_ALL_SOURCE_TO_TARGET",
                sourceShips.value
            );
            store.commit("fleets/SET_TRANSFER_SUBMIT_ACTIVE", true);
        };
        const transferRtl = () => {
            store.commit(
                "fleets/TRANSFER_ALL_TARGET_TO_SOURCE",
                targetShips.value
            );
            store.commit("fleets/SET_TRANSFER_SUBMIT_ACTIVE", true);
        };
        return {
            sourceShips,
            targetShips,
            hasTarget,
            transferLtr,
            transferRtl,
        };
    },
};
</script>

<template>
    <li class="fleet-transfer__grid-actions">
        <button
            class="ltr"
            :disabled="sourceShips.length === 0 || !hasTarget"
            :title="$t('fleets.transfer.ltr')"
            :aria-label="$t('fleets.transfer.ltr')"
            @click="transferLtr"
        >
            <icon name="transit" />
        </button>
        <button
            class="rtl"
            :disabled="targetShips.length === 0 || !hasTarget"
            :title="$t('fleets.transfer.rtl')"
            :aria-label="$t('fleets.transfer.rtl')"
            @click="transferRtl"
        >
            <icon name="transit" />
        </button>
    </li>
</template>

<style lang="scss" scoped>
.fleet-transfer__grid-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    grid-row: span 2;

    padding: 4px;
    border: 1px solid transparent;
    border-right-width: 0;
    border-left-width: 0;

    @include themed() {
        border-color: t("g-deep");
    }
}

button {
    width: 100%;
    padding: 4px 1px;
    border: 1px solid transparent;
    margin-bottom: 16px;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-abbey");
        color: t("t-bright");
        border-color: t("g-iron");
    }

    &:last-child {
        margin-bottom: 0;
    }

    &.rtl .icon {
        transform: scale(-1, 1);
    }

    &:hover {
        @include themed() {
            background-color: t("g-ebony");
            color: t("b-viking");
            border-color: t("g-abbey");
        }
    }

    &[disabled] {
        opacity: 0.3;

        cursor: not-allowed;
    }
}
</style>
