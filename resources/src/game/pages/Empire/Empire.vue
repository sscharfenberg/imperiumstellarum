<script>
/******************************************************************************
 * Page: Empire
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import ListStars from "./Stars/ListStars";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "PageEmpire",
    components: { GameHeader, AreaSection, ListStars },
    setup() {
        const store = useStore();
        onBeforeMount(() => {
            store.dispatch("empire/GET_GAME_DATA");
        });
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <game-header headline="Fuddel Faddel" area="empire" />
    <area-section headline="Fuddel faddel">
        <list-stars />
    </area-section>
</template>
