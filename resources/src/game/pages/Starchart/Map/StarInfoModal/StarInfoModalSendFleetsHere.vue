<script>
/******************************************************************************
 * PageComponent: StarInfoModalSendFleetsHere
 *****************************************************************************/
//import { computed } from "vue";
//import { useStore } from "vuex";
export default {
    name: "StarInfoModalSendFleetsHere",
    props: {
        starId: String,
    },
    setup() {
        return {};
    },
};
</script>

<template>Fleets available to send here {{ starId }}</template>
