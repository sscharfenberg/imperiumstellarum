<script>
/******************************************************************************
 * PageComponent: PopulationChange
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "PopulationChange",
    props: {
        change: Number,
    },
    setup() {
        return { ...useI18n() };
    },
};
</script>

<template>
    <li class="change">
        {{ t("empire.planet.population.food.change") }}<br />
        {{ change }}
    </li>
</template>
