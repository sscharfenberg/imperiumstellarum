<script>
/******************************************************************************
 * Page: Encounters
 *****************************************************************************/
import { useStore } from "vuex";
import { onBeforeMount, computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import EncountersList from "./List/EncountersList";
import GameHeader from "Components/Header/GameHeader";
import Popover from "Components/Popover/Popover";
export default {
    name: "PageEncounters",
    components: { AreaSection, EncountersList, GameHeader, Popover },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.encounters.requesting);
        const encounters = computed(
            () => store.getters["encounters/sortedEncounters"]
        );
        const raiderRaids = computed(
            () => store.getters["encounters/raiderRaids"]
        );
        const raidedRaids = computed(
            () => store.getters["encounters/raidedRaids"]
        );
        onBeforeMount(() => {
            store.dispatch("encounters/GET_GAME_DATA");
        });
        return {
            requesting,
            encounters,
            raiderRaids,
            raidedRaids,
        };
    },
};
</script>

<template>
    <game-header area="encounters" />
    <area-section
        :headline="
            $t('encounters.list.headline') +
            (encounters.length && encounters.length > 0
                ? ` (${encounters.length})`
                : '')
        "
        :requesting="requesting"
    >
        <template v-slot:aside>
            <popover align="right">
                {{ $t("encounters.list.explanation") }}
            </popover>
        </template>
        <encounters-list :encounters="encounters" />
    </area-section>
    <area-section headline="Raids as Raider" :requesting="requesting">
        {{ raiderRaids }}
    </area-section>
    <area-section headline="Raids as Raided" :requesting="requesting">
        {{ raidedRaids }}
    </area-section>
</template>
