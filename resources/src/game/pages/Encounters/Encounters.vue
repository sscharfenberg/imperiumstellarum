<script>
/******************************************************************************
 * Page: Encounters
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import EncountersList from "./List/EncountersList";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageEncounters",
    components: { AreaSection, EncountersList, GameHeader },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.encounters.requesting);
        const encounters = computed(() => store.state.encounters.encounters);
        onBeforeMount(() => {
            store.dispatch("encounters/GET_GAME_DATA");
        });
        return {
            requesting,
            encounters,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    <area-section
        :headline="$t('encounters.list.headline')"
        :requesting="requesting"
    >
        <encounters-list :encounters="encounters" />
    </area-section>
</template>
