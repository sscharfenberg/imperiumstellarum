<script>
/******************************************************************************
 * PageComponent: FleetTransferModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import FleetTransferChooseFleet from "./FleetTransferChooseFleet";
import FleetTransferShipGrid from "./FleetTransferShipGrid";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetTransferModal",
    props: {
        holderId: String,
    },
    components: {
        Modal,
        Icon,
        SubHeadline,
        FleetTransferChooseFleet,
        FleetTransferShipGrid,
    },
    emits: ["close"],
    setup(props) {
        const store = useStore();

        // we use this modal for fleets and shipyards as a source.
        const transferSource = computed(() => {
            const fleet = store.getters["fleets/fleetById"](props.holderId);
            const shipyard = store.getters["fleets/shipyardById"](
                props.holderId
            );
            return fleet && fleet.id ? fleet : shipyard;
        });

        // this is kinda akward, but avoids repeating a lot of code.
        // find out if the source (left side) is a fleet or shipyard
        const transferTarget = computed(() => {
            const targetId = store.state.fleets.transferTargetId;
            const fleet = store.state.fleets.fleets.find(
                (f) => f.id === targetId
            );
            const shipyard = store.state.fleets.shipyards.find(
                (s) => s.id === targetId
            );
            if (fleet)
                return {
                    id: fleet.id,
                    name: fleet.name,
                    icon: "fleets",
                };
            else if (shipyard)
                return {
                    id: shipyard.id,
                    name: shipyard.planetName,
                    icon: "shipyards",
                };
        });

        // initial ships by fleetId/shipyardId
        const transferSourceShipIds = computed(
            () => store.state.fleets.transferSourceShipIds
        );
        const transferTargetShipIds = computed(
            () => store.state.fleets.transferTargetShipIds
        );

        return {
            transferSource,
            transferTarget,
            transferSourceShipIds,
            transferTargetShipIds,
        };
    },
};
</script>

<template>
    <modal
        :title="
            $t('fleets.transfer.title', {
                name: transferSource.name
                    ? transferSource.name
                    : transferSource.planetName,
            })
        "
        @close="$emit('close')"
        :full-size="true"
    >
        <sub-headline :headline="$t('fleets.transfer.chooseFleet')" />
        <fleet-transfer-choose-fleet
            :holder-id="holderId"
            :star-id="transferSource.starId"
        />
        <ul class="fleet-transfer__grid">
            <li class="fleet-transfer__grid-item fleet-transfer__grid-head">
                <icon v-if="transferSource.name" name="fleets" />
                <icon v-if="transferSource.planetName" name="shipyards" />
                {{
                    transferSource.name
                        ? transferSource.name
                        : transferSource.planetName
                }}
            </li>
            <li class="fleet-transfer__grid-item fleet-transfer__grid-actions">
                &lt;&gt;
            </li>
            <li class="fleet-transfer__grid-item fleet-transfer__grid-head">
                <icon v-if="transferTarget" :name="transferTarget.icon" />
                <span v-if="transferTarget">{{ transferTarget.name }}</span>
            </li>
            <fleet-transfer-ship-grid
                :item-id="transferSource.id"
                :ship-ids="transferSourceShipIds"
            />
            <fleet-transfer-ship-grid
                :item-id="transferTarget ? transferTarget.id : ''"
                :ship-ids="transferTargetShipIds"
            />
        </ul>
    </modal>
</template>

<style lang="scss" scoped>
.fleet-transfer {
    &__grid {
        display: grid;
        grid-template-columns: 1fr 40px 1fr;

        padding: 0;
        margin: 0;

        list-style: none;
    }

    &__grid-item {
        padding: 4px;
        border: 1px solid transparent;

        @include themed() {
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 8px;
        }
    }

    &__grid-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        grid-row: span 2;

        padding: 4px;
        border-right-width: 0;
        border-left-width: 0;
    }

    &__grid-head {
        display: flex;
        align-items: center;

        border-bottom-width: 0;

        @include themed() {
            background-color: t("g-bunker");
        }

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }
}
</style>
