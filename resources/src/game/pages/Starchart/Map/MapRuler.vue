<script>
/******************************************************************************
 * PageComponent: MapRuler
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "MapRuler",
    props: {
        direction: String, // "horizontal" || "vertical"
        dimensions: Number, // map dimensions. the number of <li>s we have to render
        offset: Number, // offset from camera 0
        tileSize: Number, // pixel size for a <li>
        zoom: Number, // [0..4]
    },
    setup(props) {
        const offsetStyle = computed(() => {
            let rtn = "";
            if (props.direction === "horizontal")
                rtn = "transform: translate3d(-" + props.offset + "px, 0, 0)";
            if (props.direction === "vertical")
                rtn = "transform: translate3d(0, -" + props.offset + "px, 0)";
            return rtn;
        });
        const tileStyle = computed(() => {
            let rtn = "";
            if (props.direction === "horizontal") {
                let style = "flex: 0 0 " + props.tileSize + "px;";
                style += " width: " + props.tileSize + "px;";
                if (props.zoom === 0) style += "font-size: 12px;";
                rtn = style;
            }
            if (props.direction === "vertical")
                rtn = "height: " + props.tileSize + "px";
            return rtn;
        });
        return {
            offsetStyle,
            tileStyle,
        };
    },
};
</script>

<template>
    <aside class="ruler-stage" :class="direction">
        <ul
            class="ruler"
            :class="direction"
            :style="offsetStyle"
            :id="`mapRuler${
                direction.charAt(0).toUpperCase() + direction.slice(1)
            }`"
        >
            <li v-for="coord in dimensions" :key="coord" :style="tileStyle">
                {{ coord - 1 }}
            </li>
        </ul>
    </aside>
</template>

<style lang="scss" scoped>
.ruler-stage {
    position: relative;

    overflow: hidden;

    &.vertical {
        float: left;

        width: 24px;
        height: 100%;
    }

    &.horizontal {
        width: calc(100% - 28px);
        height: 24px;
        margin-left: 28px;

        @include respond-to("medium") {
            width: calc(100% - 32px);
            margin-left: 32px;
        }
    }

    @include themed() {
        border: 1px solid t("g-deep");

        background-color: t("g-bunker");
    }
}

.ruler {
    display: flex;
    position: absolute;

    overflow: hidden;
    padding: 0;
    margin: 0;

    list-style: none;

    > li {
        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 14px;
        line-height: 1;

        @include themed() {
            color: t("t-tint");
        }
    }

    &.horizontal > li {
        height: 24px;

        border-right: 1px solid transparent;

        @include themed() {
            border-right-color: t("g-deep");
        }
    }

    &.vertical {
        flex-wrap: wrap;

        > li {
            border-bottom: 1px solid transparent;
            flex: 0 0 100%;

            @include themed() {
                border-bottom-color: t("g-deep");
            }
        }
    }
}
</style>
