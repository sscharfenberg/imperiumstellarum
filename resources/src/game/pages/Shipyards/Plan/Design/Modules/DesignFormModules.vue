<script>
/******************************************************************************
 * PageComponent: DesignFormModules
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import ModulesAdded from "./ModulesAdded";
import ModulesAvailable from "./ModulesAvailable";
export default {
    name: "DesignFormModules",
    components: { SubHeadline, ModulesAdded, ModulesAvailable },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        return {
            hullType,
        };
    },
};
</script>

<template>
    <sub-headline
        v-if="hullType.length"
        :headline="`#2 ${$t('shipyards.design.modules.headline')}`"
    />
    <div class="module-stage" v-if="hullType.length">
        <header class="module-stage__header">
            {{ $t("shipyards.design.modules.available") }}
        </header>
        <header class="module-stage__header">
            {{ $t("shipyards.design.modules.added") }}
        </header>
        <modules-available :hull-type="hullType" />
        <modules-added :hull-type="hullType" />
    </div>
</template>

<style lang="scss" scoped>
.module-stage {
    display: grid;
    grid-template-columns: 1fr 1fr;

    margin-bottom: 8px;
    grid-gap: 8px;

    @include respond-to("medium") {
        margin-bottom: 16px;
        grid-gap: 16px;
    }

    &__header {
        display: flex;
        align-items: center;

        @include themed() {
            color: t("t-tint");
        }

        &::after,
        &::before {
            display: block;

            height: 1px;
            flex-grow: 1;

            content: " ";

            @include themed() {
                background-color: t("g-deep");
            }
        }

        &::after {
            margin-left: 8px;

            @include respond-to("medium") {
                margin-left: 16px;
            }
        }

        &::before {
            margin-right: 8px;

            @include respond-to("medium") {
                margin-right: 16px;
            }
        }
    }

    .column {
        padding: 4px 4px 0 4px;
        border: 2px solid transparent;

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 16px 16px 8px 16px;
        }
    }
}
</style>
