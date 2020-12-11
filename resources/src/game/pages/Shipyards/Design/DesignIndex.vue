<script>
/******************************************************************************
 * PageComponent: DesignIndex
 *****************************************************************************/
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "DesignIndex",
    components: { AreaSection },
    setup() {
        return {};
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.design.title')">
        fuddel
    </area-section>
</template>
