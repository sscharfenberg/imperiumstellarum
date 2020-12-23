<script>
/******************************************************************************
 * Component: DamageType
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "DamageType",
    props: {
        type: String,
        dmg: Number,
        range: Number,
        useDivider: Boolean,
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <li class="label">{{ $t("shipyards.design.preview.dmg.type") }}</li>
    <li class="has-icon">
        <icon :name="`tech-${type}`" />
        {{ type }}
    </li>
    <li class="label">{{ $t("shipyards.design.preview.dmg.damage") }}</li>
    <li>{{ dmg }}</li>
    <li class="label">{{ $t("shipyards.design.preview.dmg.range") }}</li>
    <li>{{ range }}</li>
    <li class="divider" v-if="useDivider"></li>
</template>

<style lang="scss" scoped>
li {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 4px;
    border: 1px solid transparent;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        background-color: t("g-bunker");
        border-color: t("g-deep");
    }

    &.has-icon {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }

    &.divider {
        grid-column-start: span 2;

        height: 2px;
        padding: 0;
        border: 0;

        @include themed() {
            background: linear-gradient(to right, t("g-deep"), t("g-raven"));
        }
    }
}
</style>
