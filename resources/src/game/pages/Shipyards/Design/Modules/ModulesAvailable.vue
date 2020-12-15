<script>
/******************************************************************************
 * PageComponent: ModulesAvailable
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import RenderModule from "./RenderModule";
export default {
    name: "ModulesAvailable",
    props: {
        hullType: String,
    },
    components: { RenderModule },
    setup(props) {
        const store = useStore();
        const tl = computed(() => store.state.shipyards.techLevels);
        const availableModules = computed(() =>
            window.rules.modules.filter(
                (mod) => mod.hullType === props.hullType
            )
        );
        const availableShipSlots = computed(
            () =>
                window.rules.ships.hullTypes[props.hullType].slots -
                store.state.shipyards.design.modules.length
        );
        const offensiveModules = computed(() =>
            availableModules.value.filter(
                (mod) => mod.moduleType === "offensive"
            )
        );
        const defensiveModules = computed(() =>
            availableModules.value.filter(
                (mod) => mod.moduleType === "defensive"
            )
        );
        const engineeringModules = computed(() =>
            availableModules.value.filter(
                (mod) => mod.moduleType === "engineering"
            )
        );
        const onClick = (techType) => {
            store.commit("shipyards/ADD_MODULE", techType);
        };
        return {
            offensiveModules,
            defensiveModules,
            engineeringModules,
            availableShipSlots,
            tl,
            onClick,
        };
    },
};
</script>

<template>
    <div class="available column">
        <header class="modules__type" v-if="offensiveModules.length">
            {{ $t("shipyards.design.modules.offensive") }}
        </header>
        <div class="modules" v-if="offensiveModules.length">
            <render-module
                v-for="module in offensiveModules"
                :key="`offensive${module.stub}`"
                :stub="module.stub"
                :tech-type="module.techType"
                :tl="tl.find((tech) => tech.type === module.techType).level"
                :disabled="availableShipSlots === 0"
                @add-module="onClick(module.techType)"
            />
        </div>
        <header class="modules__type" v-if="defensiveModules.length">
            {{ $t("shipyards.design.modules.defensive") }}
        </header>
        <div class="modules" v-if="defensiveModules.length">
            <render-module
                v-for="module in defensiveModules"
                :key="`offensive${module.stub}`"
                :stub="module.stub"
                :tech-type="module.techType"
                :tl="tl.find((tech) => tech.type === module.techType).level"
                :disabled="availableShipSlots === 0"
                @add-module="onClick(module.techType)"
            />
        </div>
        <header class="modules__type" v-if="engineeringModules.length">
            {{ $t("shipyards.design.modules.engineering") }}
        </header>
        <div class="modules" v-if="engineeringModules.length">
            <render-module
                v-for="module in engineeringModules"
                :key="`offensive${module.stub}`"
                :stub="module.stub"
                :tech-type="module.techType"
                :disabled="availableShipSlots === 0"
                @add-module="onClick(module.techType)"
            />
        </div>
    </div>
</template>

<style lang="scss" scoped>
.modules {
    display: flex;
    flex-direction: column;

    &__type {
        padding: 8px;
        border: 1px solid transparent;
        margin-bottom: 8px;
        clip-path: polygon(
            0 0,
            calc(100% - 10px) 0,
            100% 10px,
            100% 100%,
            10px 100%,
            0 calc(100% - 10px)
        );

        @include themed() {
            background-color: t("g-deep");
        }
    }
}
</style>
