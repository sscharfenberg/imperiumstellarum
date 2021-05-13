<script>
/******************************************************************************
 * Page: EncounterDetails
 *****************************************************************************/
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { onBeforeMount, onBeforeUnmount, computed } from "vue";
import router from "@/game/router";
import AreaSection from "Components/AreaSection/AreaSection";
import GameButton from "Components/Button/GameButton";
import GameHeader from "Components/Header/GameHeader";
import EncounterDetailsMeta from "./EncounterDetailsMeta";
import EncounterDetailsRenderTurn from "Pages/Encounters/EncounterDetails/Turn/EncounterDetailsRenderTurn";
import EncounterTape from "Pages/Encounters/EncounterDetails/Tape/EncounterTape";
import Popover from "Components/Popover/Popover";
export default {
    name: "EncounterDetails",
    components: {
        AreaSection,
        GameButton,
        GameHeader,
        EncounterDetailsMeta,
        EncounterDetailsRenderTurn,
        EncounterTape,
        Popover,
    },
    setup() {
        const store = useStore();
        const route = useRoute();
        const requesting = computed(() => store.state.encounters.requesting);
        const encounterId = route.params.encounterId;
        const encounter = computed(
            () => store.state.encounters.encounterDetails
        );
        const encounterStar = computed(() =>
            store.getters["encounters/starById"](encounter.value.starId)
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
        const onListviewClick = () => router.push({ name: "Encounters" });

        return {
            requesting,
            encounterId,
            encounter,
            encounterStar,
            onTurnClick,
            onListviewClick,
            currentTurn,
            sortedTurnNumbers,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    <area-section
        :headline="$t('encounters.details.headline')"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <game-button
                class="listview"
                icon-name="chevron_left"
                :text-string="$t('encounters.details.toListview')"
                @click="onListviewClick"
            />
            <popover align="right">
                {{ $t("encounters.details.explanation") }}
            </popover>
        </template>
        <encounter-details-meta
            v-if="encounterStar && encounterStar.name"
            :star="encounterStar"
            :turn="encounter.turn"
            :participants="encounter.participantIds"
            :owner-id="encounter.ownerId"
        />
        <div v-if="encounter.turns">
            {{ encounter.turns.length - 1 }} Turns
            <button
                v-for="turn in sortedTurnNumbers"
                :key="turn"
                @click="onTurnClick(turn)"
                :class="{ active: turn === currentTurn }"
            >
                {{ turn }}
            </button>
            <encounter-tape />
            <br />
            <encounter-details-render-turn :turn="currentTurn" />
        </div>
    </area-section>
</template>

<style lang="scss" scoped>
.active {
    @include themed() {
        background-color: t("b-viking");
        color: t("t-dark");
    }
}

.listview {
    margin-right: 4px;

    @include respond-to("medium") {
        margin-right: 8px;
    }
}
</style>
