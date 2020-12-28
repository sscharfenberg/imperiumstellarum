<script>
/******************************************************************************
 * PageComponent: NewContractSelectAmount
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "NewContractSelectAmount",
    components: { SubHeadline },
    setup() {
        const store = useStore();
        const rules = window.rules.shipyards.contracts.amount;
        const amount = computed({
            get: () => store.state.shipyards.newContract.amount,
            set: (val) => {
                store.commit(
                    "shipyards/SET_CONTRACT_AMOUNT",
                    parseInt(val, 10)
                );
            },
        });
        return {
            amount,
            rules,
        };
    },
};
</script>

<template>
    <sub-headline
        :headline="`#3 ${$t(
            'shipyards.construct.newContract.amount.headline'
        )}`"
    />
    <div class="amount">
        <label for="amountNumber">{{
            $t("shipyards.construct.newContract.amount.label")
        }}</label>
        <input
            type="range"
            id="researchSpeedSlider"
            :min="rules.min"
            :aria-valuemin="rules.min"
            :max="rules.max"
            :aria-valuemax="rules.max"
            step="1"
            v-model="amount"
        />
        <input
            type="number"
            id="amountNumber"
            :min="rules.min"
            :aria-valuemin="rules.min"
            :max="rules.max"
            :aria-valuemax="rules.max"
            step="1"
            v-model="amount"
        />
    </div>
</template>

<style lang="scss" scoped>
.amount {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    margin-bottom: 8px;
    flex-basis: 100%;

    @include respond-to("medium") {
        margin-bottom: 16px;
    }

    @include respond-to("medium") {
        flex-grow: 1;
        flex-basis: auto;
    }

    > input[type="range"] {
        margin: 0 8px 0 0;
        flex-grow: 1;

        @include respond-to("medium") {
            margin-right: 16px;
        }
    }

    > input[type="number"] {
        padding: 5px 10px;
        border: 0;
        flex: 0 0 45px;

        font-weight: 300;
        line-height: 1;

        @include themed() {
            background: t("g-deep");
            color: t("t-light");
        }

        &:focus {
            outline: 0;

            @include themed() {
                background: t("g-raven");
                color: t("g-white");
            }
        }
    }

    > label {
        flex-basis: 100%;

        @include respond-to("medium") {
            margin-right: 16px;
            flex-basis: auto;
        }
    }
}
</style>
