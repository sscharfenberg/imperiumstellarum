<script>
/******************************************************************************
 * PageComponent: ConstructIndex
 *****************************************************************************/
import AreaSection from "Components/AreaSection/AreaSection";
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ConstructIndex",
    components: { AreaSection },
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
    <area-section
        v-if="shipyards.length"
        :headline="$t('shipyards.build.title')"
    >
        fuddel
    </area-section>
    <div v-if="!shipyards.length">
        <p>{{ $t("shipyards.none") }}</p>
        <p>
            <router-link class="text-link" :to="{ name: 'Empire' }">{{
                $t("empire.title")
            }}</router-link>
        </p>
    </div>
</template>
