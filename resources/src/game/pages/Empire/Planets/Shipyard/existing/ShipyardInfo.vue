<script>
/******************************************************************************
 * PageComponent: ShipyardInfo
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import Icon from "Components/Icon/Icon";
import Costs from "Components/Costs/Costs";
import GameButton from "Components/Button/GameButton";
import { isEntityAffordable } from "@/game/helpers/isAffordable";
export default {
    name: "ShipyardInfo",
    props: {
        planetId: String,
    },
    components: { Modal, Icon, Costs, GameButton },
    setup(props, { emit }) {
        const store = useStore();
        const i18n = useI18n();
        const shipyard = computed(() =>
            store.getters["empire/shipyardByPlanetId"](props.planetId)
        );
        const type = computed(() => {
            if (shipyard.value.xlarge) return "xlarge";
            if (shipyard.value.large) return "large";
            if (shipyard.value.medium) return "medium";
            return "small";
        });
        const upgradeType = computed(() => {
            if (shipyard.value.large) return "xlarge";
            if (shipyard.value.medium) return "large";
            return "medium";
        });
        const modalTitle = computed(() => {
            const planetName = store.getters["empire/planetNameById"](
                props.planetId
            );
            return i18n.t("empire.planet.shipyard.info.title", {
                type: i18n.t("empire.planet.shipyard.types." + type.value),
                name: planetName,
            });
        });
        const playerResources = computed(() => store.state.resources);
        const upgradeCosts = computed(
            () =>
                window.rules.shipyards.hullTypes[upgradeType.value].upgradeCosts
        );
        const onSubmit = () => {
            store.dispatch("empire/UPGRADE_SHIPYARD", {
                planetId: props.planetId,
                type: upgradeType.value,
            });
            emit("close");
        };
        const isAffordable = computed(() =>
            isEntityAffordable(upgradeCosts.value, playerResources.value)
        );
        return {
            modalTitle,
            shipyard,
            upgradeType,
            upgradeCosts,
            isAffordable,
            onSubmit,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal :title="modalTitle" @close="$emit('close')">
        <ul class="stats">
            <li class="featured" v-if="shipyard.untilComplete !== 0">
                {{ t("empire.planet.shipyard.info.building") }}
            </li>
            <li v-if="shipyard.untilComplete !== 0">
                {{ t("empire.planet.shipyard.info.untilComplete") }}:
                {{ shipyard.untilComplete }}
            </li>
            <li v-if="shipyard.untilComplete === 0" class="stats--two-col">
                {{ t("empire.planet.shipyard.info.availableHulls") }}
            </li>
            <li
                v-if="shipyard.untilComplete === 0"
                class="stats--two-col hulls"
            >
                <span>
                    <icon name="hull-ark" />
                    {{ t("shipyards.hulls.ark") }}
                </span>
                <span>
                    <icon name="hull-small" />
                    {{ t("shipyards.hulls.small") }}
                </span>
                <span v-if="shipyard.medium">
                    <icon name="hull-medium" />
                    {{ t("shipyards.hulls.medium") }}
                </span>
                <span v-if="shipyard.large">
                    <icon name="hull-large" />
                    {{ t("shipyards.hulls.large") }}
                </span>
                <span v-if="shipyard.xlarge">
                    <icon name="hull-xlarge" />
                    {{ t("shipyards.hulls.xlarge") }}
                </span>
            </li>
        </ul>
        <ul
            class="stats"
            v-if="!shipyard.xlarge && shipyard.untilComplete === 0"
        >
            <li class="stats--two-col featured">
                {{
                    t("empire.planet.shipyard.info.upgrade", {
                        type: t("empire.planet.shipyard.types." + upgradeType),
                    })
                }}
            </li>
        </ul>
        <costs
            v-if="!shipyard.xlarge && shipyard.untilComplete === 0"
            :costs="upgradeCosts"
        />
        <template
            v-slot:actions
            v-if="!shipyard.xlarge && shipyard.untilComplete === 0"
        >
            <game-button
                :text-string="t('empire.planet.shipyard.info.submit')"
                icon-name="done"
                :disabled="!isAffordable"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.hulls {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;

    padding: 0 0.4rem;

    > span {
        display: flex;
        align-items: center;
        justify-content: center;

        margin: 0.4rem 0.8rem;

        .icon {
            margin-right: 0.5rem;
        }
    }
}
</style>
