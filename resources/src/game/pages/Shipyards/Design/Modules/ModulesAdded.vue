<script>
/******************************************************************************
 * PageComponent: ModulesAdded
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import RenderModule from "./RenderModule";
export default {
    name: "ModulesAdded",
    props: {
        hullType: String,
    },
    components: { RenderModule },
    setup(props) {
        const store = useStore();
        const tl = computed(() => store.state.shipyards.techLevels);
        const shipModules = computed(
            () => store.state.shipyards.design.modules
        );
        const shipSlots = computed(
            () =>
                window.rules.ships.hullTypes[props.hullType].slots -
                shipModules.value.length
        );
        const modStats = (stub) =>
            window.rules.modules.find(
                (mod) =>
                    mod.techType === stub && mod.hullType === props.hullType
            );
        const onClick = (techType) => {
            store.commit("shipyards/REMOVE_MODULE", techType);
        };
        const getTechLevel = (techType) => {
            const tech = store.state.shipyards.techLevels.find(
                (tech) => tech.type === techType
            );
            if (tech) return tech.level;
            return -1;
        };
        return {
            shipSlots,
            shipModules,
            tl,
            getTechLevel,
            modStats,
            onClick,
        };
    },
};
</script>

<template>
    <div class="using column">
        <div class="modules">
            <render-module
                v-for="module in shipModules"
                :key="`added${module}`"
                :tech-type="modStats(module).techType"
                :tl="getTechLevel(modStats(module).techType)"
                :remove="true"
                @remove-module="onClick(modStats(module).techType)"
            />
            <render-module v-for="slot in shipSlots" :key="slot" />
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
