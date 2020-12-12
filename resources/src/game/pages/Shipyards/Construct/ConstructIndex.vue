<script>
/******************************************************************************
 * PageComponent: ConstructIndex
 *****************************************************************************/
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "ConstructIndex",
    components: { AreaSection },
    setup() {
        return {};
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.build.title')">
        fuddel
    </area-section>
</template>
