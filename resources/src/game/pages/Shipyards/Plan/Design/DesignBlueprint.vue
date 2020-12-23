<script>
/******************************************************************************
 * PageComponent: DesignBlueprint
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AreaSection from "Components/AreaSection/AreaSection";
import DesignPreview from "./Preview/DesignPreview";
import DesignFormHullType from "./DesignFormHullType";
import DesignFormClassName from "./DesignFormClassName";
import DesignFormModules from "./Modules/DesignFormModules";
import DesignFormSave from "./DesignFormSave";
export default {
    name: "DesignBlueprint",
    components: {
        AreaSection,
        DesignFormHullType,
        DesignFormClassName,
        DesignPreview,
        DesignFormModules,
        DesignFormSave,
    },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const shipyards = computed(() => store.state.shipyards.shipyards);
        const requesting = computed(() => store.state.shipyards.requesting);
        const bpMax = computed(() => store.state.shipyards.bpMax);
        const numBps = computed(() => store.state.shipyards.blueprints.length);
        return {
            bpMax,
            numBps,
            hullType,
            shipyards,
            requesting,
        };
    },
};
</script>

<template>
    <area-section
        v-if="shipyards.length && numBps < bpMax"
        :headline="`${$t('shipyards.design.title')} (${numBps}/${bpMax})`"
        :requesting="requesting"
    >
        <div class="design-section">
            <div class="form">
                <design-form-hull-type />
                <design-form-modules />
                <design-form-class-name />
                <design-form-save />
            </div>
            <design-preview v-if="hullType && hullType.length" />
        </div>
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

<style lang="scss" scoped>
.design-section {
    display: flex;
    flex-wrap: wrap;

    @include respond-to("medium") {
        flex-wrap: nowrap;
    }
}
.form,
.preview {
    padding: 8px;
    flex: 0 0 100%;

    @include respond-to("medium") {
        padding: 16px;
        flex: 1 0 calc(60% - 8px);
    }

    @include themed() {
        background-color: t("g-sunken");
    }
}

.preview {
    order: -1;

    margin-bottom: 8px;
    flex: 0 0 100%;

    @include respond-to("medium") {
        order: 0;

        margin: 0 0 0 16px;
        flex: 0 0 calc(40% - 8px);
    }
}
</style>
