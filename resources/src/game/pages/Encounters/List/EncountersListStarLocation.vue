<script>
/******************************************************************************
 * PageComponent: EncountersListStarLocation
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "EncountersListStarLocation",
    props: {
        coordX: Number,
        coordY: Number,
    },
    components: { Icon },
};
</script>

<template>
    <span class="star__location encounter-item">
        <icon name="location" />
        {{ coordX }}/{{ coordY }}
    </span>
</template>

<style lang="scss" scoped>
.star__location {
    display: flex;
    align-items: center;

    @include respond-to("medium") {
        margin-right: auto;
    }

    .icon {
        margin-right: 4px;

        @include themed() {
            color: t("t-subdued");
        }

        @include respond-to("medium") {
            margin-right: 8px;
        }
    }
}
</style>
