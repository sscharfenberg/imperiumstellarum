<script>
/******************************************************************************
 * PageComponent: ShowHarvesterInfo
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
import Modal from "Components/Modal/Modal";
export default {
    name: "ShowHarvesterInfo",
    props: {
        resourceType: String,
        harvesterId: String,
    },
    components: { Icon, Modal },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const baseProd =
            window.rules.harvesters[props.resourceType].baseProduction;
        const harvester = computed(() =>
            store.getters["empire/harvesterById"](props.harvesterId)
        );
        const planet = computed(() =>
            store.getters["empire/planetById"](harvester.value.planetId)
        );
        const planetName = computed(() =>
            store.getters["empire/planetNameById"](planet.value.id)
        );
        const efficiency = computed(
            () =>
                planet.value.resources.find(
                    (res) => res.resourceType === props.resourceType
                ).value
        );
        const modalTitle = i18n.t("empire.planet.harvesters.infoModal.title", {
            type: i18n.t(
                "empire.planet.harvesters.names." + props.resourceType
            ),
            name: planetName.value,
        });
        return {
            planetName,
            baseProd,
            efficiency,
            harvester,
            modalTitle,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <modal :title="modalTitle" @close="$emit('close')">
        <ul class="stats">
            <li
                v-if="harvester.untilComplete"
                class="stats--two-col stats--padded stats__dots featured"
                :aria-label="
                    t('empire.planet.harvesters.untilComplete') +
                    ': ' +
                    harvester.untilComplete
                "
            >
                <div class="stats-hdl">
                    {{
                        t(
                            "empire.planet.harvesters.untilComplete",
                            { turns: harvester.untilComplete },
                            harvester.untilComplete
                        )
                    }}
                </div>
                <span
                    v-for="n in harvester.untilComplete"
                    class="stats__dot"
                    role="presentation"
                    aria-hidden="true"
                    :key="n"
                />
            </li>
            <li class="stats--two-col stats--padded stats--centered">
                <icon :name="`res-${resourceType}`" />
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
            <li class="stats--two-col stats--padded">
                {{ t("empire.planet.harvesters.production.finalProd") }}:
                {{ harvester.production }}
            </li>
        </ul>
    </modal>
</template>

<style lang="scss" scoped>
.stats {
    margin-bottom: 0;
}

.stats--centered .icon {
    margin-right: 1rem;
}

.stats-hdl {
    margin-bottom: 0.5rem;
    flex: 0 0 100%;
}
</style>
