<script>
/******************************************************************************
 * PageComponent: FleetShipSummary
 *****************************************************************************/
import { computed } from "vue";
import { getFleetHullTypes } from "@/game/helpers/fleet";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetShipSummary",
    props: {
        ships: Array, // array of fleet ships
        reverse: {
            type: Boolean,
            default: true,
        },
    },
    components: {
        Icon,
    },
    setup(props) {
        const hullTypes = computed(() =>
            getFleetHullTypes(props.ships, window.rules, props.reverse)
        );
        return { hullTypes };
    },
};
</script>

<template>
    <ul class="summary">
        <li
            class="summary__type"
            v-for="hullType in hullTypes"
            :key="hullType"
            :title="
                $tc(
                    'fleets.summary.hulls.' + hullType,
                    ships.filter((s) => s.hullType === hullType).length,
                    {
                        num: ships.filter((s) => s.hullType === hullType)
                            .length,
                    }
                )
            "
            :aria-label="
                $tc(
                    'fleets.summary.hulls.' + hullType,
                    ships.filter((s) => s.hullType === hullType).length,
                    {
                        num: ships.filter((s) => s.hullType === hullType)
                            .length,
                    }
                )
            "
        >
            {{ ships.filter((s) => s.hullType === hullType).length }}
            <icon :name="`hull-${hullType}`" />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.summary {
    display: flex;
    align-items: center;

    padding: 0;
    margin: 0;

    list-style: none;

    &__type {
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
</style>
