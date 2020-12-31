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
                // TODO: don't check whether the blueprint can be built in the shipyard, only reset if not.
                store.commit("shipyards/SET_CONTRACT_BLUEPRINT", "");
                store.commit("shipyards/SET_CONTRACT_SHIPYARD", value);
            },
        });
        const buildingShipyards = computed(() =>
            store.state.shipyards.constructionContracts.map((c) => c.shipyardId)
        );
        return { shipyards, selectedShipyard, buildingShipyards };
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
            :class="{ inactive: buildingShipyards.includes(shipyard.id) }"
        >
            <input
                type="radio"
                name="shipyard"
                :id="`shipyardContract${shipyard.id}`"
                :value="shipyard.id"
                v-model="selectedShipyard"
                :disabled="buildingShipyards.includes(shipyard.id)"
            />
            <label
                :for="`shipyardContract${shipyard.id}`"
                class="contract__shipyard-type"
                :class="{ inactive: buildingShipyards.includes(shipyard.id) }"
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
                background-color: t("g-iron");
                color: t("t-dark");
                border-color: t("g-white");
            }
        }

        > label {
            margin-bottom: 4px;

            &:last-of-type {
                margin-bottom: 0;
            }

            &.inactive {
                cursor: not-allowed;
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
        border: 2px solid transparent;

        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast"),
            border-color map-get($animation-speeds, "fast"),
            color map-get($animation-speeds, "fast");

        @include themed() {
            background-color: t("g-deep");
            color: t("t-light");
            border-color: t("g-abbey");
        }

        &:hover:not(.inactive) {
            @include themed() {
                background-color: t("g-sunken");
                color: t("t-light");
                border-color: t("g-raven");
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

        &.inactive {
            border: 2px dashed transparent;

            pointer-events: none;

            @include themed() {
                background-color: t("g-asher");
                color: t("t-subdued");
                border-color: t("s-building");
            }
        }
    }
}
</style>
