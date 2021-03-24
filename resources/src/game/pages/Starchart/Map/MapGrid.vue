<script>
/******************************************************************************
 * PageComponent: MapGrid
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "MapGrid",
    props: {
        dimensions: Number, // map dimensions in coords
        cameraX: Number,
        cameraY: Number,
        tileSize: Number, // pixel size for a tile
    },
    setup(props) {
        const gridSize = computed(() => props.dimensions * props.tileSize);
        const store = useStore();
        const flashX = computed(() => store.state.starchart.flashCoordX);
        const flashY = computed(() => store.state.starchart.flashCoordY);
        return {
            gridSize,
            flashX,
            flashY,
        };
    },
};
</script>

<template>
    <svg
        :viewBox="`0 0 ${gridSize} ${gridSize}`"
        xmlns="http://www.w3.org/2000/svg"
    >
        <line
            class="grid-line"
            v-for="n in dimensions - 1"
            :key="`vLine${n}`"
            :x1="tileSize * n - 0.5"
            y1="0"
            :x2="tileSize * n - 0.5"
            :y2="gridSize"
        />
        <line
            class="grid-line"
            v-for="n in dimensions - 1"
            :key="`hLine${n}`"
            x1="0"
            :y1="tileSize * n - 0.5"
            :x2="gridSize"
            :y2="tileSize * n - 0.5"
        />
        <rect
            v-if="flashX || flashX === 0"
            class="focus-line focus-line__vertical"
            :x="flashX * tileSize"
            y="0"
            :width="tileSize - 1"
            :height="tileSize * dimensions"
        />
        <rect
            v-if="flashY || flashY === 0"
            class="focus-line focus-line__horizontal"
            x="0"
            :y="flashY * tileSize"
            :width="tileSize * dimensions"
            :height="tileSize - 1"
        />
    </svg>
</template>

<style lang="scss" scoped>
.grid-line {
    @include themed() {
        stroke: t("g-bunker");
    }
}

.focus-line {
    opacity: 0;

    animation: grid-flash 1s linear;
    animation-fill-mode: forwards;

    @include themed() {
        fill: t("g-raven");
    }
}
</style>

<style lang="scss">
@keyframes grid-flash {
    0% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}
</style>
