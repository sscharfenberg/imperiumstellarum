<script>
/******************************************************************************
 * PageComponent: StarLocation
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import router from "@/game/router";
import Icon from "Components/Icon/Icon";
export default {
    name: "StarLocation",
    props: {
        x: Number,
        y: Number,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const onClick = () => {
            store.commit("starchart/JUMP_COORDS", {
                x: props.x,
                y: props.y,
            });
            router.push({ name: "Starchart" });
        };
        return {
            onClick,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <button
        class="location"
        :aria-label="t('empire.star.location') + ': x=' + x + ', y=' + y"
        :title="t('empire.star.location') + ': x=' + x + ', y=' + y"
        @click="onClick"
    >
        <div class="location-inner">
            <icon class="location-icon" name="location" />
            <span>{{ x }}/{{ y }}</span>
        </div>
    </button>
</template>

<style lang="scss" scoped>
.location {
    display: none;
    align-items: center;

    padding: 0;
    border: 0;

    outline: 0;
    cursor: pointer;

    @include themed() {
        background: t("g-sunken");
        color: t("t-subdued");
    }

    @include respond-to("small") {
        display: flex;
    }

    @include respond-to("medium") {
        padding: 0 10px;
    }

    &:hover .location-inner {
        @include themed() {
            background-color: t("g-ebony");
            color: t("g-bright");
        }
    }
}

.location-inner {
    display: flex;
    align-items: center;

    padding: 5px;
    margin: 8px 0;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-bunker");
    }

    > .location-icon {
        display: none;

        margin-right: 10px;

        @include themed() {
            color: darken(t("t-subdued"), 25%);
        }

        @include respond-to("medium") {
            display: block;
        }
    }

    > span {
        min-width: 50px;

        font-size: 8px;
        text-align: center;

        @include respond-to("small") {
            font-size: 1em;
        }
    }
}
</style>
