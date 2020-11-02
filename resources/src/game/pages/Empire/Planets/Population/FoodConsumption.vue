<script>
/******************************************************************************
 * PageComponent: FoodConsumption
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import PopulationChange from "./PopulationChange";
import debounce from "lodash/debounce";
export default {
    name: "FoodConsumption",
    props: {
        planetId: String,
    },
    components: { Icon, PopulationChange },
    emits: ["change-consumption"],
    setup(props) {
        const store = useStore();
        const changeDisabled = computed(() => false);
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        const foodPerPop = computed({
            get: () => planet.value.foodConsumption,
            set: debounce(
                (val) =>
                    store.dispatch("empire/SET_FOOD_CONSUMPTION", {
                        planetId: props.planetId,
                        foodConsumption: val,
                    }),
                300
            ),
        });
        const popChange = ref(0);
        return {
            planet,
            foodPerPop,
            changeDisabled,
            popChange,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <li class="stats__two-col hdl">
        <icon name="res-food" />
        {{ t("empire.planet.population.food.title") }}
    </li>
    <li class="stats__two-col perpop">
        <label :for="`setFood-number-${planetId}`">{{
            t("empire.planet.population.food.perPop")
        }}</label>
        <input
            type="range"
            :id="`setFood-slider-${planetId}`"
            min="0"
            aria-valuemin="0"
            max="3"
            aria-valuemax="3"
            step="0.01"
            v-model="foodPerPop"
            :disabled="changeDisabled"
        />
        <input
            type="number"
            :id="`setFood-number-${planetId}`"
            min="0"
            aria-valuemin="0"
            max="3"
            aria-valuemax="3"
            step="0.2"
            v-model="foodPerPop"
            :aria-label="
                $t('empire.planet.population.food.title') +
                ' ' +
                $t('empire.planet.population.food.perPop')
            "
            :disabled="changeDisabled"
        />
    </li>
    <li class="total">
        {{ t("empire.planet.population.food.total") }}<br />
        {{ Math.ceil(planet.population * foodPerPop) }}
    </li>
    <population-change :change="popChange" />
</template>

<style lang="scss" scoped>
.hdl {
    display: flex;
    align-items: center;
    justify-content: center;

    @include themed() {
        color: t("g-white");
    }

    > svg {
        margin-right: 1rem;
    }
}
.perpop {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    > label {
        flex: 0 0 100%;
    }

    > input[type="number"] {
        padding: 0.5rem 1rem;
        border: 0;
        margin-left: 1rem;
        flex: 0 0 3rem;

        font-weight: 300;
        line-height: 1;

        @include themed() {
            background: t("g-deep");
            color: t("t-light");
        }

        &:focus {
            outline: 0;

            @include themed() {
                background: t("g-raven");
                color: t("g-white");
            }
        }
    }

    > input:disabled {
        opacity: 0.5;
    }

    > input[type="range"] {
        flex-grow: 1;
    }
}
</style>
