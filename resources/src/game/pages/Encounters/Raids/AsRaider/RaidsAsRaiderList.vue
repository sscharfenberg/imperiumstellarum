<script>
/******************************************************************************
 * Page: RaidsAsRaiderList.vue
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import Popover from "Components/Popover/Popover";
export default {
    name: "RaidsAsRaiderList",
    components: { AreaSection, Popover },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.encounters.requesting);
        const raids = computed(() => store.getters["encounters/raiderRaids"]);
        return { requesting, raids };
    },
};
</script>

<template>
    <area-section headline="Raids as Raider" :requesting="requesting">
        <template v-slot:aside>
            <popover align="right">
                {{ $t("encounters.list.explanation") }}
            </popover>
        </template>

        {{ raids }}
    </area-section>
</template>
