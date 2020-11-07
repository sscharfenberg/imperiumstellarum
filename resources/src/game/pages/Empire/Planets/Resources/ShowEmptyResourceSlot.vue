<script>
/******************************************************************************
 * PageComponent: ShowEmptyResourceSlot
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import InstallHarvester from "./InstallHarvester";
export default {
    name: "ShowEmptyResourceSlot",
    props: {
        resourceType: String,
        planetId: String,
    },
    components: { Icon, InstallHarvester },
    setup(props) {
        const i18n = useI18n();
        const store = useStore();
        const btnLabel = i18n.t("empire.planet.harvesters.install", {
            type: i18n.t(
                "empire.planet.harvesters.names." + props.resourceType
            ),
        });
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        const planetName = computed(() =>
            store.getters["empire/planetNameById"](planet.value.id)
        );
        const showModal = ref(false);
        return {
            showModal,
            btnLabel,
            planetName,
        };
    },
};
</script>

<template>
    <button :title="btnLabel" :aria-label="btnLabel" @click="showModal = true">
        <icon :name="`res-${resourceType}`" />
    </button>
    <install-harvester
        v-if="showModal"
        :resource-type="resourceType"
        :planet-id="planetId"
        :planet-name="planetName"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
button {
    display: flex;
    align-items: center;

    height: 4rem;
    padding: 0.5rem 1rem;
    border: 2px dashed transparent;

    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear,
        border-style map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-deep"), 0.7);
        color: t("t-light");
        border-color: t("g-asher");
    }

    &:hover:not([disabled]),
    &:focus:not([disabled]) {
        opacity: 0.8;

        outline: 0;

        @include themed() {
            background: t("g-ebony");
            border-style: solid;
            border-color: t("g-fog");
        }
    }
}
</style>
