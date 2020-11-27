<script>
/******************************************************************************
 * PageComponent: StarMap
 *****************************************************************************/
import Ruler from "./Ruler";
export default {
    name: "StarMap",
    components: { Ruler },
    props: {
        dimensions: Number,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="map">
        <div class="map__wrapper">
            <ruler :dimensions="dimensions" direction="horizontal" />
            <div class="stage">
                <ruler :dimensions="dimensions" direction="vertical" />
                <div class="map__content">Map Map</div>
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
        display: flex;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        flex-direction: row;
        flex-wrap: wrap;

        padding: 0.4rem;

        @include respond-to("medium") {
            padding: 0.8rem;
        }
    }

    .stage {
        display: flex;

        height: calc(100% - 2.8rem);
        flex: 0 0 100%;

        @include respond-to("medium") {
            height: calc(100% - 3.2rem);
        }
    }

    &__content {
        margin: 0.2rem 0 0 0.4rem;
        flex-grow: 1;

        @include themed() {
            border: 1px solid t("g-deep");

            background: transparent;
        }

        @include respond-to("medium") {
            margin: 0.6rem 0 0 0.8rem;
        }
    }
}
</style>
