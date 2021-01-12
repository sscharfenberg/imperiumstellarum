<script>
/******************************************************************************
 * Component: ShipCard
 *****************************************************************************/
import ShipCardHpRadial from "./ShipCardHpRadial";
import ShipCardHp from "./ShipCardHp";
import ShipCardDamage from "./ShipCardDamage";
import ShipCardEngineering from "./ShipCardEngineering";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipCard",
    props: {
        shipId: String,
        name: String,
        className: String,
        hullType: String,
        shieldsCurrent: Number,
        shieldsMax: Number,
        armourCurrent: Number,
        armourMax: Number,
        structureCurrent: Number,
        structureMax: Number,
        dmgPlasma: Number,
        dmgMissile: Number,
        dmgRailgun: Number,
        dmgLaser: Number,
        ftl: Boolean,
        colony: Boolean,
        acceleration: Number,
    },
    components: {
        ShipCardHpRadial,
        Icon,
        ShipCardHp,
        ShipCardDamage,
        ShipCardEngineering,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="ship">
        <div class="ship__hp">
            <ship-card-hp-radial
                :pct-shields="(shieldsCurrent / shieldsMax) * 100"
                :pct-armour="(armourCurrent / armourMax) * 100"
                :pct-structure="(structureCurrent / structureMax) * 100"
            />
        </div>
        <div class="ship__stats">
            <header class="ship__name">
                <icon :name="`hull-${hullType}`" />
                {{ name }}
            </header>
            <aside class="ship__class">
                {{
                    $t("fleets.ship.class", {
                        type: $t("shipyards.hulls." + hullType),
                        class: className,
                    })
                }}
            </aside>
            <ul class="ship__stats-list">
                <ship-card-hp
                    :shields-current="shieldsCurrent"
                    :shields-max="shieldsMax"
                    :armour-current="armourCurrent"
                    :armour-max="armourMax"
                    :structure-current="structureCurrent"
                    :structure-max="structureMax"
                />
                <ship-card-damage
                    :laser="dmgLaser"
                    :plasma="dmgPlasma"
                    :missile="dmgMissile"
                    :railgun="dmgRailgun"
                    :hull-type="hullType"
                />
                <ship-card-engineering
                    :ftl="ftl"
                    :acceleration="acceleration"
                    :colony="colony"
                />
            </ul>
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
        padding: 4px;
        flex: 0 0 35%;

        @include respond-to("medium") {
            padding: 8px;
        }
    }

    &__stats {
        display: flex;
        flex-direction: column;

        flex: 0 0 65%;
    }

    &__name {
        display: flex;
        align-items: center;

        padding: 4px 6px 4px 8px;
        margin-left: auto;

        clip-path: polygon(
            0 0,
            100% 0,
            100% 100%,
            10px 100%,
            0 calc(100% - 10px)
        );

        @include themed() {
            background-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 6px 8px 6px 12px;
        }

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }

    &__class {
        padding: 4px 6px;
        margin-left: auto;

        @include respond-to("medium") {
            padding: 6px 8px;
        }
    }
}
</style>

<style lang="scss">
.ship__stats-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));

    padding: 4px 6px;
    margin: 0;
    grid-gap: 2px;

    list-style: none;

    @include respond-to("medium") {
        padding: 6px 8px;
    }

    > li {
        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
        padding: 4px;
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-sunken");
            border-color: t("g-deep");
        }

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }
}
</style>
