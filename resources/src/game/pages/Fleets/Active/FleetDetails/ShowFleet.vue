<script>
/******************************************************************************
 * PageComponent: ShowFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowFleetMetaActions from "./Meta/ShowFleetMetaActions";
import ShowFleetMetaStatus from "./Meta/ShowFleetMetaStatus";
import ShipCard from "Components/Ship/ShipCard/ShipCard";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: {
        ShowFleetMetaActions,
        ShowFleetMetaStatus,
        ShipCard,
        SubHeadline,
    },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const ships = computed(() =>
            store.getters["fleets/shipsByFleetId"](props.fleetId)
        );
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
            fleet,
            ships,
            hullTypes,
        };
    },
};
</script>

<template>
    <div class="fleet">
        <div class="fleet-meta">
            <show-fleet-meta-status :fleet-id="fleetId" />
            <show-fleet-meta-actions :fleet-id="fleetId" />
        </div>
        <div class="ships">
            <div class="ships__title">
                {{ $t("fleets.active.fleetTitle") }}
            </div>
            <div class="ships__list">
                <div v-for="hullType in hullTypes" :key="hullType">
                    <sub-headline
                        :headline="$t('fleets.active.hulls.' + hullType)"
                    >
                        {{
                            ships.filter((s) => s.hullType === hullType).length
                        }}
                    </sub-headline>
                    <div class="ships__list-types">
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
                </div>
                <div class="empty" v-if="!ships.length">
                    {{ $t("fleets.active.empty") }}
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
}
</style>
