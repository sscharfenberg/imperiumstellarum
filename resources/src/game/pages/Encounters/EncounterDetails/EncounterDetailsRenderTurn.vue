<script>
/******************************************************************************
 * Page: EncounterDetailsRenderTurn
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "EncounterDetails",
    props: {
        turn: Number,
    },
    components: {},
    setup(props) {
        const store = useStore();
        const turnData = computed(() =>
            store.getters["encounters/getTurnByNumber"](props.turn)
        );
        return { turnData };
    },
};
</script>

<template>
    <h1>Turn {{ turn }}</h1>
    {{ turnData }}
    <ul>
        <li v-for="fleet in turnData.attacker" :key="fleet.fleetId">
            ATTACKER {{ fleet.name }} {{ fleet.ships }} row{{ fleet.row }} col{{
                fleet.col
            }}
        </li>
        <li v-for="fleet in turnData.defender" :key="fleet.fleetId">
            DEFENDER {{ fleet.name }} {{ fleet.ships }} row{{ fleet.row }} col{{
                fleet.col
            }}
        </li>
    </ul>
</template>
