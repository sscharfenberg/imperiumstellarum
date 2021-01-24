<script>
/******************************************************************************
 * Page: MapLegend
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref, onMounted } from "vue";
import Icon from "Components/Icon/Icon";
import {
    zoomToTileSize,
    convertCameraToCenteredCoords,
} from "Pages/Starchart/Map/useMap";
export default {
    name: "MapLegend",
    components: { Icon },
    setup() {
        const store = useStore();
        const zoom = computed(() => store.state.starchart.zoomLevel);
        const viewPortSize = ref(0);
        const coords = computed(() => {
            const tileSize = zoomToTileSize(zoom.value);
            const cameraX = store.state.starchart.cameraX;
            const cameraY = store.state.starchart.cameraY;
            return convertCameraToCenteredCoords(
                cameraX,
                cameraY,
                tileSize,
                viewPortSize.value
            );
        });
        onMounted(() => {
            viewPortSize.value = document.getElementById(
                "mapWrapper"
            ).offsetWidth;
        });
        return { zoom, coords };
    },
};
</script>

<template>
    <teleport to="#MapLegend">
        <ul class="legend">
            <li class="legend__item">
                <icon class="legend__icon legend__icon--neutral" name="info" />
                {{ $t("starchart.map.legend.zoomLevel") }}: {{ zoom }}
            </li>
            <li class="legend__item">
                <icon class="legend__icon legend__icon--neutral" name="info" />
                {{ $t("starchart.map.legend.coords") }}: {{ coords.x }} /
                {{ coords.y }}
            </li>
            <li class="legend__item" v-if="zoom > 2">
                <icon class="legend__icon legend__icon--yes" name="done" />
                {{ $t("starchart.map.legend.starDetailIcons.yes") }}
            </li>
            <li class="legend__item" v-if="zoom <= 2">
                <icon class="legend__icon legend__icon--no" name="cancel" />
                {{ $t("starchart.map.legend.starDetailIcons.no") }}
            </li>
        </ul>
    </teleport>
</template>

<style lang="scss" scoped>
.legend {
    padding: 4px;
    margin: 0;

    list-style: none;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }

    &__item {
        display: flex;
        align-items: center;

        margin-top: 4px;

        &:first-child {
            margin-top: 0;
        }
    }

    &__icon {
        padding: 2px;
        border: 1px solid transparent;
        margin-right: 8px;

        &--yes {
            @include themed() {
                background-color: t("s-active");
                color: t("t-white");
                border-color: t("s-success");
            }
        }

        &--no {
            @include themed() {
                background-color: t("s-warning");
                color: t("s-error");
                border-color: t("s-error");
            }
        }

        &--neutral {
            @include themed() {
                background-color: t("g-deep");
                color: t("t-light");
                border-color: t("g-abbey");
            }
        }
    }
}
</style>
