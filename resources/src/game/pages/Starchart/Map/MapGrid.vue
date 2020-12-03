<script>
/******************************************************************************
 * PageComponent: MapGrid
 *****************************************************************************/
import { computed } from "vue";
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
        return {
            gridSize,
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
    </svg>
</template>

<style lang="scss" scoped>
.grid-line {
    @include themed() {
        stroke: t("g-bunker");
    }
}
</style>
