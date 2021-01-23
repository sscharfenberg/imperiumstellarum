<script>
/******************************************************************************
 * PageComponent: FleetMoveSummary
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { getFleetHullTypes } from "@/game/helpers/fleet";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetMoveSummary",
    props: {
        fleetId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const ships = computed(() =>
            store.getters["fleets/shipsByFleetId"](props.fleetId)
        );
        const hullTypes = computed(() =>
            getFleetHullTypes(ships.value, window.rules, true)
        );
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const startLocation = computed(() =>
            store.getters["fleets/starById"](fleet.value.starId)
        );
        const owner = computed(() =>
            store.getters["fleets/playerById"](startLocation.value.ownerId)
        );
        return { hullTypes, ships, startLocation, owner };
    },
};
</script>

<template>
    <ul class="stats">
        <li class="text-left">{{ $t("fleets.move.summaryLabel") }}</li>
        <li class="text-left">
            <ul class="ships-summary">
                <li
                    class="ships-summary__hull-type"
                    v-for="hullType in hullTypes"
                    :key="hullType"
                >
                    {{ ships.filter((s) => s.hullType === hullType).length }}
                    <icon :name="`hull-${hullType}`" />
                </li>
            </ul>
        </li>
        <li class="text-left">{{ $t("fleets.move.startSystemLabel") }}</li>
        <li class="system">
            <span class="system__name">{{ startLocation.name }}</span>
            <span class="system__location">
                <icon name="location" :size="1" />
                {{ startLocation.x }}/{{ startLocation.y }}
            </span>
        </li>
        <li class="text-left">{{ $t("fleets.move.systemOwnerLabel") }}</li>
        <li class="text-left" v-if="owner.ticker">
            [{{ owner.ticker }}] {{ owner.name }}
        </li>
        <li class="text-left" v-if="!owner.ticker">
            {{ $t("starchart.star.ownerName.none") }}
        </li>
    </ul>
</template>

<style lang="scss">
.ships-summary {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;

    list-style: none;

    &__label {
        margin-right: 8px;

        @include respond-to("medium") {
            margin-right: 16px;
        }
    }

    &__hull-type {
        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 12px;

        line-height: 1;

        &:last-child {
            margin-right: 0;
        }

        .icon {
            margin-left: 8px;
        }
    }
}
.system {
    display: flex;
    align-items: center;
    justify-content: space-between;

    &__name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__location {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 4px;
        }
    }
}
</style>
