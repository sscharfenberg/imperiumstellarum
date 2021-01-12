<script>
/******************************************************************************
 * PageComponent: ConstructionContractDetails
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { formatNumber } from "@/game/helpers/format";
import Icon from "Components/Icon/Icon";
import GameButton from "Components/Button/GameButton";
import DeleteConstructionContractModal from "./DeleteConstructionContractModal";
export default {
    name: "ConstructionContractDetails",
    props: {
        contractId: String,
    },
    components: { Icon, GameButton, DeleteConstructionContractModal },
    setup(props) {
        const store = useStore();
        const contract = computed(() =>
            store.getters["shipyards/contractById"](props.contractId)
        );
        const showModal = ref(false);
        const blueprint = computed(() =>
            store.getters["shipyards/blueprintById"](contract.value.blueprintId)
        );
        const toBlueprint = () => {
            store.commit("shipyards/SET_PAGE", 0);
            store.commit("shipyards/SET_MANAGE_BLUEPRINT_PREVIEW", {
                id: blueprint.value.id,
                hullType: blueprint.value.hullType,
                className: blueprint.value.name,
                modules: blueprint.value.modules,
                techLevels: blueprint.value.techLevels,
            });
        };
        return {
            contract,
            toBlueprint,
            showModal,
            formatNumber,
        };
    },
};
</script>

<template>
    <div class="contract-details">
        <ul class="data">
            <li>{{ $t("shipyards.constructions.details.ships") }}</li>
            <li>
                {{ $t("shipyards.constructions.details.total") }}:
                {{ contract.amount }}
            </li>
            <li>
                {{ $t("shipyards.constructions.details.left") }}:
                {{ contract.amountLeft }}
            </li>
        </ul>
        <ul class="data">
            <li>{{ $t("shipyards.constructions.details.turns") }}</li>
            <li>
                {{ $t("shipyards.constructions.details.perShip") }}:
                {{ contract.turnsPerShip }}
            </li>
            <li>
                {{ $t("shipyards.constructions.details.next") }}:
                {{ contract.turnsLeft }}
            </li>
        </ul>
        <ul class="data">
            <li>{{ $t("shipyards.constructions.details.costs") }}</li>
            <li class="col2">
                {{ formatNumber(contract.costs.minerals) }}
                <icon name="res-minerals" />
                {{ formatNumber(contract.costs.energy) }}
                <icon name="res-energy" />
            </li>
        </ul>
        <game-button
            icon-name="search"
            :text-string="$t('shipyards.constructions.details.toBlueprint')"
            @click="toBlueprint"
        />
        <game-button
            icon-name="delete"
            :text-string="$t('shipyards.constructions.details.delete')"
            @click="showModal = true"
        />
    </div>
    <delete-construction-contract-modal
        v-if="showModal"
        :contract-id="contractId"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
.contract-details {
    display: flex;
    flex-wrap: wrap;

    padding: 4px;
    border: 1px solid transparent;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        border-color: t("g-deep");
    }
}

.data {
    display: flex;
    align-self: flex-start;
    flex-wrap: wrap;

    width: 100%;
    padding: 0;
    margin: 0 0 4px 0;

    list-style: none;

    @include respond-to("medium") {
        width: auto;
        margin: 0 8px 4px 0;
    }

    > li {
        padding: 4px;
        //flex-basis: 50%;

        &:first-child,
        &.col2 {
            display: flex;
            align-items: center;
            //justify-content: center;

            border-bottom: 4px solid transparent;
            flex-basis: 100%;

            @include themed() {
                border-color: t("g-sunken");
            }

            .icon {
                margin-left: 8px;

                &:first-child {
                    margin-right: 16px;
                }
            }
        }

        &:last-child {
            border-left: 4px solid transparent;

            @include themed() {
                border-color: t("g-sunken");
            }
        }
    }

    @include themed() {
        background: t("g-bunker");
    }
}

.btn {
    margin-right: 8px;

    &:first-of-type {
        @include respond-to("medium") {
            margin-left: auto;
        }
    }

    &:last-of-type {
        margin-right: 0;
    }
}
</style>
