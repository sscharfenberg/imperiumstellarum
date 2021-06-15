<script>
/******************************************************************************
 * PageComponent: EncountersList
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
import EncounterListRenderSingle from "./EncountersListRenderSingle";
import Pagination from "Components/Pagination/Pagination";
import Popover from "Components/Popover/Popover";
export default {
    name: "PageEncounters",
    components: { AreaSection, EncounterListRenderSingle, Pagination, Popover },
    setup(props) {
        const store = useStore();
        const encounters = computed(
            () => store.getters["encounters/sortedEncounters"]
        );
        const requesting = computed(() => store.state.encounters.requesting);
        const page = ref(1);
        const perPage = computed({
            get: () => store.state.encounters.perPage,
            set: (value) => store.commit("encounters/SET_PER_PAGE", value),
        });
        const onPageChange = (changedPage) => (page.value = changedPage);
        const onPerPageChange = (changedPerPage) => {
            perPage.value = changedPerPage;
        };
        const paginatedEncounters = computed(() => {
            const from = page.value * perPage.value - perPage.value;
            const to = page.value * perPage.value;
            return props.encounters.slice(from, to);
        });
        return {
            encounters,
            requesting,
            paginatedEncounters,
            page,
            perPage,
            onPageChange,
            onPerPageChange,
        };
    },
};
</script>

<template>
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
        <pagination
            v-if="encounters.length > 0"
            :current-page="page"
            :per-page="perPage"
            :num-items="encounters.length"
            message-key="encounters.list.pagination.messages"
            per-page-key="encounters.list.pagination.perPage"
            @changepage="onPageChange"
            @changeperpage="onPerPageChange"
        />
        <nav v-if="encounters.length > 0" class="encounters">
            <encounter-list-render-single
                v-for="encounter in paginatedEncounters"
                :key="encounter.id"
                :encounter="encounter"
            />
        </nav>
        <p v-else>{{ $t("encounters.list.none") }}</p>
    </area-section>
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
