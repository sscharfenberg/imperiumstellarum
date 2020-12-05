<script>
/******************************************************************************
 * PageComponent: ZoomNavigation
 *****************************************************************************/
import { useStore } from "vuex";
import { onMounted, computed } from "vue";
import {
    zoomToTileSize,
    convertCameraToCenteredCoords,
    convertCenteredCoordsToCamera,
} from "./useMap";
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
         * @function change zoom level
         * @param {Number} newZoomLevel
         */
        const doZoom = (newZoomLevel) => {
            const coords = convertCameraToCenteredCoords(
                props.cameraX,
                props.cameraY,
                props.tileSize,
                document.getElementById("mapWrapper").offsetWidth
            );
            const newCamera = convertCenteredCoordsToCamera(
                coords.x,
                coords.y,
                zoomToTileSize(newZoomLevel),
                document.getElementById("mapWrapper").offsetWidth,
                props.dimensions
            );
            store.commit("starchart/SET_CAMERA", {
                x: newCamera.x,
                y: newCamera.y,
            });
            const _map = document.getElementById("draggableMap");
            _map.setAttribute("data-x", -newCamera.x);
            _map.setAttribute("data-y", -newCamera.y);
            store.commit("starchart/SET_ZOOM", newZoomLevel);
            // filter and commit stars to show.
            store.commit(
                "starchart/SET_STARS_SHOWN",
                store.state.starchart.stars
            );
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
