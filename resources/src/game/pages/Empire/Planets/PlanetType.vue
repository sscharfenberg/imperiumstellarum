<script>
/******************************************************************************
 * PageComponent: PlanetType
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
export default {
    name: "ShowPlanet",
    props: {
        planetId: String,
    },
    setup(props) {
        const store = useStore();
        const { t } = useI18n();
        const planetType = computed(
            () => store.getters["empire/planetById"](props.planetId).type
        );
        const planetClass = computed(() => `planet-type--${planetType.value}`);
        const label = computed(
            () =>
                t("empire.planet.typeLabel") +
                ": " +
                t("empire.planet.types." + planetType.value)
        );
        return {
            planetType,
            planetClass,
            label,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <aside class="orbit">
        <span
            class="planet-type"
            :class="planetClass"
            :title="label"
            :aria-label="label"
        >
            {{ t("empire.planet.types." + planetType) }}
        </span>
    </aside>
</template>

<style lang="scss" scoped>
.orbit {
    overflow: hidden;

    width: 4.8rem;
    margin-right: 0.4rem;
    flex: 0 0 4.8rem;

    background: url("./bg/orbit.svg") 0 0 no-repeat;

    @include respond-to("medium") {
        margin-right: 0.8rem;
    }
}
.planet-type {
    display: inline-block;

    width: 4.8rem;
    height: 4.8rem;

    background: transparent url("./bg/planets.png") 0 0 no-repeat;

    text-indent: -1000em;

    &--gas {
        background-position: 0 -48px;
    }
    &--ice {
        background-position: 0 -96px;
    }
    &--iron {
        background-position: 0 -144px;
    }
    &--desert {
        background-position: 0 -192px;
    }
    &--toxic {
        background-position: 0 -240px;
    }
    &--carbon {
        background-position: 0 -288px;
    }
    &--tomb {
        background-position: 0 -336px;
    }
}
</style>
