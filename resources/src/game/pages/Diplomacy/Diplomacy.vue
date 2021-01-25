<script>
/******************************************************************************
 * Page: Diplomacy
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
export default {
    name: "PageDiplomacy",
    components: { GameHeader },
    setup() {
        const store = useStore();
        onBeforeMount(() => {
            store.dispatch("diplomacy/GET_GAME_DATA");
        });
        return {};
    },
};
</script>

<template>
    <game-header area="diplomacy" />
    Fuddel
</template>
