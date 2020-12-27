<script>
/******************************************************************************
 * PageComponent: ConstructIndex
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import NewContract from "./NewContract/NewContract";
export default {
    name: "ConstructIndex",
    components: { NewContract },
    setup() {
        const store = useStore();
        const shipyards = computed(() => store.state.shipyards.shipyards);
        return {
            shipyards,
        };
    },
};
</script>

<template>
    <new-contract v-if="shipyards" />
    <div v-if="!shipyards">
        <p>{{ $t("shipyards.none") }}</p>
        <p>
            <router-link class="text-link" :to="{ name: 'Empire' }">{{
                $t("empire.title")
            }}</router-link>
        </p>
    </div>
</template>
