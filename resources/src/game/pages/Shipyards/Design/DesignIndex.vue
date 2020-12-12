<script>
/******************************************************************************
 * PageComponent: DesignIndex
 *****************************************************************************/
import AreaSection from "Components/AreaSection/AreaSection";
import DesignPreview from "./DesignPreview";
import DesignFormHullType from "./DesignFormHullType";
import DesignFormClassName from "./DesignFormClassName";
export default {
    name: "DesignIndex",
    components: {
        AreaSection,
        DesignFormHullType,
        DesignFormClassName,
        DesignPreview,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <area-section :headline="$t('shipyards.design.title')">
        <div class="design-section">
            <div class="form">
                <design-form-hull-type />
                <design-form-class-name />
            </div>
            <design-preview />
        </div>
    </area-section>
</template>

<style lang="scss" scoped>
.design-section {
    display: grid;

    grid-gap: 8px;

    @include respond-to("medium") {
        grid-template-columns: 2fr 1fr;

        grid-gap: 16px;
    }
}
.form,
.preview {
    padding: 8px;

    @include respond-to("medium") {
        padding: 16px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }
}

.preview {
    order: -1;

    @include respond-to("medium") {
        order: 0;
    }
}
</style>
