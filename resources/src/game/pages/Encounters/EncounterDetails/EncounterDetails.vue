<script>
/******************************************************************************
 * Page: EncounterDetails
 *****************************************************************************/
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { onBeforeMount, onBeforeUnmount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import EncounterDetailsRenderTurn from "Pages/Encounters/EncounterDetails/Turn/EncounterDetailsRenderTurn";
export default {
    name: "EncounterDetails",
    components: { GameHeader, EncounterDetailsRenderTurn },
    setup() {
        const store = useStore();
        const route = useRoute();
        const requesting = computed(() => store.state.encounters.requesting);
        const encounterId = route.params.encounterId;
        const encounter = computed(
            () => store.state.encounters.encounterDetails
        );
        onBeforeMount(() => {
            store.dispatch(
                "encounters/GET_ENCOUNTER_DETAILS",
                route.params.encounterId
            );
            store.commit("encounters/SET_TURN", 0);
        });
        onBeforeUnmount(() => {
            store.commit("encounters/SET_ENCOUNTER_DETAILS", {});
            store.commit("encounters/SET_TURN", 0);
        });
        const onTurnClick = (turn) => store.commit("encounters/SET_TURN", turn);
        const currentTurn = computed(() => store.state.encounters.renderTurn);
        const sortedTurnNumbers = computed(() =>
            encounter.value.turns.map((t) => t.turn).sort((a, b) => a - b)
        );
        return {
            requesting,
            encounterId,
            encounter,
            onTurnClick,
            currentTurn,
            sortedTurnNumbers,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    Encounter Details!
    {{ encounterId }}
    <div v-if="encounter.turns">
        {{ encounter.turns.length }} Turns
        <button
            v-for="turn in sortedTurnNumbers"
            :key="turn"
            @click="onTurnClick(turn)"
        >
            {{ turn }}
        </button>
        <br />
        <encounter-details-render-turn :turn="currentTurn" />
    </div>
</template>
