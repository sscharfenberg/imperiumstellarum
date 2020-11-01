<script>
/******************************************************************************
 * Page: Empire
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import GameHeader from "Components/Header/GameHeader";
import ListStars from "./Stars/ListStars";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "PageEmpire",
    components: { GameHeader, AreaSection, ListStars },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.empire.requesting);
        const numStars = computed(() => store.state.empire.stars.length);
        onBeforeMount(() => {
            store.dispatch("empire/GET_GAME_DATA");
        });
        return {
            requesting,
            numStars,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <game-header headline="Fuddel Faddel" area="empire" />
    <area-section
        :headline="t('empire.stars', { num: numStars })"
        :requesting="requesting"
    >
        <list-stars />
    </area-section>
</template>
