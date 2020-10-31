<script>
/******************************************************************************
 * PageComponent: StarLocation
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "StarLocation",
    props: {
        x: Number,
        y: Number,
    },
    components: { Icon },
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <aside
        class="location"
        :aria-label="t('empire.star.location')"
        :title="t('empire.star.location')"
    >
        <div class="location-inner">
            <icon class="location-icon" name="location" />
            <span>{{ x }}/{{ y }}</span>
        </div>
    </aside>
</template>

<style lang="scss" scoped>
.location {
    display: flex;
    align-items: center;

    padding: 0;

    @include themed() {
        background-color: t("g-sunken");
        color: t("t-subdued");
    }

    @include respond-to("small") {
        padding: 0 1rem;
    }
}

.location-inner {
    display: flex;
    align-items: center;

    padding: 0.5rem;
    margin: 0.8rem 0;

    @include themed() {
        background-color: t("g-bunker");
    }

    > .location-icon {
        display: none;

        margin-right: 1rem;

        @include themed() {
            color: darken(t("t-subdued"), 25%);
        }

        @include respond-to("medium") {
            display: block;
        }
    }

    > span {
        min-width: 5rem;

        font-size: 0.8em;
        text-align: center;

        @include respond-to("small") {
            font-size: 1em;
        }
    }
}
</style>
