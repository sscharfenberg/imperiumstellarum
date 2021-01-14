<script>
/******************************************************************************
 * PageComponent: FleetTransferModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import FleetTransferChooseFleet from "./FleetTransferChooseFleet";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetTransferModal",
    props: {
        fleetId: String,
        starId: String,
        shipyardId: String,
    },
    components: { Modal, Icon, SubHeadline, FleetTransferChooseFleet },
    emits: ["close"],
    setup(props) {
        const store = useStore();
        const fleet = computed(() => {
            if (props.fleetId)
                return store.getters["fleets/fleetById"](props.fleetId);
            if (props.shipyardId)
                return store.getters["fleets/shipyardById"](props.shipyardId);
        });

        // this is kinda akward, but avoids repeating a lot of code.
        const transferFleetOrShipyard = computed(() => {
            const transferId = store.state.fleets.transferId;
            const fleet = store.state.fleets.fleets.find(
                (f) => f.id === transferId
            );
            const shipyard = store.state.fleets.shipyards.find(
                (s) => s.id === transferId
            );
            if (fleet)
                return {
                    name: fleet.name,
                    icon: "fleets",
                };
            else if (shipyard)
                return {
                    name: shipyard.planetName,
                    icon: "shipyards",
                };
        });

        return {
            fleet,
            transferFleetOrShipyard,
        };
    },
};
</script>

<template>
    <modal
        :title="
            $t('fleets.transfer.title', {
                name: fleet.name ? fleet.name : fleet.planetName,
            })
        "
        @close="$emit('close')"
        :full-size="true"
    >
        <sub-headline :headline="$t('fleets.transfer.chooseFleet')" />
        <fleet-transfer-choose-fleet :fleet-id="fleetId" :star-id="starId" />
        <ul class="fleet-transfer__grid">
            <li class="fleet-transfer__grid-item fleet-transfer__grid-head">
                <icon v-if="fleet.name" name="fleets" />
                <icon v-if="fleet.planetName" name="shipyards" />
                {{ fleet.name ? fleet.name : fleet.planetName }}
            </li>
            <li class="fleet-transfer__grid-item fleet-transfer__grid-actions">
                &lt;&gt;
            </li>
            <li class="fleet-transfer__grid-item fleet-transfer__grid-head">
                <icon
                    v-if="transferFleetOrShipyard"
                    :name="transferFleetOrShipyard.icon"
                />
                <span v-if="transferFleetOrShipyard">{{
                    transferFleetOrShipyard.name
                }}</span>
            </li>
            <li class="fleet-transfer__grid-item">ship<br />ship<br />ship</li>
            <li class="fleet-transfer__grid-item">ship<br />ship<br />ship</li>
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
