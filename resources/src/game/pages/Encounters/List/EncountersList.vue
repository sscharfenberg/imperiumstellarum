<script>
/******************************************************************************
 * PageComponent: EncountersList
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import EncounterListRenderSingle from "./EncountersListRenderSingle";
import Pagination from "Components/Pagination/Pagination";
export default {
    name: "PageEncounters",
    components: { EncounterListRenderSingle, Pagination },
    props: {
        encounters: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const store = useStore();
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
