<script>
/******************************************************************************
 * Component: ShipCardHpRadial
 *****************************************************************************/
import { calculateHpDasharray } from "../useShipCalculations";
import { computed } from "vue";
export default {
    name: "ShipCardHpRadial",
    props: {
        pctShields: Number,
        pctArmour: Number,
        pctStructure: Number,
    },
    setup(props) {
        const shieldDasharray = computed(() =>
            calculateHpDasharray(props.pctShields, 45)
        );
        const armourDasharray = computed(() =>
            calculateHpDasharray(props.pctArmour, 33)
        );
        const structureDasharray = computed(() =>
            calculateHpDasharray(props.pctStructure, 21)
        );
        return { shieldDasharray, armourDasharray, structureDasharray };
    },
};
</script>

<template>
    <svg viewBox="0 0 100 100">
        <g class="shields">
            <path
                class="circle-bg"
                d="M50 5 a 45 45 0 0 1 0 90 a 45 45 0 0 1 0 -90"
                fill="none"
                stroke-width="10"
                stroke-dasharray="26.27433388 2"
            />
            <path
                class="circle-percentage"
                d="M50 5 a 43 43 0 0 1 0 90 a 43 43 0 0 1 0 -90"
                fill="none"
                stroke-width="6"
                :stroke-dasharray="shieldDasharray"
            />
        </g>
        <g class="armour">
            <path
                class="circle-bg"
                d="M50 17 a 33 33 0 0 1 0 66 a 33 33 0 0 1 0 -66"
                fill="none"
                stroke-width="10"
                stroke-dasharray="18.73451151 2"
            />
            <path
                class="circle-percentage"
                d="M50 17 a 31 31 0 0 1 0 66 a 31 31 0 0 1 0 -66"
                fill="none"
                stroke-width="6"
                :stroke-dasharray="armourDasharray"
            />
        </g>
        <g class="structure">
            <path
                class="circle-bg"
                d="M50 29 a 21 21 0 0 1 0 42 a 21 21 0 0 1 0 -42"
                fill="none"
                stroke-width="10"
                stroke-dasharray="11.194689145 2"
            />
            <path
                class="circle-percentage"
                d="M50 29 a 19 19 0 0 1 0 42 a 19 19 0 0 1 0 -42"
                fill="none"
                stroke-width="6"
                :stroke-dasharray="structureDasharray"
            />
        </g>
    </svg>
</template>

<style lang="scss" scoped>
.circle-bg {
    @include themed() {
        stroke: t("g-deep");
    }
}

.circle-percentage {
    @include themed() {
        stroke: t("b-christine");
    }
}
</style>
