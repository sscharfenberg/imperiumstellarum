<script>
/******************************************************************************
 * Page: Diplomacy
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onBeforeMount } from "vue";
import GameHeader from "Components/Header/GameHeader";
import AreaSection from "Components/AreaSection/AreaSection";
import DiplomacyFilterPlayers from "./DiplomacyFilterPlayers";
import DiplomacyListPlayers from "Pages/Diplomacy/DiplomacyListPlayers";
export default {
    name: "PageDiplomacy",
    components: {
        GameHeader,
        AreaSection,
        DiplomacyFilterPlayers,
        DiplomacyListPlayers,
    },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.diplomacy.requesting);
        const players = computed(() => store.state.diplomacy.players);
        const relations = computed(() => store.state.diplomacy.relations);
        onBeforeMount(() => {
            store.dispatch("diplomacy/GET_GAME_DATA");
        });
        return { requesting, players, relations };
    },
};
</script>

<template>
    <game-header area="diplomacy" />
    <area-section
        :headline="$t('diplomacy.list.headline')"
        :requesting="requesting"
    >
        <div class="diplomacy">
            <diplomacy-filter-players v-if="players.length" />
            <diplomacy-list-players v-if="players.length" />
        </div>
    </area-section>
</template>

<style lang="scss" scoped>
.diplomacy {
    border: 1px solid transparent;

    @include themed() {
        background-color: t("g-sunken");
        border-color: t("g-deep");
    }
}
</style>
