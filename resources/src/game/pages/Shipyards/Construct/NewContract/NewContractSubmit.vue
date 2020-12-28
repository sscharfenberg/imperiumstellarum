<script>
/******************************************************************************
 * PageComponent: NewContractSubmit
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { calculateShipCosts } from "Components/Ship/useShipCalculations";
import { isEntityAffordable } from "@/game/helpers/isAffordable";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import GameButton from "Components/Button/GameButton";
import Loading from "Components/Loading/Loading";
export default {
    name: "NewContractSubmit",
    components: { SubHeadline, GameButton, Loading },
    setup() {
        const store = useStore();
        const blueprintId = computed(
            () => store.state.shipyards.newContract.blueprint
        );
        const resourceCosts = computed(() => {
            const blueprint = store.getters["shipyards/blueprintById"](
                blueprintId.value
            );
            return calculateShipCosts(blueprint.hullType, blueprint.modules);
        });
        const selectedShipyard = computed(
            () => store.state.shipyards.newContract.shipyard
        );
        const amount = computed(() => store.state.shipyards.newContract.amount);
        const playerResources = computed(() => store.state.resources);
        const isAffordable = computed(() =>
            isEntityAffordable(resourceCosts.value, playerResources.value)
        );
        const onReset = () => {
            store.commit("shipyards/SET_CONTRACT_SHIPYARD", "");
            store.commit("shipyards/SET_CONTRACT_BLUEPRINT", "");
            store.commit("shipyards/SET_CONTRACT_AMOUNT", 0);
        };
        const isRequesting = computed(() => store.state.shipyards.requesting);
        const onSubmit = () => {
            store.dispatch("shipyards/CREATE_CONSTRUCTION_CONTRACT", {
                shipyard: selectedShipyard.value,
                blueprint: blueprintId.value,
                amount: amount.value,
            });
        };
        return { resourceCosts, isAffordable, onReset, onSubmit, isRequesting };
    },
};
</script>

<template>
    <sub-headline
        :headline="`#4 ${$t(
            'shipyards.construct.newContract.finalize.submit'
        )}`"
    />
    <p>
        {{
            $t("shipyards.construct.newContract.finalize.explanation", {
                turn: resourceCosts.turns,
            })
        }}
    </p>
    <div class="submit">
        <game-button
            icon-name="cancel"
            :text-string="$t('shipyards.construct.newContract.finalize.reset')"
            @click="onReset"
        />
        <loading v-if="isRequesting" :size="34" />
        <game-button
            icon-name="save"
            :text-string="$t('shipyards.construct.newContract.finalize.submit')"
            :disabled="!isAffordable"
            :aria-disabled="!isAffordable"
            @click="onSubmit"
        />
    </div>
</template>

<style lang="scss" scoped>
.submit {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>
