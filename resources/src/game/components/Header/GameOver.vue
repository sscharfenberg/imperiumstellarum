<script>
/******************************************************************************
 * Component: GameOver
 *****************************************************************************/
export default {
    name: "GameOver",
    props: {
        winnerTicker: {
            type: String,
            required: true,
        },
        winnerColour: {
            type: String,
            required: true,
        },
        winnerName: {
            type: String,
            required: true,
        },
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="gameover">
        <h1>{{ $t("common.header.gameOver.headline") }}</h1>
        <p>
            {{ $t("common.header.gameOver.explanation") }}
            <span
                class="winner"
                :style="{ 'background-color': '#' + winnerColour }"
                >[{{ winnerTicker }}] {{ winnerName }}</span
            >
            !
        </p>
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

    .winner {
        padding: 4px 8px;

        letter-spacing: 2px;

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
        }
    }
}
</style>
