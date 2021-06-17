<script>
/******************************************************************************
 * PageComponent: DraggableMap
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, onMounted } from "vue";
import "@interactjs/auto-start";
import "@interactjs/actions/drag";
import "@interactjs/modifiers";
import "@interactjs/dev-tools";
import interact from "@interactjs/interact";
import MapGrid from "./MapGrid";
import MapData from "./MapData";
export default {
    name: "DraggableMap",
    props: {
        zoom: Number, // 0..4
        dimensions: Number, // map dimensions in coords
        cameraX: Number,
        cameraY: Number,
        tileSize: Number, // pixel size for a tile
    },
    components: { MapGrid, MapData },
    setup(props) {
        const store = useStore();
        //const stageSize = () =>
        //    document.getElementById("mapWrapper").offsetWidth;
        const mapStyles = computed(() => {
            return `width: ${props.dimensions * props.tileSize}px; height: ${
                props.dimensions * props.tileSize
            }px; transform: translate3d(-${props.cameraX}px, -${
                props.cameraY
            }px, 0)`;
        });

        /**
         * @function should the dragging be disabled?
         */
        const checkDragPossible = () => {
            const _map = document.getElementById("draggableMap");
            const currentViewPortSize =
                document.getElementById("mapWrapper").offsetWidth;
            const currentMapSize = props.dimensions * props.tileSize;
            if (currentMapSize <= currentViewPortSize) {
                if (!_map.classList.contains("disabled"))
                    _map.classList.add("disabled");
            } else {
                if (_map.classList.contains("disabled"))
                    _map.classList.remove("disabled");
            }
        };

        /**
         * @function dragMove event listener. this gets called a lot.
         * @param {DragEvent} event
         */
        const onDragMove = (event) => {
            const target = event.target;
            checkDragPossible();
            if (target.classList.contains("disabled")) {
                return false;
            }

            // keep the dragged position in the data-x/data-y attributes
            const x =
                (parseFloat(target.getAttribute("data-x")) || 0) + event.dx;
            const y =
                (parseFloat(target.getAttribute("data-y")) || 0) + event.dy;

            // translate the element
            target.style.webkitTransform = target.style.transform =
                "translate3d(" + x + "px, " + y + "px, 0)";

            // update the posiion attributes
            target.setAttribute("data-x", x);
            document.getElementById("mapRulerHorizontal").style.transform =
                "translate3d(" + x + "px, 0, 0)";
            target.setAttribute("data-y", y);
            document.getElementById("mapRulerVertical").style.transform =
                "translate3d(0, " + y + "px, 0)";
        };

        /**
         * @function dragEnd event listener. this updates state/localStorage
         * @param {DragEvent} event
         */
        const onDragEnd = (event) => {
            const target = event.target;
            checkDragPossible();
            if (target.classList.contains("disabled")) {
                return false;
            }

            // keep the dragged position in the data-x/data-y attributes
            const x =
                (parseFloat(target.getAttribute("data-x")) || 0) + event.dx;
            const y =
                (parseFloat(target.getAttribute("data-y")) || 0) + event.dy;

            console.log(`drag end: x=${x}, y=${y}`);
            store.commit("starchart/SET_CAMERA", { x: -x, y: -y });
            // filter and commit stars to show.
            store.commit(
                "starchart/SET_STARS_SHOWN",
                store.state.starchart.stars
            );
        };

        /**
         * @function mounted hook: add event listener for mousewheel
         */
        onMounted(() => {
            const map = document.getElementById("draggableMap");
            map.setAttribute("data-x", -props.cameraX);
            map.setAttribute("data-y", -props.cameraY);
            interact("#draggableMap").draggable({
                //inertia: true,
                listeners: {
                    move: onDragMove,
                    end: onDragEnd,
                },
                modifiers: [
                    interact.modifiers.restrict({
                        restriction: "parent",
                        endOnly: true,
                        elementRect: { top: 0, left: 0, bottom: 1, right: 1 },
                    }),
                ],
            });
            checkDragPossible();
        });
        return {
            mapStyles,
        };
    },
};
</script>

<template>
    <div class="map__actual" :style="mapStyles" id="draggableMap">
        <map-grid
            :dimensions="dimensions"
            :tileSize="tileSize"
            :cameraX="cameraX"
            :cameraY="cameraY"
        />
        <map-data
            :zoom="zoom"
            :dimensions="dimensions"
            :tileSize="tileSize"
            :cameraX="cameraX"
            :cameraY="cameraY"
        />
    </div>
</template>

<style lang="scss" scoped>
.map__actual {
    z-index: -1;

    cursor: move;
    touch-action: none;

    @include themed() {
        background: t("g-black");
    }

    &.disabled {
        cursor: default !important;
    }
}
</style>
