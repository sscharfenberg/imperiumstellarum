<script>
/******************************************************************************
 * PageComponent: NewContractSelectShipyard
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import Icon from "Components/Icon/Icon";
export default {
    name: "NewContractSelectShipyard",
    components: { SubHeadline, Icon },
    setup() {
        const store = useStore();
        const shipyards = computed(() =>
            store.state.shipyards.shipyards.filter((s) => s.untilComplete === 0)
        );
        const selectedShipyard = computed({
            get: () => store.state.shipyards.newContract.shipyard,
            set: (value) => {
                store.commit("shipyards/SET_CONTRACT_SHIPYARD", value);
            },
        });
        return { shipyards, selectedShipyard };
    },
};
</script>

<template>
    <sub-headline
        :headline="`#1 ${$t(
            'shipyards.construct.newContract.shipyard.headline'
        )}`"
    />
    <div class="contract__shipyards">
        <label
            v-for="shipyard in shipyards"
            :key="`shipyardContract${shipyard.id}`"
            :for="`shipyardContract${shipyard.id}`"
        >
            <input
                type="radio"
                name="shipyard"
                :id="`shipyardContract${shipyard.id}`"
                :value="shipyard.id"
                v-model="selectedShipyard"
            />
            <label
                :for="`shipyardContract${shipyard.id}`"
                class="contract__shipyard-type"
            >
                <icon :name="`hull-${shipyard.type}`" />
                <span>
                    {{
                        $t("empire.planet.shipyard.info.title", {
                            type: $t(
                                "empire.planet.shipyard.types." + shipyard.type
                            ),
                            name: shipyard.planetName,
                        })
                    }}
                </span>
            </label>
        </label>
    </div>
</template>

<style lang="scss" scoped>
.contract {
    &__shipyards {
        display: flex;
        justify-content: space-between;
        flex-direction: column;

        margin-bottom: 8px;

        @include respond-to("medium") {
            flex-direction: row;

            margin-bottom: 16px;
            grid-gap: 8px;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked + label {
            @include themed() {
                border: 1px solid t("g-astral");

                background: t("g-iron");
                color: t("t-dark");
            }
        }

        > label {
            margin-bottom: 4px;

            &:last-of-type {
                margin-bottom: 0;
            }

            @include respond-to("medium") {
                margin-bottom: 0;
                flex: 0 0 calc(50% - 8px);
            }
        }
    }

    &__shipyard-type {
        display: flex;
        align-items: center;
        justify-content: center;

        height: 100%;
        padding: 8px;

        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast"),
            border-color map-get($animation-speeds, "fast"),
            color map-get($animation-speeds, "fast");

        @include themed() {
            border: 1px solid t("g-abbey");

            background: t("g-deep");
            color: t("t-light");
        }

        &:hover {
            @include themed() {
                border: 1px solid t("g-raven");

                background: t("g-sunken");
                color: t("t-light");
            }
        }

        > .icon {
            width: 24px;
            height: 12px;
            margin-right: 8px;
            flex: 0 0 24px;

            @include respond-to("medium") {
                width: 36px;
                height: 18px;
                margin-right: 16px;
                flex: 0 0 36px;
            }
        }
    }
}
</style>
