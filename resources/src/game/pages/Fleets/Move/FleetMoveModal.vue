<script>
/******************************************************************************
 * PageComponent: FleetMoveModal
 *****************************************************************************/
import { computed, onBeforeMount } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
import FleetMoveSummary from "./FleetMoveSummary";
import FleetMoveOwnSystem from "./FleetMoveOwnSystem";
import FleetMoveAnySystem from "./FleetMoveAnySystem";
import FleetMoveDestinationInfo from "./FleetMoveDestinationInfo";
import FleetMovePlayerSystems from "Pages/Fleets/Move/FleetMovePlayerSystems";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import Loading from "Components/Loading/Loading";
export default {
    name: "FleetMoveModal",
    props: {
        fleetId: String,
    },
    components: {
        Modal,
        FleetMoveSummary,
        SubHeadline,
        FleetMoveOwnSystem,
        FleetMoveAnySystem,
        FleetMovePlayerSystems,
        FleetMoveDestinationInfo,
        CollapsibleItem,
        GameButton,
        Loading,
    },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const destinationId = computed(
            () => store.state.fleets.moveDestinationStarId
        );
        const destinationStar = computed(
            () => store.state.fleets.destinationStar
        );
        const destinationOwner = computed(
            () => store.state.fleets.destinationOwner
        );
        const requesting = computed(() => store.state.fleets.requesting);

        const onSubmit = () => {
            store.dispatch("fleets/SEND_FLEET", {
                fleetId: props.fleetId,
                destinationId: destinationId.value,
            });
            console.log(emit);
            //emit("close");
        };

        onBeforeMount(() => {
            store.commit("fleets/SET_DESTINATION_STAR_ID", "");
            store.commit("fleets/SET_DESTINATION_COORD_X", "");
            store.commit("fleets/SET_DESTINATION_COORD_Y", "");
            store.commit("fleets/SET_DESTINATION_STAR", {});
            store.commit("fleets/SET_DESTINATION_OWNER", {});
            store.commit("fleets/SET_AVAILABLE_DESTINATIONS", []);
        });

        return {
            fleet,
            destinationId,
            destinationStar,
            destinationOwner,
            requesting,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('fleets.move.headline', { name: fleet.name })"
        @close="$emit('close')"
        :full-size="false"
    >
        <fleet-move-summary :fleet-id="fleetId" />
        <sub-headline :headline="$t('fleets.move.chooseDestination')" />
        <div class="choose-destination">
            <collapsible-item
                :collapsible-id="`moveFleet-${fleetId}-ownSystem`"
                :alt-bg="true"
            >
                <template v-slot:topic>{{
                    $t("fleets.move.ownSystems")
                }}</template>
                <fleet-move-own-system :fleet-id="fleetId" />
            </collapsible-item>
            <collapsible-item
                :collapsible-id="`moveFleet-${fleetId}-findbyCoordinates`"
                :alt-bg="true"
            >
                <template v-slot:topic>{{
                    $t("fleets.move.coordinates.topic")
                }}</template>
                <fleet-move-any-system :fleet-id="fleetId" />
            </collapsible-item>
            <collapsible-item
                :collapsible-id="`moveFleet-${fleetId}-findByPlayerTicker`"
                :alt-bg="true"
            >
                <template v-slot:topic>{{
                    $t("fleets.move.players.headline")
                }}</template>
                <fleet-move-player-systems :fleet-id="fleetId" />
            </collapsible-item>
        </div>
        <loading :size="48" v-if="!destinationId && requesting" />
        <sub-headline
            v-if="destinationId"
            :headline="$t('fleets.move.destination.info')"
        />
        <fleet-move-destination-info v-if="destinationId" />
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.move.submit')"
                icon-name="save"
                @click="onSubmit"
                :disabled="!destinationId"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.choose-destination {
    margin-bottom: 16px;

    .collapsible__item {
        margin-bottom: 2px;

        @include respond-to("medium") {
            margin-bottom: 4px;
        }
    }
}

svg.spinner {
    margin: 0 auto;
}
</style>
