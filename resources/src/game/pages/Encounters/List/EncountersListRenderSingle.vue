<script>
/******************************************************************************
 * PageComponent: EncountersListRenderSingle
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import EncountersListStarLocation from "./EncountersListStarLocation";
import EncountersListStarName from "./EncountersListStarName";
import EncountersListStarOwner from "./EncountersListStarOwner";
import EncountersListTurn from "./EncountersListTurn";
import EncountersListParticipants from "./EncountersListParticipants";
import Spectral from "Components/Spectral/Spectral";
export default {
    name: "EncountersListRenderSingle",
    props: {
        encounter: {
            type: Object,
            required: true,
        },
    },
    components: {
        EncountersListStarLocation,
        EncountersListStarName,
        EncountersListStarOwner,
        EncountersListTurn,
        EncountersListParticipants,
        Spectral,
    },
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
        :class="{ read: encounter.read }"
    >
        <spectral :spectral="star.spectral" />
        <encounters-list-turn :number="encounter.turn" />
        <encounters-list-star-owner
            :ticker="starOwner.ticker"
            :name="starOwner.name"
            :colour="starOwner.colour"
        />
        <encounters-list-star-name :name="encounter.starName" />
        <encounters-list-star-location :coord-x="star.x" :coord-y="star.y" />
        <encounters-list-participants
            :participants="encounter.participantIds"
        />
    </router-link>
</template>

<style lang="scss" scoped>
.encounter {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    min-height: 48px;
    padding: 0 4px 0 0;
    gap: 4px;

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

    @include respond-to("medium") {
        padding: 0 8px 0 0;
        gap: 8px;
    }

    &.read {
        @include themed() {
            background: radial-gradient(
                ellipse 35px 35px at 25px 25px,
                transparent 0%,
                transparent 99%,
                t("g-deep") 100%
            );
        }
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

    .encounter-item {
        display: flex;
        align-items: center;

        height: 40px;
        padding: 4px;
        border: 1px solid;

        @include respond-to("medium") {
            padding: 8px;
        }

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }
    }
}
</style>
