<script>
/******************************************************************************
 * PageComponent: ShowFleetRange
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowFleetRange",
    props: {
        range: Number,
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="range" :title="$t('fleets.active.range', { num: range })">
        <icon name="range" />
        {{ range }}
    </aside>
</template>

<style lang="scss" scoped>
.range {
    display: flex;
    align-items: center;

    padding: 5px;
    margin: 0 8px 0 0;

    line-height: 1;

    @include themed() {
        background: t("g-bunker");
        color: t("t-subdued");
    }

    .icon {
        display: none;

        margin-right: 4px;

        @include themed() {
            color: darken(t("t-subdued"), 25%);
        }

        @include respond-to("medium") {
            display: block;
        }
    }
}
</style>
