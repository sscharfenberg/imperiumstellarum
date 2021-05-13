<script>
/******************************************************************************
 * PageComponent: EncounterDetailsMeta
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import EncountersListStarLocation from "../EncounterMeta/EncountersListStarLocation";
import EncountersListStarName from "../EncounterMeta/EncountersListStarName";
import EncountersListStarOwner from "../EncounterMeta/EncountersListStarOwner";
import EncountersListTurn from "../EncounterMeta/EncountersListTurn";
import EncountersListParticipants from "../EncounterMeta/EncountersListParticipants";
import Spectral from "Components/Spectral/Spectral";
export default {
    name: "EncounterDetailsMeta",
    props: {
        star: Object,
        turn: Number,
        participants: Array,
        ownerId: String,
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
        const starOwner = computed(() =>
            store.getters["encounters/playerById"](props.ownerId)
        );
        return { starOwner };
    },
};
</script>

<template>
    <div class="encounter-meta">
        <spectral :spectral="star.spectral" />
        <encounters-list-turn :number="turn" />
        <encounters-list-star-owner
            :ticker="starOwner.ticker"
            :name="starOwner.name"
            :colour="starOwner.colour"
        />
        <encounters-list-star-name :name="star.name" />
        <encounters-list-star-location :coord-x="star.x" :coord-y="star.y" />
        <encounters-list-participants :participants="participants" />
    </div>
</template>

<style lang="scss" scoped>
.encounter-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    min-height: 48px;
    padding: 0 4px 0 0;
    margin: 0 0 8px 0;
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
        margin: 0 0 16px 0;
        gap: 8px;
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
</style>
