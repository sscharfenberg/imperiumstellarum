<script>
/******************************************************************************
 * Page: RaidsAsRaidedList.vue
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import Pagination from "Components/Pagination/Pagination";
import Popover from "Components/Popover/Popover";
import RaidsAsRaidedRenderSingle from "./RaidsAsRaidedRenderSingle";
export default {
    name: "RaidsAsRaidedList",
    components: { AreaSection, Pagination, Popover, RaidsAsRaidedRenderSingle },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.encounters.requesting);
        const raids = computed(() => store.getters["encounters/raidedRaids"]);
        const page = ref(1);
        const perPage = computed({
            get: () => store.state.encounters.perPage,
            set: (value) =>
                store.commit("encounters/SET_RAIDS_RAIDED_PER_PAGE", value),
        });
        const onPageChange = (changedPage) => (page.value = changedPage);
        const onPerPageChange = (changedPerPage) => {
            perPage.value = changedPerPage;
        };
        const paginatedRaids = computed(() => {
            const from = page.value * perPage.value - perPage.value;
            const to = page.value * perPage.value;
            return raids.value.slice(from, to);
        });
        return {
            requesting,
            raids,
            page,
            perPage,
            onPageChange,
            onPerPageChange,
            paginatedRaids,
        };
    },
};
</script>

<template>
    <area-section
        :headline="$t('encounters.raidsAsRaided.headline')"
        :requesting="requesting"
    >
        <template v-slot:aside>
            <popover align="right">
                {{ $t("encounters.raidsAsRaided.explanation") }}
            </popover>
        </template>
        <pagination
            v-if="raids.length > 0"
            :current-page="page"
            :per-page="perPage"
            :num-items="raids.length"
            message-key="encounters.raidsAsRaided.pagination.messages"
            per-page-key="encounters.raidsAsRaided.pagination.perPage"
            @changepage="onPageChange"
            @changeperpage="onPerPageChange"
        />
        <ul v-if="raids.length > 0">
            <raids-as-raided-render-single
                v-for="raid in paginatedRaids"
                :key="raid.id"
                :raid="raid"
            />
        </ul>
        <p v-else-if="raids.length === 0">
            {{ $t("encounters.raidsAsRaided.none") }}
        </p>
    </area-section>
</template>
