<script>
/******************************************************************************
 * PageComponent: StarMap
 *****************************************************************************/
export default {
    name: "StarMap",
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="map">
        <div class="map__wrapper"></div>
    </div>
</template>

<style lang="scss" scoped>
.map {
    position: relative;

    @include themed() {
        background-color: t("g-sunken");
    }

    &::before {
        display: block;

        padding-top: 100%;

        content: " ";
    }

    &__wrapper {
        display: flex;

        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }
}
</style>
