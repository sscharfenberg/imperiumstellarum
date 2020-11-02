<script>
/******************************************************************************
 * Component: ShowColony
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed } from "vue";
import Modal from "Components/Modal/Modal";
import FoodConsumption from "./FoodConsumption";
import { convertLatinToRoman } from "@/game/helpers/format";
export default {
    name: "ShowColony",
    props: {
        planetId: String,
        starName: String,
        index: Number,
    },
    components: { Modal, FoodConsumption },
    setup(props) {
        const store = useStore();
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        return {
            planet,
            convertLatinToRoman,
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
            <food-consumption :planet-id="planetId" />
        </ul>
    </modal>
</template>

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;
}
</style>
