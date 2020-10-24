<script>
/******************************************************************************
 * Component: Header
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
import FetchButton from "./FetchButton";
export default {
    name: "GameHeader",
    components: { Icon, FetchButton },
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
            {{ area }}
        </aside>
        <fetch-button :area="area" />
        <h1>[{{ ticker }}] {{ name }}</h1>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. A
            asperiores culpa excepturi illum natus officiis placeat, quia soluta
            tempora vitae! Alias aperiam at atque cum earum eius est
            exercitationem, fuga, illum iusto maxime, minima minus molestiae
            mollitia natus nulla odit quia ullam vero voluptate. Molestiae
            perspiciatis praesentium quas vero vitae.
        </p>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. A
            asperiores culpa excepturi illum natus officiis placeat, quia soluta
            tempora vitae! Alias aperiam at atque cum earum eius est
            exercitationem, fuga, illum iusto maxime, minima minus molestiae
            mollitia natus nulla odit quia ullam vero voluptate. Molestiae
            perspiciatis praesentium quas vero vitae.
        </p>
    </header>
</template>

<style lang="scss" scoped>
header {
    display: flex;
    position: relative;
    align-items: flex-start;
    flex-wrap: wrap;

    border: 1px solid palette("dark", "g-raven");

    background: palette("dark", "g-bunker") none 50% 50% no-repeat;
    background-size: cover;
    color: palette("dark", "t-light");

    &.empire {
        background-image: url("bg/empire.jpg");
    }
    &.fleets {
        background-image: url("bg/fleets.jpg");
    }
    &.shipyards {
        background-image: url("bg/shipyards.jpg");
    }
    &.research {
        background-image: url("bg/research.jpg");
    }
    &.starchart {
        background-image: url("bg/starchart.jpg");
    }
    &.galnet {
        background-image: url("bg/galnet.jpg");
    }
}

.area {
    $padding: 8px;
    display: flex;
    align-items: center;

    padding: $padding #{$padding * 4.5} $padding #{$padding * 1.5};

    clip-path: polygon(0% 0%, 100% 0%, calc(100% - 30px) 100%, 0% 100%);

    font-size: 2rem;
    line-height: 1;
    text-transform: capitalize;

    @include themed() {
        background: t("g-raven");
        color: t("t-bright");
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
    line-height: 1;
    text-shadow: 1px 1px 0 palette("dark", "g-bunker"),
        -1px 1px 0 palette("dark", "g-bunker"),
        -1px -1px 0 palette("dark", "g-bunker"),
        1px -1px 0 palette("dark", "g-bunker");
    letter-spacing: 0.2rem;
}
</style>
