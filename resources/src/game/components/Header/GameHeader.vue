<script>
/******************************************************************************
 * Component: Header
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
import FetchButton from "./FetchButton";
import PlayerResources from "./Resources/PlayerResources";
export default {
    name: "GameHeader",
    components: { Icon, FetchButton, PlayerResources },
    props: {
        area: {
            type: String,
            required: true,
        },
        headline: {
            type: String,
            required: true,
        },
    },
    setup(props) {
        const state = useStore().state;
        const className = computed(() => props.area.toLowerCase());
        const name = computed(() => state.empireName);
        const ticker = computed(() => state.empireTicker);
        return {
            className,
            name,
            ticker,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <header :class="className">
        <aside class="area">
            <icon :name="area" :size="1" />
            {{ t(area + ".title") }}
        </aside>
        <fetch-button :area="area" />
        <h1>[{{ ticker }}] {{ name }}</h1>
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
    margin: 0 0 1.6rem 0;

    background: transparent none 50% 50% no-repeat;
    background-size: cover;

    @include respond-to("small") {
        margin: 0 0 3.2rem 0;
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
    &.galnet {
        @include themed() {
            background-image: t("diplomacy");
        }
    }
}

.area {
    $padding: 8px;
    display: flex;
    align-items: center;

    padding: $padding #{$padding * 4.5} $padding #{$padding * 1.5};

    clip-path: polygon(0% 0%, 100% 0%, calc(100% - 30px) 100%, 0% 100%);

    font-size: 2rem;
    font-weight: 300;
    line-height: 1;
    text-transform: capitalize;

    @include themed() {
        background: rgba(t("g-raven"), 0.4);
        color: t("t-light");
    }

    .icon {
        margin-right: 0.8rem;

        @include themed() {
            color: t("b-viking");
        }
    }
}

h1 {
    padding: 1.6rem;
    margin: 0;
    flex: 0 1 100%;

    font-size: 2.8rem;

    font-weight: 400;
    line-height: 1.4;
    letter-spacing: 0.2rem;

    @include themed() {
        color: t("t-light");

        text-shadow: 1px 1px 0 t("g-bunker"), -1px 1px 0 t("g-bunker"),
            -1px -1px 0 t("g-bunker"), 1px -1px 0 t("g-bunker");
    }
}
</style>
