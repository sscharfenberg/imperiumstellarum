<script>
/******************************************************************************
 * PageComponent: InstallShipyard
 *****************************************************************************/
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
import InstallShipyardModal from "./InstallShipyardModal";
export default {
    name: "InstallShipyard",
    props: {
        planetId: String,
    },
    components: { Icon, InstallShipyardModal },
    setup() {
        const showModal = ref(false);
        const i18n = useI18n();
        const btnTitle = i18n.t("empire.planet.shipyard.build");
        return {
            showModal,
            btnTitle,
        };
    },
};
</script>

<template>
    <button
        class="planet__shipyard"
        @click="showModal = true"
        :title="btnTitle"
        :aria-label="btnTitle"
    >
        <icon name="shipyards" />
    </button>
    <install-shipyard-modal
        v-if="showModal"
        :planet-id="planetId"
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
    margin: 0 0.5rem 0.5rem 0;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear,
        border-style map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-deep"), 0.7);
        color: t("t-light");
        border-color: t("g-asher");
    }

    &:hover:not([disabled]) {
        opacity: 0.8;

        @include themed() {
            background: t("g-ebony");
            border-style: solid;
            border-color: t("g-fog");
        }
    }
}
</style>
