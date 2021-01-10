<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaActions
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import FleetEditModal from "./FleetEditModal";
import FleetDeleteModal from "./FleetDeleteModal";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: { GameButton, FleetEditModal, FleetDeleteModal },
    setup(props) {
        const store = useStore();
        const showEditModal = ref(false);
        const showDeleteModal = ref(false);
        const ships = computed(() =>
            store.getters["fleets/shipsByFleetId"](props.fleetId)
        );
        return { showEditModal, showDeleteModal, ships };
    },
};
</script>

<template>
    <nav class="fleet-meta__actions">
        <game-button
            icon-name="edit"
            :text-string="$t('fleets.active.actions.edit')"
            @click="showEditModal = true"
        />
        <fleet-edit-modal
            v-if="showEditModal"
            :fleet-id="fleetId"
            @close="showEditModal = false"
        />
        <game-button
            icon-name="delete"
            :text-string="$t('fleets.active.actions.delete')"
            :disabled="ships.length > 0"
            :aria-disabled="ships.length > 0"
            @click="showDeleteModal = true"
        />
        <fleet-delete-modal
            v-if="showDeleteModal"
            :fleet-id="fleetId"
            @close="showDeleteModal = false"
        />
        <game-button
            icon-name="transit"
            :text-string="$t('fleets.active.actions.move')"
        />
        <game-button
            icon-name="transfer"
            :text-string="$t('fleets.active.actions.transfer')"
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
