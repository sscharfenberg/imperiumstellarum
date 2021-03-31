<script>
/******************************************************************************
 * Component: Header
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import FetchButton from "./FetchButton";
import Icon from "Components/Icon/Icon";
import PlayerResources from "./Resources/PlayerResources";
export default {
    name: "GameHeader",
    components: { Icon, FetchButton, PlayerResources },
    props: {
        area: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const state = useStore().state;
        const className = computed(() => props.area.toLowerCase());
        const name = computed(() => state.empireName);
        const ticker = computed(() => state.empireTicker);
        const requesting = computed(() => state[props.area].requesting);
        const turnSlug = computed(
            () => `g${state.gameNumber}t${state.gameTurn}`
        );
        return {
            className,
            name,
            ticker,
            requesting,
            turnSlug,
        };
    },
};
</script>

<template>
    <header :class="className">
        <aside class="area">
            <icon :name="area" :size="1" />
            {{ $t(area + ".title") }}
        </aside>
        <aside v-if="ticker" class="game">{{ turnSlug }}</aside>
        <fetch-button :area="area" />
        <h1>
            <span v-if="ticker">[{{ ticker }}]</span>
            <span v-if="name">{{ name }}</span>
        </h1>
        <player-resources />
    </header>
</template>

<style lang="scss" scoped>
header {
    display: flex;
    position: relative;
    align-items: flex-start;
    flex-wrap: wrap;

    border: 2px solid transparent;
    margin: 0 0 16px 0;

    background: transparent none 50% 50% no-repeat;
    background-size: cover;

    @include respond-to("small") {
        margin: 0 0 32px 0;
    }

    @include themed() {
        background-color: t("g-bunker");
        color: t("t-light");
        border-color: t("g-deep");
    }

    &.empire {
        @include themed() {
            background-image: t("empire");
        }
    }
    &.fleets {
        @include themed() {
            background-image: t("fleets");
        }
    }
    &.shipyards {
        @include themed() {
            background-image: t("shipyards");
        }
    }
    &.research {
        @include themed() {
            background-image: t("research");
        }
    }
    &.starchart {
        @include themed() {
            background-image: t("starchart");
        }
    }
    &.diplomacy {
        @include themed() {
            background-image: t("diplomacy");
        }
    }
    &.messages {
        @include themed() {
            background-image: t("messages");
        }
    }
    &.encounters {
        @include themed() {
            background-image: t("encounters");
        }
    }
}

.area {
    $padding: 8px;
    display: flex;
    align-items: center;

    padding: 4px 22px 4px 6px;

    clip-path: polygon(0% 0%, 100% 0%, calc(100% - 20px) 100%, 0% 100%);

    font-size: 20px;
    font-weight: 300;
    line-height: 1;
    text-transform: capitalize;

    @include themed() {
        background: rgba(t("g-raven"), 0.4);
        color: t("t-light");
    }

    @include respond-to("medium") {
        padding: 8px 36px 8px 12px;

        clip-path: polygon(0% 0%, 100% 0%, calc(100% - 30px) 100%, 0% 100%);
    }

    .icon {
        margin-right: 4px;

        @include respond-to("medium") {
            margin-right: 8px;
        }

        @include themed() {
            color: t("b-viking");
        }
    }
}

.game {
    $padding: 8px;
    display: flex;
    align-items: center;

    padding: 4px 22px;
    margin-left: -12px;

    clip-path: polygon(20px 0%, 100% 0%, calc(100% - 20px) 100%, 0% 100%);

    font-size: 20px;
    font-weight: 300;
    line-height: 1;

    @include themed() {
        background: rgba(t("g-raven"), 0.4);
        color: t("t-light");
    }

    @include respond-to("medium") {
        padding: 8px 36px;
        margin-left: -16px;

        clip-path: polygon(30px 0%, 100% 0%, calc(100% - 30px) 100%, 0% 100%);
    }
}

h1 {
    padding: 0 4px 4px 4px;
    margin: 0;
    flex: 0 1 100%;

    font-size: 28px;

    font-weight: 400;
    line-height: 1.4;
    letter-spacing: 2px;

    @include themed() {
        color: t("t-light");

        text-shadow: 1px 1px 0 t("g-bunker"), -1px 1px 0 t("g-bunker"),
            -1px -1px 0 t("g-bunker"), 1px -1px 0 t("g-bunker");
    }

    @include respond-to("medium") {
        padding: 0 16px 16px 16px;
    }

    > span:first-of-type {
        padding-right: 6px;

        @include respond-to("medium") {
            padding-right: 12px;
        }
    }
}
</style>
