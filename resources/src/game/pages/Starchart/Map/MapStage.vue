<script>
/******************************************************************************
 * PageComponent: StarMap
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import { zoomToTileSize } from "./useMap";
import MapRuler from "./MapRuler";
import DraggableMap from "./DraggableMap";
import ZoomNavigation from "./ZoomNavigation";
export default {
    name: "MapStage",
    components: { MapRuler, DraggableMap, ZoomNavigation },
    props: {
        dimensions: Number,
    },
    setup() {
        const store = useStore();
        const zoom = computed(() => store.state.starchart.zoomLevel);
        const tileSize = computed(() => zoomToTileSize(zoom.value));
        const cameraX = computed(() => store.state.starchart.cameraX);
        const cameraY = computed(() => store.state.starchart.cameraY);
        return {
            tileSize,
            cameraX,
            cameraY,
            zoom,
        };
    },
};
</script>

<template>
    {{ cameraX }}, {{ cameraY }} @ {{ zoom }}
    <div class="map">
        <div class="map__wrapper">
            <map-ruler
                direction="horizontal"
                :dimensions="dimensions"
                :offset="cameraX"
                :tile-size="tileSize"
            />
            <div class="bottom">
                <map-ruler
                    direction="vertical"
                    :dimensions="dimensions"
                    :offset="cameraY"
                    :tile-size="tileSize"
                />
                <div class="map__content" id="mapWrapper">
                    <draggable-map
                        :zoom="zoom"
                        :dimensions="dimensions"
                        :tileSize="tileSize"
                        :cameraX="cameraX"
                        :cameraY="cameraY"
                    />
                    <zoom-navigation
                        :zoom="zoom"
                        :dimensions="dimensions"
                        :tileSize="tileSize"
                        :cameraX="cameraX"
                        :cameraY="cameraY"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.map {
    position: relative;

    @include themed() {
        background-color: t("g-sunken");
    }

    &::before {
        display: block;

        padding-top: 100%;

        content: " ";
    }

    &__wrapper {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

        padding: 0.4rem;

        @include respond-to("medium") {
            padding: 0.8rem;
        }
    }

    .bottom {
        //display: flex;

        height: calc(100% - 2.8rem);
        margin-top: 4px;
        //flex: 0 0 100%;

        @include respond-to("medium") {
            height: calc(100% - 3.2rem);
            margin-top: 8px;
        }

        @extend %clearfix;
    }

    &__content {
        position: relative;
        float: left;

        overflow: hidden;
        width: calc(100% - 28px);
        height: 100%;
        margin-left: 4px;

        @include respond-to("medium") {
            width: calc(100% - 32px);
            margin-left: 8px;
        }

        @include themed() {
            border: 1px solid t("g-deep");

            background: transparent;
        }
    }
}
</style>
