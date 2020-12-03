<script>
/******************************************************************************
 * PageComponent: MapSystem
 *****************************************************************************/
export default {
    name: "MapSystem",
    props: {
        id: String,
        bgPos: String,
        top: String,
        left: String,
        borderColor: String,
        ticker: String,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <div
        class="star"
        :style="{
            '--bgPos': bgPos,
            top: top,
            left: left,
        }"
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

    text-align: right;

    @include themed() {
        border-color: t("b-viking");
    }

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
