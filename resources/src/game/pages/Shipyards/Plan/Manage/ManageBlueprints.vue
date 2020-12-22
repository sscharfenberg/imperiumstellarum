<script>
/******************************************************************************
 * PageComponent: ManageBlueprints
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import SingleBlueprint from "./SingleBlueprint";
export default {
    name: "ManageBlueprints",
    components: { AreaSection, CollapsibleItem, SingleBlueprint },
    setup() {
        const store = useStore();
        const blueprints = computed(() => store.state.shipyards.blueprints);
        const hullTypes = computed(() =>
            blueprints.value
                .map((b) => b.hullType) // pass an array that only contains hullTypes
                // check if it is the first index, so we pass only uniques.
                .filter((value, index, self) => self.indexOf(value) === index)
        );
        return {
            blueprints,
            hullTypes,
        };
    },
};
</script>

<template>
    <area-section
        :headline="$t('shipyards.manage.title')"
        v-if="blueprints.length"
    >
        <collapsible-item
            v-for="hullType in hullTypes"
            :key="`collapsibleBPArea${hullType}`"
            :topic="`${$t('shipyards.manage.collapsible', {
                type: $t('shipyards.hulls.' + hullType),
            })} (${blueprints.filter((b) => b.hullType === hullType).length})`"
            :icon-name="`hull-${hullType}`"
            :expanded="true"
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
    </area-section>
</template>

<style lang="scss" scoped>
.collapsible {
    margin: 0;
}
.blueprint__list {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
