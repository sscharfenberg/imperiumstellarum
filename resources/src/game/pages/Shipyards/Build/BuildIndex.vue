<script>
/******************************************************************************
 * PageComponent: BuildIndex
 *****************************************************************************/
import AreaSection from "Components/AreaSection/AreaSection";
export default {
    name: "BuildIndex",
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
