<script>
/******************************************************************************
 * PageComponent: EmptyResourceSlot
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import InstallHarvester from "./InstallHarvester";
import { convertLatinToRoman } from "@/game/helpers/format";
export default {
    name: "EmptyResourceSlot",
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
        const starId = computed(() => planet.value.starId);
        const starName = computed(
            () => store.getters["empire/starById"](starId.value).name
        );
        const planetName = computed(
            () =>
                starName.value +
                " - " +
                convertLatinToRoman(planet.value.orbitalIndex)
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
    border: 2px solid transparent;

    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-mystic"), 0.05);
        color: t("t-light");
        border-color: t("g-abbey");
    }

    .icon {
        opacity: 0.3;

        transition: opacity map-get($animation-speeds, "fast") linear;
    }

    &:hover:not([disabled]),
    &:focus:not([disabled]) {
        opacity: 0.8;

        outline: 0;

        .icon {
            opacity: 1;
        }

        @include themed() {
            background: t("g-bunker");
            border-color: t("g-fog");
        }
    }
}
</style>
