<script>
/******************************************************************************
 * PageComponent: NewContractSelectBlueprint
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import NewContractSingleBlueprint from "./NewContractSingleBlueprint";
export default {
    name: "NewContractSelectBlueprint",
    components: { SubHeadline, NewContractSingleBlueprint },
    setup: function () {
        const store = useStore();
        /**
         * @function get the blueprints that can be built in this shipyard
         * @type {ComputedRef<T[]>}
         */
        const blueprints = computed(() => {
            // get default order of ship/shipyard hullTypes
            const order = Object.keys(window.rules.ships.hullTypes);
            // find shipyard by id.
            const shipyard = store.state.shipyards.shipyards.find(
                (s) => s.id === store.state.shipyards.newContract.shipyard
            );
            // get array that contains the hullTypes that this shipyard can build
            const availableBlueprints =
                window.rules.shipyards.hullTypes[shipyard.type].construct;
            // return the blueprints
            return (
                store.state.shipyards.blueprints
                    // filter so only blueprints are included that can be built in the selected shipyard
                    .filter((b) => availableBlueprints.includes(b.hullType))
                    // sort by prefered sort order
                    .sort((a, b) => {
                        return (
                            order.indexOf(a.hullType) -
                            order.indexOf(b.hullType)
                        );
                    })
            );
        });
        return { blueprints };
    },
};
</script>

<template>
    <sub-headline
        :headline="`#2 ${$t(
            'shipyards.construct.newContract.blueprint.headline'
        )}`"
    />
    <div class="contract__blueprints">
        <ul class="contract__blueprint">
            <new-contract-single-blueprint
                v-for="blueprint in blueprints"
                :key="blueprint.id"
                :blueprint-id="blueprint.id"
                :hull-type="blueprint.hullType"
                :class-name="blueprint.name"
            />
        </ul>
    </div>
</template>

<style lang="scss" scoped>
.contract {
    &__blueprint {
        padding: 0;
        margin: 0;

        list-style: none;
    }
}
</style>
