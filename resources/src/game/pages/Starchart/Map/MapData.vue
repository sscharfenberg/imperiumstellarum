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
        const x = computed(() => store.state.starchart.jumpCoordX);
        const y = computed(() => store.state.starchart.jumpCoordY);
        const playerFleets = computed(() => store.state.starchart.fleets);
        const numStarFleets = (starId) => {
            return playerFleets.value.filter((f) => f.starId === starId).length;
        };
        const shipyards = computed(() => store.state.starchart.shipyards);
        const hasShipyard = (starId) =>
            shipyards.value.filter((s) => s.starId === starId).length;
        const fleetMovements = computed(
            () => store.state.starchart.fleetMovements
        );
        const starFleetTransit = (starId) =>
            fleetMovements.value.filter((fm) => fm.destinationId === starId)
                .length;

        // number values
        const borderWidth = computed(() => (props.zoom >= 3 ? 3 : 1));
        const availableTilePixels = computed(
            () => props.tileSize - 1 - 2 * borderWidth.value
        );
        const relations = computed(() => store.state.starchart.relations);
        const getEmpireRelation = (playerId) => {
            if (!playerId) return undefined;
            const rel = relations.value.find((r) => r.playerId === playerId);
            if (rel && rel.effective >= 0) {
                return rel.effective;
            } else {
                return 1;
            }
        };

        // number of foreign fleets with relation
        const foreignFleetsHostile = (starId) =>
            store.state.starchart.foreignFleets.filter(
                (f) => f.starId === starId && f.playerRelation === 0
            ).length;

        const foreignFleetsAllied = (starId) =>
            store.state.starchart.foreignFleets.filter(
                (f) => f.starId === starId && f.playerRelation === 2
            ).length;

        const foreignFleetsNeutral = (starId) =>
            store.state.starchart.foreignFleets.filter(
                (f) => f.starId === starId && f.playerRelation === 1
            ).length;

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
            // did we come here from a different page?
            if (x.value && y.value) {
                // focus coordinates on map. this includes filtering, so we don't need to do it again
                store.commit("starchart/FOCUS_COORDS", {
                    x: x.value,
                    y: y.value,
                });
                // reset jump coords so we don't focus coords again when revisiting the page.
                store.commit("starchart/RESET_JUMP_COORDS");
            } else {
                // filter stars
                store.commit(
                    "starchart/SET_STARS_SHOWN",
                    store.state.starchart.stars
                );
            }
        });

        return {
            stars,
            cssTileSize,
            bgPos,
            cssBorderWidth,
            getEmpireRelation,
            numStarFleets,
            hasShipyard,
            starFleetTransit,
            foreignFleetsHostile,
            foreignFleetsAllied,
            foreignFleetsNeutral,
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
            :name="star.name"
            :owner-id="star.ownerId"
            :owner-colour="star.ownerColour"
            :num-fleets="numStarFleets(star.id)"
            :has-shipyard="!!hasShipyard(star.id)"
            :transit-fleets="starFleetTransit(star.id)"
            :relation-status="getEmpireRelation(star.ownerId)"
            :foreign-fleets-hostile="foreignFleetsHostile(star.id)"
            :foreign-fleets-allied="foreignFleetsAllied(star.id)"
            :foreign-fleets-neutral="foreignFleetsNeutral(star.id)"
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
