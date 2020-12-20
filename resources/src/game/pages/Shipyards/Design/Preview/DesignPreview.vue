<script>
/******************************************************************************
 * PageComponent: DesignPreview
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShipClassCard from "Components/Ship/ShipClassCard/ShipClassCard";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "DesignPreview",
    components: { SubHeadline, ShipClassCard },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const className = computed(
            () => store.state.shipyards.design.className
        );
        const modules = computed(() => store.state.shipyards.design.modules);
        const tls = computed(() => store.state.shipyards.techLevels);
        return {
            hullType,
            className,
            modules,
            tls,
        };
    },
};
</script>

<template>
    <div class="preview">
        <sub-headline :headline="$t('shipyards.design.preview.headline')" />
        <ship-class-card
            :class-name="className"
            :hull-type="hullType"
            :modules="modules"
            :tls="tls"
        />
    </div>
</template>
