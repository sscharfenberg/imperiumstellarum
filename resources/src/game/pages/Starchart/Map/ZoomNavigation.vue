<script>
/******************************************************************************
 * PageComponent: ZoomNavigation
 *****************************************************************************/
import { useStore } from "vuex";
import { onMounted, computed, onUnmounted } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "ZoomNavigation",
    props: {
        zoom: Number, // 0..4
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const zoomIn = () => {
            store.commit("starchart/SET_ZOOM", props.zoom + 1);
        };
        const zoomOut = () => {
            store.commit("starchart/SET_ZOOM", props.zoom - 1);
        };
        const zoomInDisabled = computed(() => props.zoom === 4);
        const zoomOutDisabled = computed(() => props.zoom === 0);
        const wheelZoom = (ev) => {
            ev.preventDefault();
            if (ev.deltaY && ev.deltaY < 0 && !zoomInDisabled.value) zoomIn();
            if (ev.deltaY && ev.deltaY > 0 && !zoomOutDisabled.value) zoomOut();
        };
        onMounted(() => {
            document
                .getElementById("mapWrapper")
                .addEventListener("wheel", wheelZoom);
        });
        onUnmounted(() => {
            document
                .getElementById("mapWrapper")
                .removeEventListener("wheel", wheelZoom);
        });
        return {
            zoomIn,
            zoomOut,
            zoomInDisabled,
            zoomOutDisabled,
        };
    },
};
</script>

<template>
    <nav class="zoom">
        <button @click="zoomIn" :disabled="zoomInDisabled">
            <icon name="add" />
        </button>
        <button @click="zoomOut" :disabled="zoomOutDisabled">
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
