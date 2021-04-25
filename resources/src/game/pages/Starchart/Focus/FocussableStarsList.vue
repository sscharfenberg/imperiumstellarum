<script>
/******************************************************************************
 * PageComponent: FocussableStarsList
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FocussableStarsList",
    components: { GameButton },
    setup() {
        const store = useStore();
        const stars = computed(() => store.state.starchart.playerStars);
        const onStarClicked = (x, y) => {
            store.commit("starchart/FOCUS_COORDS", { x, y });
        };
        return {
            stars,
            onStarClicked,
        };
    },
};
</script>

<template>
    <div class="focus-stars">
        <label>{{ $t("starchart.focus.stars") }}</label>
        <ul class="stars">
            <li v-for="star in stars" :key="star.id">
                <game-button
                    :text-string="`${star.name} (${star.x}/${star.y})`"
                    icon-name="location"
                    :size="1"
                    @click="onStarClicked(star.x, star.y)"
                />
            </li>
        </ul>
    </div>
</template>

<style lang="scss" scoped>
.focus-stars {
    margin-bottom: 4px;
    flex-grow: 1;

    @include respond-to("medium") {
        margin-bottom: 0;
    }
}
label {
    display: block;

    margin-bottom: 4px;
}
ul {
    display: flex;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;
    gap: 4px;

    list-style: none;
}
</style>
