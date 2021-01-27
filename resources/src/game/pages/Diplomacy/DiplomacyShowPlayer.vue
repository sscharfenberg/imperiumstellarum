<script>
/******************************************************************************
 * PageComponent: DiplomacyShowPlayer
 *****************************************************************************/
export default {
    name: "DiplomacyShowPlayer",
    props: {
        player: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const onClick = () => {
            console.log("open modal for player ", props.player.id);
        };
        return { onClick };
    },
};
</script>

<template>
    <button class="player" @click="onClick">
        <span
            class="player__ticker"
            :style="{ '--playerColour': '#' + player.colour }"
            >[{{ player.ticker }}]</span
        >
        <span class="player__name">{{ player.name }}</span>
        <ul class="player__relations">
            <li
                class="player__relation"
                :class="{
                    allied: player.relationSet === 2,
                    neutral: player.relationSet === 1,
                    hostile: player.relationSet === 0,
                    unset: player.relationSet === 9,
                }"
            >
                {{ $t("diplomacy.list.relation.set") }}
                <div>
                    {{ player.relationSet !== 9 ? player.relationSet : "-" }}
                </div>
            </li>
            <li
                class="player__relation"
                :class="{
                    allied: player.relationEffective === 2,
                    neutral: player.relationEffective === 1,
                    hostile: player.relationEffective === 0,
                }"
            >
                {{ $t("diplomacy.list.relation.effective") }}
                <div>{{ player.relationEffective }}</div>
            </li>
        </ul>
    </button>
</template>

<style lang="scss" scoped>
.player {
    display: flex;
    flex-direction: column;

    padding: 0;
    border: 2px solid transparent;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-bunker");
        color: t("t-light");
        border-color: t("g-deep");
    }

    &:hover {
        @include themed() {
            background-color: t("g-ebony");
        }
    }

    &__ticker {
        display: block;

        width: 100%;
        padding: 8px;

        background-color: var(--playerColour);

        font-weight: 600;
        text-align: center;

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
            letter-spacing: 2px;
        }

        @include respond-to("medium") {
            padding: 16px;
        }
    }

    &__name {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 60px;
        padding: 4px;

        @include respond-to("medium") {
            height: 80px;
            padding: 8px;
        }
    }

    &__relations {
        display: grid;
        grid-template-columns: 1fr 1fr;

        width: calc(100% - 16px);
        padding: 0;
        margin: 0 8px 8px 8px;
        grid-gap: 8px;

        list-style: none;
    }

    &__relation {
        padding: 4px;
        border: 2px solid transparent;

        @include respond-to("medium") {
            padding: 8px;
        }

        &.allied {
            @include themed() {
                border-color: t("s-success");
            }
        }
        &.hostile {
            @include themed() {
                border-color: t("s-error");
            }
        }
        &.neutral {
            @include themed() {
                border-color: t("s-building");
            }
        }
        &.unset {
            @include themed() {
                border-color: t("g-deep");
            }
        }

        > div {
            margin-top: 8px;

            font-size: 20px;
            font-weight: 600;
        }
    }
}
</style>
