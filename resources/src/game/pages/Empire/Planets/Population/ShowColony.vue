<script>
/******************************************************************************
 * Component: ShowColony
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed, ref, onBeforeMount } from "vue";
import Modal from "Components/Modal/Modal";
import Icon from "Components/Icon/Icon";
import GameButton from "Components/Button/GameButton";
import { convertLatinToRoman } from "@/game/helpers/format";
import debounce from "lodash/debounce";
export default {
    name: "ShowColony",
    props: {
        planetId: String,
        starName: String,
        index: Number,
    },
    components: { Modal, Icon, GameButton },
    setup(props, { emit }) {
        const store = useStore();
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        const stateFoodConsumption = computed(
            () => planet.value.foodConsumption
        );
        const foodPerPop = ref(0);
        const change = ref(0);
        onBeforeMount(() => {
            foodPerPop.value = stateFoodConsumption.value;
            getPopulationGrowth();
        });
        const getPopulationGrowth = () => {
            window.axios
                .post("/api/game/populationChange", {
                    food: parseFloat(foodPerPop.value),
                    population: planet.value.population,
                })
                .then((response) => {
                    change.value = response.data.change;
                })
                .catch((e) => {
                    console.error(e);
                });
        };
        const onChange = debounce(() => {
            getPopulationGrowth();
        }, 200);
        const onSubmit = () => {
            store.dispatch("empire/SET_FOOD_CONSUMPTION", {
                planetId: props.planetId,
                foodConsumption: foodPerPop.value,
            });
            emit("close");
        };
        return {
            planet,
            convertLatinToRoman,
            foodPerPop,
            change,
            onChange,
            onSubmit,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal
        :title="
            t('empire.planet.population.colony.title', {
                name: starName + ' - ' + convertLatinToRoman(index),
            })
        "
        @close="$emit('close')"
    >
        <ul class="stats">
            <li>
                {{ t("empire.planet.population.colony.pop") }}<br />{{
                    planet.population
                }}
            </li>
            <li>
                {{ t("empire.planet.population.colony.effectivePop") }}<br />{{
                    Math.floor(planet.population)
                }}
            </li>
            <li class="stats--two-col hdl">
                <icon name="res-food" />
                {{ t("empire.planet.population.food.title") }}
            </li>
            <li class="stats--two-col perpop">
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
                    @change="onChange"
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
                    @change="onChange"
                />
            </li>
            <li class="total">
                {{ t("empire.planet.population.food.total") }}<br />
                {{ Math.ceil(planet.population * foodPerPop) }}
            </li>
            <li class="change" :class="{ pos: change > 0, neg: change < 0 }">
                {{ t("empire.planet.population.food.change") }}<br />
                {{ change }}
            </li>
        </ul>
        <template v-slot:actions>
            <game-button
                :text-string="t('empire.planet.population.food.submit')"
                icon-name="done"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;
}
.hdl {
    display: flex;
    align-items: center;
    justify-content: center;

    > .icon {
        margin-right: 10px;
    }
}
.perpop {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    > label {
        flex: 0 0 100%;
    }

    > input[type="range"] {
        flex-grow: 1;
    }

    > input[type="number"] {
        width: 60px;
        padding: 5px;
        border: 0;
        margin-left: 10px;

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
}
.pos {
    @include themed() {
        border-color: t("s-success");
    }
}
.neg {
    @include themed() {
        border-color: t("s-error");
    }
}
</style>
