<script>
/******************************************************************************
 * PageComponent: ManageBlueprints
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import SingleBlueprint from "./SingleBlueprint";
import ManagePreview from "./Preview/ManagePreview";
export default {
    name: "ManageBlueprints",
    components: {
        AreaSection,
        CollapsibleItem,
        SingleBlueprint,
        ManagePreview,
    },
    setup() {
        const store = useStore();
        const blueprints = computed(() => store.state.shipyards.blueprints);
        const hullTypes = computed(() => {
            // prepare array of preferred sort order
            const order = Object.keys(window.rules.ships.hullTypes);
            return (
                blueprints.value
                    .map((b) => b.hullType) // pass an array that only contains hullTypes
                    // check if it is the first index, so we pass only uniques.
                    .filter(
                        (value, index, self) => self.indexOf(value) === index
                    )
                    // sort hullTypes according to our preferred sort order (ascending)
                    .sort((a, b) => {
                        return order.indexOf(a) - order.indexOf(b);
                    })
            );
        });
        const requesting = computed(() => store.state.shipyards.requesting);
        const preview = computed(() => store.state.shipyards.preview);
        const bpMax = computed(() => store.state.shipyards.bpMax);
        return {
            bpMax,
            blueprints,
            hullTypes,
            requesting,
            preview,
        };
    },
};
</script>

<template>
    <area-section
        :headline="`${$t('shipyards.manage.title')} (${
            blueprints.length
        }/${bpMax})`"
        v-if="blueprints.length"
        :requesting="requesting"
    >
        <div class="manage">
            <div class="list">
                <collapsible-item
                    v-for="hullType in hullTypes"
                    :key="`collapsibleBPArea${hullType}`"
                    :topic="`${$t('shipyards.manage.collapsible', {
                        type: $t('shipyards.hulls.' + hullType),
                    })} (${
                        blueprints.filter((b) => b.hullType === hullType).length
                    })`"
                    :icon-name="`hull-${hullType}`"
                    :expanded="preview.hullType === hullType"
                    :aria-expanded="preview.hullType === hullType"
                >
                    <ul class="blueprint__list">
                        <single-blueprint
                            v-for="blueprint in blueprints.filter(
                                (b) => b.hullType === hullType
                            )"
                            :key="`BlueprintListItem${blueprint.id}`"
                            :id="blueprint.id"
                            :hull-type="hullType"
                            :class-name="blueprint.name"
                        />
                    </ul>
                </collapsible-item>
            </div>
            <manage-preview v-if="preview.hullType" />
        </div>
    </area-section>
</template>

<style lang="scss" scoped>
.manage {
    display: flex;
    flex-wrap: wrap;

    @include respond-to("medium") {
        flex-wrap: nowrap;
    }
}

.list,
.preview {
    flex: 0 0 100%;

    @include respond-to("medium") {
        flex: 1 0 calc(60% - 8px);
    }
}

.preview {
    order: -1;

    padding: 8px;
    margin-bottom: 8px;
    flex: 0 0 100%;

    @include respond-to("medium") {
        order: 0;

        padding: 16px;
        margin: 0 0 0 16px;
        flex: 0 0 calc(40% - 8px);
    }

    @include themed() {
        background-color: t("g-sunken");
    }
}

.collapsible {
    margin: 0;
}

.blueprint__list {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
