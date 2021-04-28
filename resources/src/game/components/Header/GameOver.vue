<script>
/******************************************************************************
 * Component: GameOver
 *****************************************************************************/
export default {
    name: "GameOver",
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="gameover">
        <h1>{{ $t("common.header.gameOver.headline") }}</h1>
        <p>{{ $t("common.header.gameOver.explanation") }}</p>
    </aside>
</template>

<style lang="scss" scoped>
.gameover {
    padding: 8px;
    margin: 0 16px 16px 16px;

    @include themed() {
        background-color: rgba(t("s-success"), 0.8);
        color: t("t-bright");
    }

    h1 {
        margin: 0 0 8px 0;

        @include themed() {
            text-shadow: 1px 1px 0 t("g-bunker"), -1px 1px 0 t("g-bunker"),
                -1px -1px 0 t("g-bunker"), 1px -1px 0 t("g-bunker");
        }
    }

    p {
        margin: 0 0 8px 0;

        &:last-child {
            margin: 0;
        }
    }
}
</style>
