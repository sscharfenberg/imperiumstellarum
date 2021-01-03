<script>
/******************************************************************************
 * PageComponent: ListStars
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import ShowStar from "./ShowStar";
import { VueDraggableNext } from "vue-draggable-next";
export default {
    name: "ListStars",
    components: { ShowStar, draggable: VueDraggableNext },
    setup() {
        const store = useStore();
        const stars = computed({
            get: () => {
                const unsortedStars = store.state.empire.stars.map(
                    (star) => star.id
                );
                if (!unsortedStars.length) return [];
                const stateOrder = store.state.empire.starsSorted;
                if (!stateOrder.length) return unsortedStars;
                let sortedStars = [];
                for (const sortedStar of stateOrder) {
                    const unsortedStar = unsortedStars.find(
                        (star) => star === sortedStar
                    );
                    // does the star in our order array match a star from the server?
                    // => add to sortedStars, remove from unsorted
                    if (unsortedStar) {
                        sortedStars.push(unsortedStar);
                        unsortedStars.splice(
                            unsortedStars.indexOf(unsortedStar),
                            1
                        );
                    }
                }
                // if there are unsorted (server) stars left, that means the server
                // has more items than are saved in our localStorage array => add them.
                if (unsortedStars.length) {
                    sortedStars = sortedStars.concat(unsortedStars);
                }
                return sortedStars;
            },
            set: (val) => {
                store.commit("empire/SET_STARS_ORDER", val);
            },
        });
        return {
            stars,
        };
    },
    methods: {
        // prevent dragImage while dragging
        setData: function (dataTransfer) {
            const img = document.createElement("img");
            dataTransfer.setDragImage(img, 0, 0); // `dataTransfer` object of HTML5 DragEvent
        },
    },
};
</script>

<template>
    <draggable
        v-model="stars"
        ghost-class="ghost"
        drag-class="drag"
        handle=".handle"
        v-if="stars.length > 0"
        :set-data="setData"
    >
        <transition-group type="transition" name="flip-list">
            <show-star v-for="star in stars" :key="star" :id="star" />
        </transition-group>
    </draggable>
</template>

<style lang="scss" scoped>
.ghost {
    @include themed() {
        background: t("g-ebony");
    }
}
.flip-list-move {
    transition: transform 0.2s;
}
</style>
