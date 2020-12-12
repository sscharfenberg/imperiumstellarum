<script>
/******************************************************************************
 * PageComponent: InstallShipyardModal
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import Icon from "Components/Icon/Icon";
import Costs from "Components/Costs/Costs";
import GameButton from "Components/Button/GameButton";
import { isEntityAffordable } from "@/game/helpers/isAffordable";
export default {
    name: "InstallShipyardModal",
    props: {
        planetId: String,
    },
    components: { Modal, Icon, Costs, GameButton },
    setup(props, { emit }) {
        const store = useStore();
        const shipyardTypes = window.rules.shipyards.hullTypes;
        const selectedShipyardType = ref("");
        const planetName = computed(() =>
            store.getters["empire/planetNameById"](props.planetId)
        );
        const installCosts = computed(() =>
            selectedShipyardType.value
                ? shipyardTypes[selectedShipyardType.value].costs
                : 0
        );
        const playerResources = computed(() => store.state.resources);
        const onSubmit = () => {
            store.dispatch("empire/INSTALL_SHIPYARD", {
                planetId: props.planetId,
                type: selectedShipyardType.value,
            });
            emit("close");
        };
        const isAffordable = computed(() =>
            isEntityAffordable(installCosts.value, playerResources.value)
        );
        return {
            shipyardTypes,
            selectedShipyardType,
            planetName,
            installCosts,
            onSubmit,
            isAffordable,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal
        :title="t('empire.planet.shipyard.title', { name: planetName }) + '?'"
        @close="$emit('close')"
    >
        <p>{{ t("empire.planet.shipyard.explanation") }}</p>
        <div class="shipyard__sizes">
            <label
                v-for="(value, type) in shipyardTypes"
                :key="`shipYardType${type}`"
                :for="`shipYardType${type}`"
            >
                <input
                    type="radio"
                    name="shipyard_type"
                    :id="`shipYardType${type}`"
                    :value="type"
                    v-model="selectedShipyardType"
                />
                <label :for="`shipYardType${type}`" class="shipyard__size">
                    <icon :name="`hull-${type}`" />
                    {{ t("empire.planet.shipyard.types." + type) }}
                </label>
            </label>
        </div>
        <costs v-if="installCosts" :costs="installCosts" />
        <template v-slot:actions>
            <game-button
                v-if="selectedShipyardType"
                :text-string="t('empire.planet.shipyard.submit')"
                icon-name="done"
                :disabled="!isAffordable"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
p {
    margin-top: 0;
}

.shipyard {
    &__sizes {
        display: grid;
        grid-template-columns: 1fr 1fr;

        grid-gap: 4px;
    }

    &__size {
        display: flex;
        align-items: center;
        justify-content: center;

        min-height: 40px;

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
            margin-right: 10px;
        }
    }
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

.costs {
    margin-top: 16px;
}
</style>
