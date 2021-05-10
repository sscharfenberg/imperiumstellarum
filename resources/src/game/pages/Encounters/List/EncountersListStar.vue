<script>
/******************************************************************************
 * PageComponent: EncountersListStar
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "EncountersListStar",
    props: {
        name: String,
        coordX: Number,
        coordY: Number,
    },
    components: { Icon },
    setup() {},
};
</script>

<template>
    <span class="star">
        <span class="star__name">{{ name }}</span>
        <span class="star__location">
            <icon name="location" />
            {{ coordX }}/{{ coordY }}
        </span>
    </span>
</template>

<style lang="scss" scoped>
.star {
    display: flex;
    align-items: center;

    &__location,
    &__name {
        padding: 4px;
        border: 1px solid;

        @include respond-to("medium") {
            padding: 8px;
        }

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }
    }

    &__location {
        display: flex;
        align-items: center;

        margin-left: 4px;

        .icon {
            margin-right: 4px;

            @include themed() {
                color: t("t-subdued");
            }
        }

        @include respond-to("medium") {
            margin-left: 8px;

            .icon {
                margin-right: 8px;
            }
        }

        @include themed() {
            color: t("t-tint");
        }
    }

    &__name {
        @include themed() {
            color: t("t-bright");
        }
    }
}
</style>
