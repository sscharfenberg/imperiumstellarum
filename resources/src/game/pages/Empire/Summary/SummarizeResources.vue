<script>
/******************************************************************************
 * PageComponent: SummarizeResources
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "SummarizeResources",
    props: {
        resourceType: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const harvesters = computed(() => {
            return store.state.empire.harvesters.filter(
                (harvester) =>
                    harvester.untilComplete === 0 &&
                    harvester.resourceType === props.resourceType
            );
        });
        const production = computed(() =>
            harvesters.value
                .map((harvester) => harvester.production)
                .reduce((acc, val) => {
                    return acc + val;
                }, 0)
        );
        const foodConsumption = computed(() =>
            store.state.empire.planets
                .filter((planet) => planet.population > 0)
                .map((planet) =>
                    Math.ceil(planet.foodConsumption * planet.population)
                )
                .reduce((acc, val) => {
                    return acc + val;
                }, 0)
        );
        return {
            harvesters,
            production,
            foodConsumption,
        };
    },
};
</script>

<template>
    <li>
        <h3>
            <icon :name="`res-${resourceType}`" :size="3" />
            {{ $t("common.resourceTypes." + resourceType) }}
        </h3>
        <div class="statistics">
            <span class="production">+{{ Math.round(production) }}</span>
            {{ $t("empire.summary.production", { num: harvesters.length }) }}
            <span v-if="resourceType === 'food'">
                <br />
                <span class="consumption">-{{ foodConsumption }}</span>
                {{ $t("empire.summary.foodConsumption") }}
            </span>
            <span v-if="resourceType === 'research'"> <br />[tbd] </span>
        </div>
    </li>
</template>

<style lang="scss" scoped>
h3 {
    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0;
    flex: 0 0 100%;

    font-weight: 300;
    text-align: center;
    text-transform: capitalize;
}

.icon {
    margin-right: 1rem;
}

.statistics {
    margin-top: 0.8rem;
    flex: 0 0 100%;

    text-align: center;

    @include themed() {
        color: t("t-tint");
    }
}

.production {
    @include themed() {
        color: t("s-success");
    }
}
.consumption {
    @include themed() {
        color: t("s-error");
    }
}
</style>
