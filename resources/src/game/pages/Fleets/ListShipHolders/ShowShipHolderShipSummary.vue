<script>
/******************************************************************************
 * PageComponent: ShowShipHolderShipSummary
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowShipHolderShipSummary",
    props: {
        holderId: String,
    },
    components: {
        Icon,
    },
    setup(props) {
        const store = useStore();
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
        return { hullTypes, ships };
    },
};
</script>

<template>
    <ul class="topic-ships">
        <li
            class="topic-ships__ship"
            v-for="hullType in hullTypes"
            :key="hullType"
        >
            {{ ships.filter((s) => s.hullType === hullType).length }}
            <icon :name="`hull-${hullType}`" />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.topic-ships {
    display: none;

    padding: 0;
    margin: 0;

    list-style: none;

    @include respond-to("medium") {
        display: flex;
        align-items: center;

        margin-left: 16px;
    }

    &__ship {
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
