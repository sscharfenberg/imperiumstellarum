<script>
/******************************************************************************
 * PageComponent: DesignFormSave
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import GameButton from "Components/Button/GameButton";
export default {
    name: "",
    components: { SubHeadline, GameButton },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const className = computed(
            () => store.state.shipyards.design.className
        );
        const mods = computed(() => store.state.shipyards.design.modules);
        const saveEnabled = computed(() => {
            return (
                hullType.value.length &&
                className.value.length &&
                mods.value.length ===
                    window.rules.ships.hullTypes[hullType.value].slots
            );
        });
        const costs = computed(() => {
            const costs = {
                energy:
                    window.rules.ships.hullTypes[hullType.value].costs.energy,
                minerals:
                    window.rules.ships.hullTypes[hullType.value].costs.minerals,
            };
            // costs for modules
            console.log(mods.value.length, Array.from(mods.value));
            mods.value.forEach((type) => {
                console.log("searching for ", type, hullType.value);
                const modCosts = window.rules.modules.find(
                    (m) => m.techType === type && m.hullType === hullType.value
                ).costs;
                console.log(modCosts);
                costs.energy += modCosts.energy;
                costs.minerals += modCosts.minerals;
            });
            // TODO: Kosten anteilig als research
            return costs;
        });
        return {
            saveEnabled,
            costs,
        };
    },
};
</script>

<template>
    <sub-headline
        v-if="saveEnabled"
        :headline="`#4 ${$t('shipyards.design.save.headline')}`"
    />
    <div v-if="saveEnabled" class="save">
        {{ costs }}
        <game-button
            icon-name="save"
            :text-string="$t('shipyards.design.save.button')"
        />
    </div>
</template>
