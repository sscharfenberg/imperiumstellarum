<script>
/******************************************************************************
 * PageComponent: SummarizePopulation
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "SummarizeResources",
    components: { Icon },
    setup() {
        const store = useStore();
        const colonies = computed(() =>
            store.state.empire.planets.filter((planet) => planet.population > 0)
        );
        const totalPopulation = computed(() => {
            return colonies.value
                .map((planet) => Math.floor(planet.population))
                .reduce((acc, val) => {
                    return acc + val;
                }, 0);
        });
        return {
            colonies,
            totalPopulation,
        };
    },
};
</script>

<template>
    <li>
        <h3>
            <icon name="population" :size="3" />
            {{ $t("empire.summary.population") }}
        </h3>
        <div class="statistics">
            <span class="production">{{ totalPopulation }}</span>
            {{
                $tc("empire.summary.colonies", colonies.length, {
                    num: colonies.length,
                })
            }}
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
    margin-right: 10px;
}

.statistics {
    margin-top: 8px;
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
