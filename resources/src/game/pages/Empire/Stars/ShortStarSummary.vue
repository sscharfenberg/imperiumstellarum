<script>
/******************************************************************************
 * PageComponent: ShortStarSummary
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShortStarSummary",
    props: {
        starId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const planetIds = computed(() =>
            store.getters["empire/planetsByStarId"](props.starId).map(
                (planet) => planet.id
            )
        );
        const numShipyards = computed(() => {
            let num = 0;
            planetIds.value.forEach((id) => {
                if (store.getters["empire/shipyardByPlanetId"](id)) num++;
            });
            return num;
        });
        const population = computed(() => {
            let num = 0;
            planetIds.value.forEach((id) => {
                const population = store.getters["empire/planetById"](id)
                    .population;
                if (population) num += population;
            });
            return num;
        });
        const shipyardLabel = computed(() =>
            i18n.t(
                "empire.star.summary.shipyards",
                { num: numShipyards.value },
                numShipyards.value
            )
        );
        const populationLabel = computed(() =>
            i18n.t("empire.star.summary.population", { num: population.value })
        );
        return {
            planetIds,
            numShipyards,
            population,
            shipyardLabel,
            populationLabel,
        };
    },
};
</script>

<template>
    <ul class="short-summary">
        <li
            v-if="numShipyards > 0"
            class="shipyards"
            :title="shipyardLabel"
            :aria-label="shipyardLabel"
        >
            <icon name="shipyards" />
            {{ numShipyards }}
        </li>
        <li
            v-if="population > 0"
            class="population"
            :title="populationLabel"
            :aria-label="populationLabel"
        >
            <icon name="population" />
            {{ Math.floor(population) }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.short-summary {
    display: none;
    align-items: center;

    padding: 0;
    margin: 0;

    list-style: none;

    @include respond-to("small") {
        display: flex;
    }

    > li {
        display: flex;
        align-items: center;

        margin-left: 10px;

        @include themed() {
            color: t("t-tint");
        }

        .icon {
            margin-right: 5px;
        }

        &.shipyards .icon {
            @include themed() {
                color: t("b-viking");
            }
        }

        &.population .icon {
            margin-right: 0;
        }
    }
}
</style>
