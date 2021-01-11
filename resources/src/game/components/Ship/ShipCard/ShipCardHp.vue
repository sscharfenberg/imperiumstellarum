<script>
/******************************************************************************
 * Component: ShipCardHp
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
import { formatInt } from "@/game/helpers/format";
export default {
    name: "ShipCardHp",
    props: {
        armour: Number,
        shields: Number,
        structure: Number,
    },
    components: { Icon },
    setup() {
        return { formatInt };
    },
};
</script>

<template>
    <ul class="ship__hp-numbers">
        <li><icon name="tech-shields" />{{ formatInt(shields) }}</li>
        <li class="seperator"></li>
        <li><icon name="tech-armour" />{{ formatInt(armour) }}</li>
        <li class="seperator"></li>
        <li><icon name="tech-structure" />{{ formatInt(structure) }}</li>
    </ul>
</template>

<style lang="scss" scoped>
.ship__hp-numbers {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 4px 6px;
    margin: 0;

    list-style: none;

    @include respond-to("medium") {
        padding: 6px 8px;
    }

    > li {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }

    .seperator {
        width: 2px;
        height: 100%;

        @include themed() {
            background-color: t("g-deep");
        }
    }
}
</style>
