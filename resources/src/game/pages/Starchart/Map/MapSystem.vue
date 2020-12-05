<script>
/******************************************************************************
 * PageComponent: MapSystem
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "MapSystem",
    props: {
        zoom: Number, // 0..4
        id: String,
        bgPos: String,
        top: String,
        left: String,
        ownerId: String,
        ownerColour: String,
        ticker: String,
    },
    setup(props) {
        const store = useStore();
        const empireId = computed(() => store.state.empireId);
        const starClass = computed(() => {
            if (empireId.value === props.ownerId) {
                return "my";
            }
            return "";
        });
        const bgColour = computed(() =>
            props.zoom <= 2 ? props.ownerColour : "transparent"
        );
        return {
            bgColour,
            starClass,
        };
    },
};
</script>

<template>
    <div
        class="star"
        :class="starClass"
        :style="{
            '--bgPos': bgPos,
            '--ownerColour': ownerColour,
            top: top,
            left: left,
            backgroundColor: bgColour,
        }"
        :title="ticker"
    >
        <aside class="ticker" v-if="ticker">{{ ticker }}</aside>
    </div>
</template>

<style lang="scss" scoped>
.star {
    position: absolute;

    box-sizing: content-box;
    overflow: hidden;
    width: var(--cssTileSize);
    height: var(--cssTileSize);
    border-width: var(--borderWidth);

    background: url("@/theme/spectral-types.png") var(--bgPos) no-repeat;
    background-size: var(--cssTileSize);
    border-style: dashed;
    border-color: var(--ownerColour);

    text-align: right;

    &.my {
        border-style: solid;
    }
}

.ticker {
    position: absolute;
    top: 0;
    right: 0;

    padding: 2px 4px;

    font-size: calc(var(--cssTileSize) / 5);

    @include themed() {
        background: rgba(t("g-raven"), 0.5);
    }
}
</style>
