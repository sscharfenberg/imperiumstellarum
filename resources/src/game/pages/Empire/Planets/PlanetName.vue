<script>
/******************************************************************************
 * PageComponent: PlanetName
 *****************************************************************************/
import { convertLatinToRoman } from "@/game/helpers/format";
export default {
    name: "PlanetName",
    props: {
        starName: String,
        index: Number,
    },
    setup() {
        return { convertLatinToRoman };
    },
};
</script>

<template>
    <div class="planet__name planet__item">
        <span>{{ starName }}</span>
        &nbsp;- {{ convertLatinToRoman(index) }}
    </div>
</template>

<style lang="scss" scoped>
.planet__name {
    display: flex;
    align-items: center;

    height: 40px;
    padding: 5px 10px;
    border: 2px solid transparent;
    margin-bottom: 5px;

    @include themed() {
        background-color: rgba(t("g-deep"), 0.7);
        color: t("t-light");
        border-color: t("g-abbey");
    }

    > span {
        display: inline-block;

        overflow: hidden;
        max-width: 25vw;

        white-space: nowrap;
        text-overflow: ellipsis;
    }
}
</style>
