<script>
/******************************************************************************
 * PageComponent: ShowShipHolder
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShipCard from "Components/Ship/ShipCard/ShipCard";
import ShipCardShort from "Components/Ship/ShipCardShort/ShipCardShort";
import ShowFleetMetaActions from "./Meta/ShowFleetMetaActions";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "ShowShipHolder",
    props: {
        holderId: String,
    },
    components: {
        ShipCard,
        ShipCardShort,
        ShowFleetMetaActions,
        SubHeadline,
    },
    setup(props) {
        const store = useStore();
        const shipView = computed(() => store.state.fleets.shipView);
        const holder = computed(() => {
            const fleet = store.getters["fleets/fleetById"](props.holderId);
            const shipyard = store.getters["fleets/shipyardById"](
                props.holderId
            );
            return fleet && fleet.id ? fleet : shipyard;
        });
        const ships = computed(() => {
            const fleetShips = store.getters["fleets/shipsByFleetId"](
                props.holderId
            );
            const shipyardShips = store.getters["fleets/shipsByShipyardId"](
                props.holderId
            );
            return fleetShips.length ? fleetShips : shipyardShips;
        });
        const hullTypes = computed(() => {
            // prepare array of preferred sort order
            const order = Object.keys(window.rules.ships.hullTypes);
            return (
                ships.value
                    .map((b) => b.hullType) // pass an array that only contains hullTypes
                    // check if it is the first index, so we pass only uniques.
                    .filter(
                        (value, index, self) => self.indexOf(value) === index
                    )
                    // sort hullTypes according to our preferred sort order (ascending)
                    .sort((a, b) => {
                        return order.indexOf(a) - order.indexOf(b);
                    })
                    .reverse()
            );
        });
        return {
            shipView,
            holder,
            ships,
            hullTypes,
        };
    },
};
</script>

<template>
    <div class="fleet">
        <div class="fleet-meta">
            <show-fleet-meta-actions :holder-id="holderId" />
        </div>
        <div class="ships">
            <div class="ships__title">
                {{
                    holder.planetName
                        ? $t("fleets.active.shipyardTitle")
                        : $t("fleets.active.fleetTitle")
                }}
            </div>
            <div class="ships__list">
                <div v-for="hullType in hullTypes" :key="hullType">
                    <sub-headline
                        :headline="
                            $tc(
                                'fleets.summary.hulls.' + hullType,
                                ships.filter((s) => s.hullType === hullType)
                                    .length
                            )
                        "
                    >
                        {{
                            ships.filter((s) => s.hullType === hullType).length
                        }}
                    </sub-headline>
                    <div
                        v-if="shipView === 0"
                        class="ships__list-types--detailed"
                    >
                        <ship-card
                            v-for="ship in ships.filter(
                                (s) => s.hullType === hullType
                            )"
                            :key="ship.id"
                            :ship-id="ship.id"
                            :hull-type="ship.hullType"
                            :name="ship.name"
                            :class-name="ship.className"
                            :shields-current="ship.hp.shields.current"
                            :shields-max="ship.hp.shields.max"
                            :armour-current="ship.hp.armour.current"
                            :armour-max="ship.hp.armour.max"
                            :structure-current="ship.hp.structure.current"
                            :structure-max="ship.hp.structure.max"
                            :dmg-laser="ship.dmg.laser"
                            :dmg-plasma="ship.dmg.plasma"
                            :dmg-missile="ship.dmg.missile"
                            :dmg-railgun="ship.dmg.railgun"
                            :ftl="ship.ftl"
                            :acceleration="ship.acceleration"
                            :colony="ship.colony"
                        />
                    </div>
                    <div
                        v-if="shipView === 1"
                        class="ships__list-types--collapsed"
                    >
                        <ship-card-short
                            v-for="ship in ships.filter(
                                (s) => s.hullType === hullType
                            )"
                            :key="ship.id"
                            :ship-id="ship.id"
                            :hull-type="ship.hullType"
                            :name="ship.name"
                            :shields-current="ship.hp.shields.current"
                            :shields-max="ship.hp.shields.max"
                            :armour-current="ship.hp.armour.current"
                            :armour-max="ship.hp.armour.max"
                            :structure-current="ship.hp.structure.current"
                            :structure-max="ship.hp.structure.max"
                        />
                    </div>
                </div>
                <div class="empty" v-if="!ships.length && !holder.planetName">
                    {{ $t("fleets.active.emptyFleet") }}
                </div>
                <div class="empty" v-if="!ships.length && holder.planetName">
                    {{ $t("fleets.active.emptyShipyard") }}
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.fleet-meta {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;

    @include respond-to("medium") {
        flex-direction: row;
    }
}
.ships {
    &__title {
        display: inline-block;

        padding: 4px 8px;
        border: 1px solid transparent;
        border-bottom: 0;

        @include themed() {
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 8px 16px;
        }
    }

    &__list {
        padding: 8px 8px 0 8px;
        border: 1px solid transparent;

        @include themed() {
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 16px 16px 0 16px;
        }

        .empty {
            margin-bottom: 8px;

            @include respond-to("medium") {
                margin-bottom: 16px;
            }
        }
    }

    &__list-types {
        // detailed ship view
        &--detailed {
            display: flex;
            flex-wrap: wrap;

            @include respond-to("small") {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            }

            .ship {
                width: 100%;
                margin: 0 0 8px 0;

                @include respond-to("medium") {
                    width: calc(50% - 8px);
                    margin: 0 16px 16px 0;

                    &:nth-of-type(2n) {
                        margin-right: 0;
                    }
                }
            }
        }

        // collapsed ship view
        &--collapsed {
            display: grid;
            grid-template-columns: 1fr;

            padding: 0 0 16px 0;
            grid-gap: 2px;

            @include respond-to("medium") {
                grid-template-columns: calc(50% - 2px) calc(50% - 2px);

                grid-gap: 4px;
            }
        }
    }
}
</style>
