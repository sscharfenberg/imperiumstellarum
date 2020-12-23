<script>
/******************************************************************************
 * PageComponent: ManagePreview
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShipClassCard from "Components/Ship/ShipClassCard/ShipClassCard";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import GameButton from "Components/Button/GameButton";
export default {
    name: "ManagePreview",
    components: { SubHeadline, ShipClassCard, GameButton },
    //components: { SubHeadline },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.preview.hullType);
        const className = computed(
            () => store.state.shipyards.preview.className
        );
        const modules = computed(() => store.state.shipyards.preview.modules);
        const tls = computed(() => {
            const tls = [];
            const objectTls = store.state.shipyards.preview.techLevels;
            for (const [type, level] of Object.entries(objectTls)) {
                tls.push({ type, level });
            }
            return tls;
        });
        const onCancel = () => {
            console.log("clicked on cancel!");
            store.commit("shipyards/RESET_MANAGE_BLUEPRINT_PREVIEW");
        };
        return {
            hullType,
            className,
            modules,
            tls,
            onCancel,
        };
    },
};
</script>

<template>
    <div class="preview">
        <sub-headline :headline="$t('shipyards.design.preview.headline')">
            <game-button icon-name="cancel" @click="onCancel" />
        </sub-headline>
        <ship-class-card
            :class-name="className"
            :hull-type="hullType"
            :modules="modules"
            :tls="tls"
        />
    </div>
</template>
