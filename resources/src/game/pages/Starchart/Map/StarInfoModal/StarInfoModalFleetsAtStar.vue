<script>
/******************************************************************************
 * PageComponent: StarInfoModalFleetsAtStar
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import StarInfoModalFleets from "./StarInfoModalFleets";
export default {
    name: "StarInfoModalFleetsAtStar",
    props: {
        starId: String,
    },
    components: { StarInfoModalFleets },
    setup(props) {
        const store = useStore();
        const fleets = computed(() => store.state.starchart.fleets);
        const fleetsAtStar = computed(() =>
            fleets.value.filter((f) => f.starId === props.starId)
        );
        return { fleetsAtStar };
    },
};
</script>

<template>
    <li class="text-left" v-if="fleetsAtStar.length">
        {{ $t("starchart.star.fleetsHereLabel") }}
    </li>
    <li class="text-left" v-if="fleetsAtStar.length">
        <star-info-modal-fleets :fleets="fleetsAtStar" />
    </li>
</template>
