<script>
/******************************************************************************
 * PageComponent: ShowSummary
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SummarizeResources from "./SummarizeResources";
import SummarizePopulation from "./SummarizePopulation";
export default {
    name: "ShowSummary",
    components: { SummarizeResources, SummarizePopulation },
    setup() {
        const store = useStore();
        const resources = computed(() =>
            store.state.resources.map((res) => res.type)
        );
        return {
            resources,
        };
    },
};
</script>

<template>
    <ul class="summary">
        <summarize-resources
            v-for="res in resources"
            :key="res"
            :resource-type="res"
        />
        <summarize-population />
    </ul>
</template>

<style lang="scss" scoped>
.summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));

    padding: 0;
    grid-gap: 0.8rem;

    list-style: none;

    @include respond-to("medium") {
        grid-gap: 1.6rem;
    }

    > li {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        flex-wrap: wrap;

        padding: 1rem;
        border: 1px solid transparent;

        background: palette("grey", "sunken");

        @include themed() {
            background-color: t("g-sunken");
            border-color: t("g-abbey");
        }
    }
}
</style>
