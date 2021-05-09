<script>
/******************************************************************************
 * PageComponent: EncountersListRenderSingle
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Spectral from "Components/Spectral/Spectral";
export default {
    name: "EncountersListRenderSingle",
    props: {
        encounter: {
            type: Object,
            required: true,
        },
    },
    components: { Spectral },
    setup(props) {
        const store = useStore();
        const star = computed(() =>
            store.getters["encounters/starById"](props.encounter.starId)
        );
        const starOwner = computed(() =>
            store.getters["encounters/playerById"](props.encounter.ownerId)
        );
        return { star, starOwner };
    },
};
</script>

<template>
    <router-link
        :to="{
            name: 'EncounterDetails',
            params: { encounterId: encounter.id },
        }"
        class="encounter"
    >
        <spectral :spectral="star.spectral" />
        {{ star }}<br />
        {{ starOwner }}
    </router-link>
</template>

<style lang="scss" scoped>
.encounter {
    display: flex;

    text-decoration: none;

    transition: background map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background: radial-gradient(
            ellipse 35px 35px at 25px 25px,
            transparent 0%,
            transparent 99%,
            t("g-sunken") 100%
        );
        color: t("t-light");
    }

    &:hover {
        @include themed() {
            background: radial-gradient(
                ellipse 35px 35px at 25px 25px,
                transparent 0%,
                transparent 99%,
                t("g-bunker") 100%
            );
            color: t("b-christine");
        }
    }
}
</style>
