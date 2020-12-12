<script>
/******************************************************************************
 * PageComponent: DesignPreview
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "DesignPreview",
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const className = computed(
            () => store.state.shipyards.design.className
        );
        return {
            hullType,
            className,
        };
    },
};
</script>

<template>
    <div class="preview">
        type: {{ hullType }}<br />
        className: {{ className }}<br />
    </div>
</template>
