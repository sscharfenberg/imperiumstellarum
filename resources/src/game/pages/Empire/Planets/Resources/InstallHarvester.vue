<script>
/******************************************************************************
 * PageComponent: InstallHarvester
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import Costs from "Components/Costs/Costs";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
import { isEntityAffordable } from "@/game/helpers/isAffordable";
export default {
    name: "InstallHarvester",
    props: {
        resourceType: String,
        planetId: String,
        planetName: String,
    },
    components: { Modal, Costs, GameButton, Icon },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const installCosts = computed(
            () => window.rules.harvesters[props.resourceType].costs
        );
        const playerResources = computed(() => store.state.resources);
        const baseProd =
            window.rules.harvesters[props.resourceType].baseProduction;
        const efficiency = computed(
            () =>
                store.getters["empire/planetById"](
                    props.planetId
                ).resources.find(
                    (res) => res.resourceType === props.resourceType
                ).value
        );
        const finalProd = computed(
            () =>
                efficiency.value *
                window.rules.harvesters[props.resourceType].baseProduction
        );
        const isAffordable = computed(() =>
            isEntityAffordable(installCosts.value, playerResources.value)
        );
        const onSubmit = () => {
            store.dispatch("empire/INSTALL_HARVESTER", {
                planetId: props.planetId,
                resourceType: props.resourceType,
            });
            emit("close");
        };
        return {
            isAffordable,
            installCosts,
            onSubmit,
            baseProd,
            efficiency,
            finalProd,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal
        :title="
            t('empire.planet.harvesters.installModal.question', {
                type: t('empire.planet.harvesters.names.' + resourceType),
                planet: planetName,
            })
        "
        @close="$emit('close')"
    >
        <ul class="stats">
            <li class="stats--two-col stats--padded stats--centered">
                <icon :name="`res-${resourceType}`" />
                &nbsp;
                {{ t("empire.planet.harvesters.production.title") }}
            </li>
            <li>
                {{
                    t("empire.planet.harvesters.production.base", {
                        type: t("common.resourceTypes." + resourceType),
                    })
                }}<br />
                {{ baseProd }}
            </li>
            <li>
                {{
                    t("empire.planet.harvesters.production.resGrade", {
                        type: t(
                            "empire.planet.harvesters.names." + resourceType
                        ),
                    })
                }}<br />
                {{ efficiency }}
            </li>
            <li class="stats--two-col featured">
                {{ t("empire.planet.harvesters.production.finalProd") }}:
                {{ Math.floor(finalProd) }}
            </li>
        </ul>
        <costs :costs="installCosts" />
        <template v-slot:actions>
            <game-button
                :text-string="t('empire.planet.harvesters.installModal.submit')"
                icon-name="done"
                :disabled="!isAffordable"
                :primary="true"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>
