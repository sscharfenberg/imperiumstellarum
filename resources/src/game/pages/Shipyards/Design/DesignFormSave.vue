<script>
/******************************************************************************
 * PageComponent: DesignFormSave
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { calculateBlueprintCosts } from "Components/Ship/useShipCalculations";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
export default {
    name: "DesignFormSave",
    components: { SubHeadline, Icon, GameButton },
    setup() {
        const store = useStore();
        const hullType = computed(() => store.state.shipyards.design.hullType);
        const className = computed(
            () => store.state.shipyards.design.className
        );
        const mods = computed(() => store.state.shipyards.design.modules);
        const resources = computed(() => store.state.resources);
        const saveEnabled = computed(() => {
            return (
                hullType.value.length &&
                className.value.length &&
                mods.value.length ===
                    window.rules.ships.hullTypes[hullType.value].slots
            );
        });
        const costs = computed(() =>
            calculateBlueprintCosts(hullType.value, mods.value)
        );
        const btnEnabled = computed(
            () =>
                resources.value.find((res) => res.type === "research").amount >=
                costs.value.research
        );
        const onClick = () => {
            store.dispatch("shipyards/SAVE_BLUEPRINT", {
                hullType: hullType.value,
                modules: Array.from(mods.value),
                name: className.value,
            });
        };
        return {
            saveEnabled,
            resources,
            costs,
            btnEnabled,
            onClick,
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
        <ul class="costs">
            <li class="title">{{ $t("shipyards.design.save.costs") }}:</li>
            <li
                class="cost"
                :class="{
                    affordable:
                        resources.find((res) => res.type === 'research')
                            .amount >= costs.research,
                    insufficient:
                        resources.find((res) => res.type === 'research')
                            .amount < costs.research,
                }"
                :title="
                    $t('common.costs.ariaLabel', {
                        type: $t('common.resourceTypes.research'),
                        amount: costs.research,
                    })
                "
                :aria-label="
                    $t('common.costs.ariaLabel', {
                        type: $t('common.resourceTypes.research'),
                        amount: costs.research,
                    })
                "
            >
                {{ costs.research }}
                <icon name="res-research" :size="1" />
            </li>
        </ul>
        <game-button
            icon-name="save"
            :disabled="!btnEnabled"
            :aria-disabled="!btnEnabled"
            @click="onClick"
            :text-string="$t('shipyards.design.save.button')"
        />
    </div>
</template>

<style lang="scss" scoped>
.save {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}
.costs {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;

    list-style: none;

    > li {
        padding: 5px 10px;
        margin: 0 2px 2px 0;

        &:last-child {
            margin-right: 0;
        }
    }

    .title {
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-raven");
            border-color: t("g-abbey");
        }
    }

    .cost {
        display: flex;
        align-items: center;

        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-deep");
            border-color: t("g-abbey");
        }

        > svg {
            margin-left: 5px;
        }

        &.insufficient {
            @include themed() {
                color: t("s-error");
                border-color: t("s-error");
            }
        }

        &.affordable {
            @include themed() {
                border-color: t("s-success");
            }
        }
    }
}
</style>
