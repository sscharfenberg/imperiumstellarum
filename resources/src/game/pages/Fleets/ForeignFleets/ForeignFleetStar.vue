<script>
/******************************************************************************
 * Page: ForeignFleetStar
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "ForeignFleetStar",
    props: {
        coordX: Number,
        coordY: Number,
        starName: String,
        ownStar: Boolean,
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="aside">
        <span
            v-if="ownStar"
            class="aside__ownstar"
            :title="$t('fleets.other.ownStar')"
            :aria-label="$t('fleets.other.ownStar')"
        >
            <icon name="warning" />
        </span>
        <span
            class="aside__star-name"
            :title="$t('fleets.other.starName')"
            :aria-label="$t('fleets.other.starName')"
            >{{ starName }}</span
        >
        <span
            class="aside__location"
            :title="$t('fleets.other.starLocation')"
            :aria-label="$t('fleets.other.starLocation')"
        >
            <icon name="location" />
            {{ coordX }}/{{ coordY }}
        </span>
    </aside>
</template>

<style lang="scss" scoped>
.aside {
    display: flex;
    align-items: center;

    gap: 4px;

    @include respond-to("medium") {
        margin-left: auto;
        gap: 8px;
    }

    &__ownstar,
    &__star-name,
    &__location {
        padding: 5px;

        @include themed() {
            background-color: t("g-bunker");
            color: t("t-subdued");
        }
    }

    &__location .icon {
        margin-right: 4px;

        @include themed() {
            color: darken(t("t-subdued"), 25%);
        }
    }

    &__ownstar {
        padding: 4px;
        border: 1px solid transparent;

        @include themed() {
            border-color: t("s-warning");
        }

        .icon {
            width: 22px;
            height: 22px;
            flex: 0 0 22px;

            @include themed() {
                color: t("s-warning");
            }
        }
    }
}
</style>
