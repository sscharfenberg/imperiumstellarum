<script>
/******************************************************************************
 * PageComponent: ShowStar
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ListPlanets from "@/game/pages/Empire/Planets/ListPlanets";
import Loading from "Components/Loading/Loading";
import ShortStarSummary from "./ShortStarSummary";
import StarDragHandle from "./StarDragHandle";
import StarLocation from "./StarLocation";
import StarName from "./StarName/StarName";
import StarNameEdit from "./StarName/StarNameEdit";
import Spectral from "Components/Spectral/Spectral";
export default {
    name: "ShowStar",
    props: {
        id: String,
    },
    components: {
        StarNameEdit,
        Spectral,
        StarDragHandle,
        StarName,
        ShortStarSummary,
        StarLocation,
        ListPlanets,
        Loading,
    },
    setup(props) {
        const store = useStore();
        const star = computed(() => store.getters["empire/starById"](props.id));
        const isCollapsed = computed(() =>
            store.getters["empire/isStarCollapsed"](props.id)
        );
        const isStarEditing = computed(
            () => store.state.empire.editingStarId === props.id
        );
        const isStarChanging = computed(
            () => store.state.empire.changingStarId === props.id
        );
        return {
            star,
            isCollapsed,
            isStarEditing,
            isStarChanging,
        };
    },
};
</script>

<template>
    <div class="system">
        <div class="star">
            <spectral :spectral="star.spectral" />
            <star-drag-handle />
            <star-name
                v-if="!isStarEditing && !isStarChanging"
                :star-id="star.id"
            />
            <star-name-edit
                v-if="isStarEditing && !isStarChanging"
                :star-id="star.id"
                :star-name="star.name"
            />
            <short-star-summary :star-id="star.id" />
            <loading v-if="isStarChanging" :size="38" class="loading" />
            <star-location :x="star.x" :y="star.y" />
        </div>
        <list-planets v-if="!isCollapsed" :star-id="star.id" />
    </div>
</template>

<style lang="scss" scoped>
.system {
    display: flex;
    flex-direction: column;

    margin: 0 0 8px 0;

    @include respond-to("small") {
        margin-bottom: 16px;
    }

    @include themed() {
        background: radial-gradient(
            ellipse 35px 35px at 25px 25px,
            transparent 0%,
            transparent 99%,
            t("g-sunken") 100%
        );
    }
}
.star {
    display: flex;

    width: 100%;
}
.loading {
    margin: 5px auto;
}
</style>
