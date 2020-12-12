<script>
/******************************************************************************
 * PageComponent: DesignIndex
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "DesignIndex",
    components: { AreaSection },
    setup() {
        const store = useStore();
        const shipTypes = computed(() => {
            console.log(window.rules.shipyards);
            return store.state.shipyards.shipyards;
        });
        return {
            shipTypes,
        };
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.design.title')">
        {{ shipTypes }}
    </area-section>
</template>
