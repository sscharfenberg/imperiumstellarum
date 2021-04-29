<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaActions
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import FleetDeleteModal from "./FleetDeleteModal";
import FleetEditModal from "./FleetEditModal";
import FleetMoveModal from "../../Move/FleetMoveModal";
import FleetTransferModal from "../../Transfer/FleetTransferModal";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowFleet",
    props: {
        holderId: String,
    },
    components: {
        FleetDeleteModal,
        FleetEditModal,
        FleetMoveModal,
        FleetTransferModal,
        GameButton,
        Icon,
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
        const gameOver = computed(() => store.state.gameEnded);

        return {
            showEditModal,
            showDeleteModal,
            showTransferModal,
            showMoveModal,
            ships,
            transferDisabled,
            holder,
            doTransferShips,
            gameOver,
        };
    },
};
</script>

<template>
    <nav class="fleet-meta__actions">
        <game-button
            v-if="!holder.planetName && !gameOver"
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
            v-if="!holder.planetName && !gameOver"
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
            v-if="!holder.planetName && !gameOver"
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
            v-if="!gameOver"
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

        <div
            class="fleet-ftl"
            v-if="holder.ftl && !holder.planetName"
            :title="$t('fleets.active.location.status.ftl.label')"
            :aria-label="$t('fleets.active.location.status.ftl.label')"
        >
            <icon name="tech-ftl" />
            {{ $t("fleets.active.location.status.ftl.short") }}
        </div>
        <div
            class="fleet-ftl"
            v-if="!holder.ftl && !holder.planetName"
            :title="$t('fleets.active.location.status.noFtl')"
            :aria-label="$t('fleets.active.location.status.noFtl')"
        >
            <icon name="warning" />
            {{ $t("fleets.active.location.status.ftl.short") }}
        </div>
    </nav>
</template>

<style lang="scss" scoped>
.fleet-meta__actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    .btn {
        width: auto;
        margin: 0 4px 4px 0;

        @include respond-to("medium") {
            margin: 0 8px 8px 0;
        }
    }

    .fleet-ftl {
        display: flex;
        align-items: center;

        padding: 5px;
        margin: 0 0 4px 0;
        clip-path: polygon(
            0 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% 100%,
            5px 100%,
            0 calc(100% - 5px)
        );

        @include respond-to("medium") {
            padding: 5px 8px;
            margin: 0 8px 8px 0;
            clip-path: polygon(
                0 0,
                calc(100% - 10px) 0,
                100% 10px,
                100% 100%,
                10px 100%,
                0 calc(100% - 10px)
            );
        }

        &:last-child {
            margin-right: 0;
        }

        @include themed() {
            background-color: t("g-deep");
        }

        @include respond-to("medium") {
            margin: 0 0 8px 0;
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
