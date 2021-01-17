<script>
/******************************************************************************
 * PageComponent: FleetTransferShipGrid
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import FleetTransferShip from "./FleetTransferShip";
export default {
    name: "FleetTransferShipGrid",
    props: {
        itemId: String,
        shipIds: Array,
        columnIndex: Number, // 0..1, left or right.
    },
    components: { FleetTransferShip },
    setup(props) {
        const store = useStore();
        const activatedOnce = ref(false);

        /**
         * @function transfer the ship to the other column
         * @param id
         */
        const onShipClick = (id) => {
            if (!activatedOnce.value)
                store.commit("fleets/SET_TRANSFER_SUBMIT_ACTIVE", true);
            activatedOnce.value = true;
            if (props.columnIndex === 0) {
                store.commit("fleets/TRANSFER_SOURCE_TO_TARGET", id);
            } else {
                store.commit("fleets/TRANSFER_TARGET_TO_SOURCE", id);
            }
        };

        /**
         * @function sort shipId by hullType and name
         * @type {ComputedRef<[]>}
         * @returns {Array} of shipIds
         */
        const shipIdsSorted = computed(() => {
            // base order of hullTypes; reversed so biggest ships are at the top.
            const order = Object.keys(window.rules.ships.hullTypes).reverse();
            // create and array of unsorted ships with hullType and name from id
            const ships = props.shipIds.map((s) => {
                const singleShip = store.getters["fleets/shipById"](s);
                return {
                    id: s,
                    hullType: singleShip.hullType,
                    name: singleShip.name,
                };
            });
            let sortedShips = [];
            // loop hullTypes in order
            order.forEach((ht) => {
                let htShips = ships.filter((s) => s.hullType === ht);
                if (ht.length) {
                    // if there are ships with this hullType
                    htShips = htShips
                        // sort them alphanumerically by name.
                        .sort((a, b) =>
                            a.name.localeCompare(b.name, "en", {
                                numeric: true,
                            })
                        )
                        // and map back to shipIds
                        .map((s) => s.id);
                    // concat them to sortedShips
                    sortedShips = [...sortedShips, ...htShips];
                }
            });
            return sortedShips;
        });

        return { onShipClick, shipIdsSorted };
    },
};
</script>

<template>
    <li
        class="fleet-transfer__grid-item"
        :class="{ left: columnIndex === 0, right: columnIndex === 1 }"
    >
        <transition-group name="shiptransfer">
            <fleet-transfer-ship
                v-for="id in shipIdsSorted"
                :key="id"
                :ship-id="id"
                :column-index="columnIndex"
                @click="onShipClick(id)"
            />
        </transition-group>
    </li>
</template>

<style lang="scss" scoped>
.left {
    .shiptransfer-enter-active,
    .shiptransfer-leave-active {
        transition: all 150ms ease;
    }
    .shiptransfer-enter-from,
    .shiptransfer-leave-to {
        opacity: 0;

        transform: translateX(200px);
    }
}

.right {
    .shiptransfer-enter-active,
    .shiptransfer-leave-active {
        transition: all 150ms ease;
    }
    .shiptransfer-enter-from,
    .shiptransfer-leave-to {
        opacity: 0;

        transform: translateX(-200px);
    }
}
</style>
