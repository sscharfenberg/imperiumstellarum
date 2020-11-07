<script>
/******************************************************************************
 * PageComponent: ShowHarvester
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import ShowHarvesterInfo from "./ShowHarvesterInfo";
export default {
    name: "ShowHarvester",
    props: {
        resourceType: String,
        harvesterId: String,
    },
    components: { Icon, ShowHarvesterInfo },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const showModal = ref(false);
        const harvester = computed(() =>
            store.getters["empire/harvesterById"](props.harvesterId)
        );
        const btnLabel = computed(() =>
            i18n.t("empire.planet.harvesters.building", {
                type: i18n.t(
                    "empire.planet.harvesters.names." + props.resourceType
                ),
            })
        );
        const untilCompleteLabel = computed(() =>
            i18n.t("empire.planet.harvesters.untilComplete", {
                turns: harvester.value.untilComplete,
            })
        );
        return {
            showModal,
            harvester,
            btnLabel,
            untilCompleteLabel,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <button
        :class="{ building: harvester.untilComplete > 0 }"
        :title="btnLabel"
        :aria-label="btnLabel"
        @click="showModal = true"
    >
        <icon :name="`res-${resourceType}`" />
        <div
            v-if="harvester.untilComplete"
            class="build-turns"
            :aria-label="untilCompleteLabel"
        >
            <div
                v-for="n in harvester.untilComplete"
                class="build-turns__turn"
                role="presentation"
                aria-hidden="true"
                :key="n"
            >
                •
            </div>
        </div>
    </button>
    <show-harvester-info
        v-if="showModal"
        :resource-type="resourceType"
        :harvester-id="harvesterId"
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
        border-color map-get($animation-speeds, "fast") linear,
        border-style map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-deep"), 0.7);
        color: t("t-light");
        border-color: t("g-charcoal");
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

    &.building {
        @include themed() {
            border-color: t("s-building");
        }
    }

    .build-turns {
        display: flex;
        flex-wrap: wrap;

        max-width: 3.2rem;
        margin: 4px 0 0 10px;

        &__turn {
            width: 4px;
            height: 4px;
            margin: 0 4px 4px 0;

            border-radius: 50%;

            text-indent: -1000em;

            @include themed() {
                background: linear-gradient(
                    to bottom,
                    t("s-warning") 0%,
                    t("s-error") 100%
                );
            }
        }
    }
}
</style>
