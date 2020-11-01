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

    height: 4.8rem;
    padding: 0 0.4rem;
    margin: 0 0.4rem 0 0;

    cursor: move;

    @include themed() {
        color: t("g-raven");
    }

    @include respond-to("medium") {
        padding: 0 0.8rem;
        margin-right: 0.8rem;
    }
}
</style>
