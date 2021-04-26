<script>
/******************************************************************************
 * Component: DeadPlayer
 *****************************************************************************/
export default {
    name: "DeadPlayer",
    setup() {
        return {};
    },
};
</script>

<template>
    <aside class="dead">
        <h1>{{ $t("common.header.dead.headline") }}</h1>
        <p>{{ $t("common.header.dead.moody") }}</p>
    </aside>
</template>

<style lang="scss" scoped>
.dead {
    padding: 8px;
    margin: 0 16px 16px 16px;

    @include themed() {
        background-color: rgba(t("s-error"), 0.8);
        color: t("t-bright");
    }

    h1 {
        margin: 0 0 8px 0;

        @include themed() {
            text-shadow: 1px 1px 0 t("g-bunker"), -1px 1px 0 t("g-bunker"),
                -1px -1px 0 t("g-bunker"), 1px -1px 0 t("g-bunker");
        }
    }

    p {
        margin: 0 0 8px 0;

        &:last-child {
            margin: 0;
        }
    }
}
</style>
