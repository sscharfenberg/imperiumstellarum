<script>
/******************************************************************************
 * Page: EncounterDetails
 *****************************************************************************/
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "EncounterDetails",
    components: { GameHeader },
    setup() {
        const store = useStore();
        const route = useRoute();
        const requesting = computed(() => store.state.encounters.requesting);
        const encounterId = route.params.encounterId;
        onBeforeMount(() => {
            store.dispatch("encounters/GET_GAME_DATA");
        });
        return {
            requesting,
            encounterId,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    Encounter Details!
    {{ requesting }}
    {{ encounterId }}
</template>
