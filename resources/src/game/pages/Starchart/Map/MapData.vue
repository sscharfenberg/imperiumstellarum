<script>
/******************************************************************************
 * PageComponent: MapData
 *****************************************************************************/
import { computed, onMounted } from "vue";
import { useStore } from "vuex";
import RenderStar from "./RenderStar";
export default {
    name: "MapData",
    props: {
        zoom: Number, // 0..4
        dimensions: Number, // map dimensions in coords
        cameraX: Number,
        cameraY: Number,
        tileSize: Number, // pixel size for a tile
    },
    components: { RenderStar },
    setup(props) {
        const store = useStore();
        const stars = computed(
            () => store.getters["starchart/starsWithOwners"]
        );
        // number values
        const borderWidth = computed(() => (props.zoom >= 3 ? 3 : 1));
        const availableTilePixels = computed(
            () => props.tileSize - 1 - 2 * borderWidth.value
        );
        // css values
        const cssTileSize = computed(() => availableTilePixels.value + "px");
        const bgPos = computed(() => {
            return {
                O: "0 0",
                B: "0 -" + availableTilePixels.value + "px",
                A: "0 -" + availableTilePixels.value * 2 + "px",
                F: "0 -" + availableTilePixels.value * 3 + "px",
                G: "0 -" + availableTilePixels.value * 4 + "px",
                K: "0 -" + availableTilePixels.value * 5 + "px",
                M: "0 -" + availableTilePixels.value * 6 + "px",
                Y: "0 -" + availableTilePixels.value * 7 + "px",
            };
        });
        const cssBorderWidth = computed(() => borderWidth.value + "px");
        onMounted(() => {
            console.log("mounted mapdata");
            store.commit(
                "starchart/SET_STARS_SHOWN",
                store.state.starchart.stars
            );
        });
        return {
            stars,
            cssTileSize,
            bgPos,
            cssBorderWidth,
        };
    },
};
</script>

<template>
    <div
        class="map__contents"
        :style="{
            '--cssTileSize': cssTileSize,
            '--borderWidth': cssBorderWidth,
        }"
    >
        <render-star
            v-for="star in stars"
            :key="star.id"
            :zoom="zoom"
            :bg-pos="bgPos[star.spectral]"
            :id="star.id"
            :top="tileSize * star.y + 'px'"
            :left="tileSize * star.x + 'px'"
            :ticker="star.ownerTicker"
            :owner-id="star.ownerId"
            :owner-colour="star.ownerColour"
        />
    </div>
</template>

<style lang="scss" scoped>
.map__contents {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 5;
}
</style>
