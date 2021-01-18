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
import SubHeadline from "Components/SubHeadline/SubHeadline";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
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
        CollapsibleItem,
        GameButton,
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
        const onSubmit = () => {
            console.log(emit);
            //emit("close");
        };

        onBeforeMount(() => {
            store.commit("fleets/SET_DESTINATION_STAR_ID", "");
        });

        return {
            fleet,
            destinationId,
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
                :collapsible-id="`moveFleet-${fleetId}-otherSystem`"
                :alt-bg="true"
            >
                <template v-slot:topic>{{
                    $t("fleets.move.otherSystems")
                }}</template>
                <fleet-move-any-system :fleet-id="fleetId" />
            </collapsible-item>
        </div>
        <sub-headline
            v-if="destinationId"
            :headline="$t('fleets.move.destinationInfo')"
        />
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
}
</style>
