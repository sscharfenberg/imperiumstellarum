<script>
/******************************************************************************
 * PageComponent: NewFleetLocation
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import Dropdown from "Components/Dropdown/Dropdown";
export default {
    name: "NewFleetLocation",
    components: { Dropdown },
    setup() {
        const store = useStore();
        const sortOrder = computed(() => store.state.empire.starsSorted);
        const focusSelectModel = ref(null);

        /**
         * @function get options from store, reformat them and sort them.
         * @type {ComputedRef<{name: string, x: *, y: *, id: *}[]>}
         */
        const options = computed(() => {
            const unsortedStars = store.state.fleets.stars.map(
                (star) => star.id
            );
            const stars = store.state.fleets.stars;
            let options = stars.map((star) => {
                return {
                    val: star.id,
                    label: `${star.name} (${star.x}/${star.y})`,
                };
            });
            if (!sortOrder.value.length) {
                return options;
            } else {
                let sortedStars = [];
                for (const sortedStar of sortOrder.value) {
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
                options = options.sort(
                    (a, b) =>
                        sortedStars.indexOf(a.id) - sortedStars.indexOf(b.id)
                );
            }
            return options;
        });

        const onSelected = (selectedStar) => {
            store.commit("fleets/SET_CREATE_FLEET_LOCATION", selectedStar);
        };

        return { options, focusSelectModel, onSelected };
    },
};
</script>

<template>
    <div class="label">
        <label for="fleetLocation">{{
            $t("fleets.new.locationLabel")
        }}</label>
    </div>
    <div class="input">
        <dropdown
            ref-id="fleetLocation"
            :options="options"
            @valuechanged="onSelected"
        />
    </div>
</template>

<style lang="scss" scoped>
.label {
    margin-bottom: 8px;
    flex-basis: 100%;
}
.input :deep(.select) {
    width: 100%;
    max-width: 100%;
}
</style>
