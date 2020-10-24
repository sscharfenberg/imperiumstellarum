<script>
/******************************************************************************
 * Page: Empire
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageEmpire",
    components: { GameHeader },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.empire.requesting);

        onBeforeMount(() => {
            store.dispatch("empire/GET_GAME_DATA");
        });

        return {
            ...useI18n(),
            requesting,
        };
    },
};
</script>

<template>
    <game-header headline="Fuddel Faddel" area="empire" />
    Tüddelü weiter gehts.
</template>
