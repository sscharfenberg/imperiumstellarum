<script>
/******************************************************************************
 * PageComponent: EncountersList
 *****************************************************************************/
import EncounterListRenderSingle from "./EncountersListRenderSingle";
export default {
    name: "PageEncounters",
    components: { EncounterListRenderSingle },
    props: {
        encounters: {
            type: Array,
            required: true,
        },
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <nav v-if="encounters.length" class="encounters">
        <encounter-list-render-single
            v-for="encounter in encounters"
            :key="encounter.id"
            :encounter="encounter"
        />
    </nav>
    <p v-else>{{ $t("encounters.list.none") }}</p>
</template>

<style lang="scss" scoped>
.encounters {
    display: flex;
    flex-direction: column;

    gap: 4px;

    @include respond-to("medium") {
        gap: 8px;
    }
}
</style>
