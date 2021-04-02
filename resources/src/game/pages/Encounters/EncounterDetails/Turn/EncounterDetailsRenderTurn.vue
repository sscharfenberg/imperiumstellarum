<script>
/******************************************************************************
 * Page: EncounterDetailsRenderTurn
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import EncounterDetailsRenderFleet from "Pages/Encounters/EncounterDetails/Turn/EncounterDetailsRenderFleet";
export default {
    name: "EncounterDetailsRenderTurn",
    props: {
        turn: Number,
    },
    components: { EncounterDetailsRenderFleet },
    setup(props) {
        const store = useStore();
        const turnData = computed(() =>
            store.getters["encounters/getTurnByNumber"](props.turn)
        );
        const defender = computed(() => {
            const data = turnData.value.defender;
            return data.sort((a, b) => a.row - b.row);
        });
        const attacker = computed(() => {
            const data = turnData.value.attacker;
            return data.sort((a, b) => a.row - b.row);
        });
        return { turnData, defender, attacker };
    },
};
</script>

<template>
    <h1>Turn {{ turn }}</h1>
    <ul class="arena">
        <li>Attacker</li>
        <encounter-details-render-fleet
            v-for="fleet in attacker"
            :key="fleet.fleetId"
            :fleet-id="fleet.fleetId"
            :name="fleet.name"
            :row="fleet.row"
            :col="fleet.col"
            :ships="fleet.ships"
        />
        <li>Defender</li>
        <encounter-details-render-fleet
            v-for="fleet in defender"
            :key="fleet.fleetId"
            :fleet-id="fleet.fleetId"
            :name="fleet.name"
            :row="fleet.row"
            :col="fleet.col"
            :ships="fleet.ships"
        />
    </ul>
</template>

<style lang="scss" scoped>
.arena {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
