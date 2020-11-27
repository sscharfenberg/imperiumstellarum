<script>
/******************************************************************************
 * PageComponent: MapRuler
 *****************************************************************************/
export default {
    name: "MapRuler",
    props: {
        dimensions: Number,
        direction: String,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="ruler-stage" :class="direction">
        <ul class="ruler" :class="direction">
            <li v-for="coord in dimensions" :key="coord">{{ coord }}</li>
        </ul>
    </aside>
</template>

<style lang="scss" scoped>
.ruler-stage {
    position: relative;

    overflow: hidden;

    &.vertical {
        margin-top: 0.2rem;
        flex: 0 0 2.4rem;

        @include respond-to("medium") {
            margin-top: 0.6rem;
        }
    }

    &.horizontal {
        height: 2.4rem;
        margin-left: 2.8rem;
        flex: 0 0 calc(100% - 2.8rem);

        @include respond-to("medium") {
            margin-left: 3.2rem;
            flex: 0 0 calc(100% - 3.2rem);
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

    padding: 0;
    margin: 0;

    list-style: none;

    > li {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &.horizontal > li {
        border-right: 1px solid transparent;
        flex: 0 0 5rem;

        &:last-child {
            border-right: 0;
        }

        @include themed() {
            border-right-color: t("g-deep");
        }
    }

    &.vertical {
        //top: -350px; <- this is how we position the ruler.
        flex-wrap: wrap;

        > li {
            height: 5rem;
            border-bottom: 1px solid transparent;
            flex: 0 0 100%;

            &:last-child {
                border-bottom: 0;
            }

            @include themed() {
                border-bottom-color: t("g-deep");
            }
        }
    }
}
</style>
