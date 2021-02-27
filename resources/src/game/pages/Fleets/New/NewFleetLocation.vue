<script>
/******************************************************************************
 * PageComponent: NewFleetLocation
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import VueSelect from "vue-next-select";
export default {
    name: "NewFleetLocation",
    components: { VueSelect },
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
                    id: star.id,
                    name: `${star.name} (${star.x}/${star.y})`,
                    x: star.x,
                    y: star.y,
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
            store.commit("fleets/SET_CREATE_FLEET_LOCATION", selectedStar.id);
        };

        return { options, focusSelectModel, onSelected };
    },
};
</script>

<template>
    <div class="form-row">
        <div class="label">
            <label for="fleetLocation">{{
                $t("fleets.new.locationLabel")
            }}</label>
        </div>
        <div class="input">
            <vue-select
                v-model="focusSelectModel"
                :options="options"
                :close-on-select="true"
                :track-by="(option) => option.name"
                label-by="name"
                :place-holder="$t('common.dropdown.placeHolder')"
                @selected="onSelected"
            />
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}
.form-row .label {
    margin-bottom: 8px;
    flex-basis: 100%;
}
.form-row .input {
    flex-basis: 100%;
}
.input .vue-select {
    width: 100%;
    padding: 9px;

    > ul.vue-dropdown {
        top: 54px !important;
    }
}
</style>
