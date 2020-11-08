<script>
/******************************************************************************
 * PageComponent: ShowPopulation
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
import ShowColony from "./ShowColony";
export default {
    name: "ShowPopulation",
    props: {
        planetId: String,
        starName: String,
    },
    components: { ShowColony, Icon },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const showModal = ref(false);
        const planet = computed(() =>
            store.getters["empire/planetById"](props.planetId)
        );
        const pop = computed(() => {
            if (!planet.value) return 0;
            return Math.floor(planet.value.population || 0);
        });
        const label = computed(() =>
            i18n.t("empire.planet.population.label", { num: pop.value })
        );
        return {
            planet,
            showModal,
            pop,
            label,
        };
    },
};
</script>

<template>
    <div class="population planet__item">
        <button :title="label" :aria-label="label" @click="showModal = true">
            <icon name="population" />
            {{ pop }}
        </button>
        <show-colony
            v-if="showModal"
            :planet-id="planetId"
            :star-name="starName"
            :index="planet.orbitalIndex"
            @close="showModal = false"
        />
    </div>
</template>

<style lang="scss" scoped>
.population button {
    display: flex;
    align-items: center;

    height: 4rem;
    padding: 0.5rem 1rem;
    border: 2px solid transparent;
    margin-bottom: 0.5rem;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: rgba(t("g-mystic"), 0.05);
        color: t("t-light");
        border-color: t("g-abbey");
    }

    &:hover,
    &:focus {
        @include themed() {
            background: t("g-bunker");
            border-color: t("g-fog");
        }
    }

    .icon {
        margin-left: -5px;
    }
}
</style>
