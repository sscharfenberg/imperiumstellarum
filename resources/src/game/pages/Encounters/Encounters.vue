<script>
/******************************************************************************
 * Page: Encounters
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import EncountersList from "./EncountersList/EncountersList";
import EncountersNavigation from "./EncountersNavigation";
import GameHeader from "Components/Header/GameHeader";
import RaidsAsRaidedList from "./Raids/AsRaided/RaidsAsRaidedList";
import RaidsAsRaiderList from "./Raids/AsRaider/RaidsAsRaiderList";
export default {
    name: "PageEncounters",
    components: {
        EncountersList,
        EncountersNavigation,
        GameHeader,
        RaidsAsRaidedList,
        RaidsAsRaiderList,
    },
    setup() {
        const store = useStore();
        const pageIndex = computed(() => store.state.encounters.page);
        onBeforeMount(() => {
            store.dispatch("encounters/GET_GAME_DATA");
        });
        return {
            pageIndex,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    <encounters-navigation />
    <encounters-list v-if="pageIndex === 0" />
    <raids-as-raider-list v-else-if="pageIndex === 1" />
    <raids-as-raided-list v-else-if="pageIndex === 2" />
</template>
