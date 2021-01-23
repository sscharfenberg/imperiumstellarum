<script>
/******************************************************************************
 * PageComponent: StarInfoModalMovingFleets
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import StarInfoModalFleets from "./StarInfoModalFleets";
export default {
    name: "StarInfoModalMovingFleets",
    props: {
        starId: String,
    },
    components: { StarInfoModalFleets },
    setup(props) {
        const store = useStore();
        const movements = computed(() =>
            store.state.starchart.fleetMovements.filter(
                (m) => m.destinationId === props.starId
            )
        );
        const fleetsMovingHere = computed(() =>
            movements.value.map((m) => {
                return store.getters["starchart/fleetById"](m.fleetId);
            })
        );
        return { fleetsMovingHere };
    },
};
</script>

<template>
    <li class="text-left" v-if="fleetsMovingHere.length">
        {{ $tc("starchart.star.fleetsMovingHere", fleetsMovingHere.length) }}
    </li>
    <li class="text-left" v-if="fleetsMovingHere.length">
        <star-info-modal-fleets :fleets="fleetsMovingHere" :star-id="starId" />
    </li>
</template>
