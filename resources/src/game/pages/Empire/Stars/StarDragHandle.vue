<script>
/******************************************************************************
 * PageComponent: StarDragHandle
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "StarDragHandle",
    components: {
        Icon,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="handle">
        <icon name="drag" :size="2" />
    </div>
</template>

<style lang="scss" scoped>
.handle {
    display: flex;
    align-items: center;

    height: 48px;
    padding: 0 4px;
    margin: 0 4px 0 0;

    cursor: move;

    @include themed() {
        color: t("g-raven");
    }

    @include respond-to("medium") {
        padding: 0 8px;
        margin-right: 8px;
    }
}
</style>
