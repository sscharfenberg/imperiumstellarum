<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaActions
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import FleetEditModal from "./FleetEditModal";
import FleetDeleteModal from "./FleetDeleteModal";
import FleetTransferModal from "../../Transfer/FleetTransferModal";
import FleetMoveModal from "../../Move/FleetMoveModal";
export default {
    name: "ShowFleet",
    props: {
        holderId: String,
    },
    components: {
        GameButton,
        FleetEditModal,
        FleetDeleteModal,
        FleetTransferModal,
        FleetMoveModal,
    },
    setup(props) {
        const store = useStore();
        const showEditModal = ref(false);
        const showDeleteModal = ref(false);
        const showTransferModal = ref(false);
        const showMoveModal = ref(false);
        const ships = computed(() => {
            const fleetShips = store.getters["fleets/shipsByFleetId"](
                props.holderId
            );
            const shipyardShips = store.getters["fleets/shipsByShipyardId"](
                props.holderId
            );
            return fleetShips.length ? fleetShips : shipyardShips;
        });
        const holder = computed(() => {
            const fleet = store.getters["fleets/fleetById"](props.holderId);
            const shipyard = store.getters["fleets/shipyardById"](
                props.holderId
            );
            return fleet && fleet.id ? fleet : shipyard;
        });
        const transferDisabled = computed(() => {
            if (!holder.value.starId) return true; // no starId => fleet in transit.
            const holders = [
                ...store.state.fleets.fleets,
                ...store.state.fleets.shipyards,
            ].filter(
                (h) =>
                    h.id !== props.holderId && h.starId === holder.value.starId
            );
            return holders.length === 0;
        });

        // prepare state for modal.
        // do it here and not within the modal since we already have the data
        const doTransferShips = () => {
            store.commit("fleets/SET_TRANSFER_SOURCE_ID", props.holderId);
            store.commit(
                "fleets/SET_TRANSFER_SOURCE_SHIP_IDS",
                ships.value.map((s) => s.id)
            );
            showTransferModal.value = true;
        };

        return {
            showEditModal,
            showDeleteModal,
            showTransferModal,
            showMoveModal,
            ships,
            transferDisabled,
            holder,
            doTransferShips,
        };
    },
};
</script>

<template>
    <nav class="fleet-meta__actions">
        <game-button
            v-if="!holder.planetName"
            icon-name="edit"
            :text-string="$t('fleets.active.actions.edit')"
            @click="showEditModal = true"
        />
        <fleet-edit-modal
            v-if="showEditModal && !holder.planetName"
            :fleet-id="holderId"
            @close="showEditModal = false"
        />

        <game-button
            v-if="!holder.planetName"
            icon-name="delete"
            :text-string="$t('fleets.active.actions.delete')"
            :disabled="ships.length > 0"
            :aria-disabled="ships.length > 0"
            @click="showDeleteModal = true"
        />
        <fleet-delete-modal
            v-if="showDeleteModal"
            :fleet-id="holderId"
            @close="showDeleteModal = false"
        />

        <game-button
            v-if="!holder.planetName"
            icon-name="transit"
            :text-string="$t('fleets.active.actions.move')"
            :disabled="!holder.ftl || !holder.starId"
            @click="showMoveModal = true"
        />
        <fleet-move-modal
            v-if="showMoveModal"
            :fleet-id="holderId"
            @close="showMoveModal = false"
        />

        <game-button
            icon-name="transfer"
            :text-string="$t('fleets.active.actions.transfer')"
            :disabled="transferDisabled"
            @click="doTransferShips"
        />
        <fleet-transfer-modal
            v-if="showTransferModal"
            :holder-id="holderId"
            @close="showTransferModal = false"
        />
    </nav>
</template>

<style lang="scss" scoped>
.fleet-meta__actions {
    @include respond-to("medium") {
        order: -1;
    }

    .btn {
        width: auto;
        margin: 0 4px 4px 0;

        @include respond-to("medium") {
            margin: 0 8px 8px 0;
        }

        &:last-of-type {
            margin-right: 0;
        }
    }
}
</style>