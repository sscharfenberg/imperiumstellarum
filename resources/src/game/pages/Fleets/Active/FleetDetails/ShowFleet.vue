<script>
/******************************************************************************
 * PageComponent: ShowFleet
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowFleetMetaActions from "./Meta/ShowFleetMetaActions";
import ShowFleetMetaStatus from "./Meta/ShowFleetMetaStatus";
import ShipCard from "Components/Ship/ShipCard/ShipCard";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: { ShowFleetMetaActions, ShowFleetMetaStatus, ShipCard },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const ships = computed(() =>
            store.getters["fleets/shipsByFleetId"](props.fleetId)
        );
        return {
            fleet,
            ships,
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
            <div class="ships__title">1st Fleet ships</div>
            <div class="ships__list">
                <ship-card
                    v-for="ship in ships"
                    :key="ship.id"
                    :ship-id="ship.id"
                    :hull-type="ship.hullType"
                    :acceleration="ship.acceleration"
                    :name="ship.name"
                    :class-name="ship.className"
                    :shields-current="ship.hp.shields.current"
                    :shields-max="ship.hp.shields.max"
                    :armour-current="ship.hp.armour.current"
                    :armour-max="ship.hp.armour.max"
                    :structure-current="ship.hp.structure.current"
                    :structure-max="ship.hp.structure.max"
                />
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
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));

        padding: 8px;
        border: 1px solid transparent;
        grid-gap: 8px;

        @include themed() {
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 16px;
            grid-gap: 16px;
        }
    }
}
</style>
