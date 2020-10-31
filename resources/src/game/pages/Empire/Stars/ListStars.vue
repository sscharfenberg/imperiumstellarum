<script>
/******************************************************************************
 * PageComponent: ListStars
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowStar from "./ShowStar";
export default {
    name: "ListStars",
    components: { ShowStar },
    setup() {
        const store = useStore();
        const stars = computed(() =>
            store.state.empire.stars
                .sort((a, b) => {
                    return a.name.localeCompare(b.name);
                })
                .map((star) => star.id)
        );
        return {
            stars,
        };
    },
};
</script>

<template>
    <ul class="stars" v-if="stars.length > 0">
        <show-star v-for="star in stars" :key="star" :id="star" />
    </ul>
</template>

<style lang="scss" scoped>
.stars {
    padding: 0;
    margin: 0;

    list-style: none;
}
</style>
