<script>
/******************************************************************************
 * Component: UpgradeStorage
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
import Costs from "Components/Costs/Costs";
export default {
    name: "UpgradeStorage",
    props: {
        type: {
            type: String,
            required: true,
        },
        amount: {
            type: Number,
            required: true,
        },
        max: {
            type: Number,
            required: true,
        },
        storageLevel: {
            type: Number,
            required: true,
        },
        maxLevel: {
            type: Number,
            required: true,
        },
    },
    components: { Modal, GameButton, Costs },
    setup(props, { emit }) {
        const store = useStore();
        const resources = computed(() => store.state.resources);
        const nextLevel = computed(() => {
            const nextLevel = props.storageLevel + 1;
            if (nextLevel > props.maxLevel) return -1;
            return nextLevel;
        });
        const upgradeCosts = computed(
            () =>
                window.rules.player.resourceTypes[props.type][nextLevel.value]
                    .costs
        );
        const newStorage = computed(
            () =>
                window.rules.player.resourceTypes[props.type][nextLevel.value]
                    .amount
        );
        const onSubmit = () => {
            store.dispatch("INSTALL_STORAGE_UPGRADE", {
                type: props.type,
                level: nextLevel.value,
            });
            emit("close");
        };
        const storageUpgrade = computed(() =>
            store.getters.storageUpgradeByType(props.type)
        );
        const isAffordable = computed(() => {
            const costs =
                window.rules.player.resourceTypes[props.type][nextLevel.value]
                    .costs;
            let isAffordable = true;
            for (const resourceType in costs) {
                if (
                    resourceType !== "turns" &&
                    costs[resourceType] >
                        resources.value.find((res) => res.type === resourceType)
                            .amount
                ) {
                    isAffordable = false;
                }
            }
            return isAffordable;
        });
        return {
            nextLevel,
            upgradeCosts,
            newStorage,
            onSubmit,
            isAffordable,
            storageUpgrade,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal
        :title="t('common.header.storageUpgrades.title.' + type)"
        @close="$emit('close')"
    >
        <ul class="stats">
            <li>
                {{ t("common.header.storageUpgrades.currentLevel") }}<br />{{
                    storageLevel
                }}
            </li>
            <li>
                {{ t("common.header.storageUpgrades.currentStorage") }}<br />{{
                    max
                }}
            </li>
            <li
                v-if="storageUpgrade.newLevel"
                class="stats__two-col stats__dots featured"
                :aria-label="
                    t('common.header.storageUpgrades.untilComplete') +
                    ': ' +
                    storageUpgrade.untilComplete
                "
            >
                {{ t("common.header.storageUpgrades.untilComplete") }}:
                {{ storageUpgrade.untilComplete }}<br />
                <span
                    v-for="n in storageUpgrade.untilComplete"
                    class="stats__dot"
                    role="presentation"
                    aria-hidden="true"
                    :key="n"
                />
            </li>
            <li>
                {{ t("common.header.storageUpgrades.newLevel") }}<br />{{
                    nextLevel
                }}
            </li>
            <li>
                {{ t("common.header.storageUpgrades.newStorage") }}<br />{{
                    newStorage
                }}
            </li>
        </ul>
        <costs :costs="upgradeCosts" />
        <template v-slot:actions>
            <game-button
                :text-string="t('common.header.storageUpgrades.install')"
                icon-name="done"
                :disabled="!isAffordable || storageUpgrade.newLevel"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
