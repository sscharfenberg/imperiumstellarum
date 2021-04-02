<script>
/******************************************************************************
 * Page: Encounters
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageEncounters",
    components: { GameHeader },
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
    {{ encounters }}
    <ul>
        <li v-for="encounter in encounters" :key="encounter.id">
            <router-link
                :to="{
                    name: 'EncounterDetails',
                    params: { encounterId: encounter.id },
                }"
            >
                {{ encounter.starId }}
            </router-link>
        </li>
    </ul>
</template>
