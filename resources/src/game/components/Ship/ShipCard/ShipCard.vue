<script>
/******************************************************************************
 * Component: ShipCard
 *****************************************************************************/
//import Icon from "Components/Icon/Icon";
import ShipCardHp from "./ShipCardHp";
export default {
    name: "ShipCard",
    props: {
        shipId: String,
        acceleration: Number,
        name: String,
        className: String,
        hullType: String,
    },
    components: { ShipCardHp },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="ship">
        <div class="ship__hp">
            <!--<icon :name="`hull-${hullType}`" />-->
            <ship-card-hp />
        </div>
        <div class="ship__stats">
            Ship {{ shipId }}<br />
            HullType {{ hullType }}<br />
            ACC {{ acceleration }}<br />
            Name {{ name }}<br />
            class name {{ className }}
        </div>
    </div>
</template>

<style lang="scss" scoped>
.ship {
    display: flex;

    border: 2px solid transparent;

    @include themed() {
        background-color: t("g-bunker");
        border-color: t("g-ebony");
    }

    &__hp {
        flex: 0 0 35%;

        @include themed() {
            //background-color: t("g-sunken");
            //border-color: t("g-ebony");
        }
    }

    &__stats {
        flex: 0 0 65%;
    }
}
</style>
