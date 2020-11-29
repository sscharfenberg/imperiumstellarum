<script>
/******************************************************************************
 * PageComponent: ZoomNavigation
 *****************************************************************************/
import { useStore } from "vuex";
import { onMounted, computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "ZoomNavigation",
    props: {
        zoom: Number, // 0..4
        dimensions: Number, // map dimensions in coords
        cameraX: Number,
        cameraY: Number,
        tileSize: Number, // pixel size for a tile
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const zoomInDisabled = computed(() => props.zoom === 4);
        const zoomOutDisabled = computed(() => props.zoom === 0);

        /**
         * @function get TileSize from zoomLevel
         * @param {Number} zoom
         * @returns {Number}
         */
        const getTileSizeFromZoom = (zoom) => {
            if (zoom < 0 || zoom > 4) return false;
            switch (zoom) {
                case 0:
                    return 20;
                case 1:
                    return 35;
                case 2:
                default:
                    return 50;
                case 3:
                    return 65;
                case 4:
                    return 80;
            }
        };

        /**
         * @function get the approximately centered coords from camera offset
         * @param {Number} x
         * @param {Number} y
         * @param {Number} tileSize
         * @returns {Object}
         */
        const convertCameraToCenteredCoords = (x, y, tileSize) => {
            const currentViewPortSize = document.getElementById("mapWrapper")
                .offsetWidth;
            return {
                x: Math.round(
                    x / tileSize + currentViewPortSize / (tileSize * 2)
                ),
                y: Math.round(
                    y / tileSize + currentViewPortSize / (tileSize * 2)
                ),
            };
        };

        /**
         * @function get the camera offsets from centered coords
         * @param {Number} x
         * @param {Number} y
         * @param {Number} tileSize
         * @returns {Object}
         */
        const convertCenteredCoordsToCamera = (x, y, tileSize) => {
            const currentViewPortSize = document.getElementById("mapWrapper")
                .offsetWidth;
            const centerCoord = currentViewPortSize / tileSize / 2;
            const coordsOffScreenX = x - centerCoord;
            const coordsOffScreenY = y - centerCoord;
            let cameraX = parseInt(coordsOffScreenX * tileSize, 10);
            let cameraY = parseInt(coordsOffScreenY * tileSize, 10);
            // check if we would have empty space to the right
            if (tileSize * props.dimensions < cameraX + currentViewPortSize) {
                cameraX = parseInt(
                    tileSize * props.dimensions - currentViewPortSize,
                    10
                );
            }
            // ... or below the map
            if (tileSize * props.dimensions < cameraY + currentViewPortSize) {
                cameraY = parseInt(
                    tileSize * props.dimensions - currentViewPortSize,
                    10
                );
            }
            // make sure we don't get negative numbers (negative camera => outside of map)
            if (cameraX < 0) cameraX = 0;
            if (cameraY < 0) cameraY = 0;
            return {
                x: cameraX,
                y: cameraY,
            };
        };

        /**
         * @function change zoom level
         * @param {Number} newZoomLevel
         */
        const doZoom = (newZoomLevel) => {
            const coords = convertCameraToCenteredCoords(
                props.cameraX,
                props.cameraY,
                props.tileSize
            );
            const newCamera = convertCenteredCoordsToCamera(
                coords.x,
                coords.y,
                getTileSizeFromZoom(newZoomLevel)
            );
            store.commit("starchart/SET_CAMERA", {
                x: newCamera.x,
                y: newCamera.y,
            });
            const _map = document.getElementById("draggableMap");
            _map.setAttribute("data-x", -newCamera.x);
            _map.setAttribute("data-y", -newCamera.y);
            store.commit("starchart/SET_ZOOM", newZoomLevel);
        };

        /**
         * @function eventListener for mousewheel
         * @param {MouseEvent} ev
         */
        const onWheelZoom = (ev) => {
            ev.preventDefault();
            if (ev.deltaY && ev.deltaY < 0 && !zoomInDisabled.value)
                doZoom(props.zoom + 1);
            if (ev.deltaY && ev.deltaY > 0 && !zoomOutDisabled.value)
                doZoom(props.zoom - 1);
        };

        /**
         * @function mounted hook: add event listener for mousewheel
         */
        onMounted(() => {
            // TODO: return false if map < viewport
            document
                .getElementById("mapWrapper")
                .addEventListener("wheel", onWheelZoom);
        });

        return {
            doZoom,
            zoomInDisabled,
            zoomOutDisabled,
        };
    },
};
</script>

<template>
    <nav class="zoom">
        <button @click="doZoom(zoom + 1)" :disabled="zoomInDisabled">
            <icon name="add" />
        </button>
        <button @click="doZoom(zoom - 1)" :disabled="zoomOutDisabled">
            <icon name="remove" />
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.zoom {
    display: flex;
    position: absolute;
    right: 8px;
    bottom: 8px;
    z-index: z("header");
    flex-direction: column;
}

button {
    display: flex;
    align-items: center;
    justify-content: center;

    width: 30px;
    height: 30px;
    padding: 0;
    margin-bottom: 2px;

    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        border: 1px solid t("g-deep");

        background: t("g-bunker");
        color: t("t-light");
    }

    @include respond-to("medium") {
        margin-bottom: 4px;
    }

    &:last-child {
        margin-bottom: 0;
    }

    &:hover:not([disabled]),
    &:focus {
        outline: 0;

        @include themed() {
            background: t("g-ebony");
            color: t("b-viking");
            border-color: t("g-raven");
        }
    }

    &[disabled] {
        cursor: not-allowed;

        .icon {
            opacity: 0.2;
        }
    }
}
</style>
