<script>
/******************************************************************************
 * PageComponent: FleetTransferShipGrid
 *****************************************************************************/
//import { useStore } from "vuex";
//import { computed } from "vue";
export default {
    name: "FleetTransferShipGrid",
    props: {
        itemId: String,
        shipIds: Array,
    },
    setup() {
        //const store = useStore();
        return {};
    },
};
</script>

<template>
    <li class="fleet-transfer__grid-item">
        passed itemId: {{ itemId }}<br />
        ShipIds: {{ shipIds }}
    </li>
</template>

<style lang="scss" scoped></style>
