<script>
/******************************************************************************
 * Component: ShipCardHp
 circumference = radius * 2pi
 arc1 radius = 45
 circumference = 282.7433388
 10 pips, circumference = 262.7433388
 dasharray = 26.27433388 2

 arc2 radius = 33
 arc2 circumference = 207.3451151
 10 pips, circumference = 187.3451151
 dasharray = 18.73451151 2

 arc3 radius = 21
 arc3 circumference = 131.94689145
 10pips, circumfernece = 111.94689145
 dasharray = 11.194689145 2
 TODO: create function that calculates the full dasharray for .circle-percentage
 *****************************************************************************/
export default {
    name: "ShipCardHp",
    setup() {
        return {};
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
                stroke-dasharray="26.27433388 2"
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
        </g>
        <g class="structure">
            <path
                class="circle-bg"
                d="M50 29 a 21 21 0 0 1 0 42 a 21 21 0 0 1 0 -42"
                fill="none"
                stroke-width="10"
                stroke-dasharray="11.194689145 2"
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
