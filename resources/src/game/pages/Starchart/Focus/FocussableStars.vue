<script>
/******************************************************************************
 * PageComponent: FocussableStars
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import DropDown from "Components/DropDown/DropDown";
export default {
    name: "FocussableStars",
    components: { DropDown },
    setup() {
        const store = useStore();
        const stars = computed(() => store.state.starchart.playerStars);
        const sortOrder = computed(() => store.state.empire.starsSorted);

        /**
         * @function get options from store, reformat them and sort them.
         * @type {ComputedRef<{name: string, x: *, y: *, id: *}[]>}
         */
        const options = computed(() => {
            const unsortedStars = store.state.starchart.playerStars.map(
                (star) => star.id
            );
            let options = stars.value.map((star) => {
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

        const handleSelected = (selectedOption) => {
            console.log("child was selected ", selectedOption);
        };
        return {
            options,
            handleSelected,
        };
    },
};
</script>

<template>
    <div class="focus">
        <nav>
            <label for="focusStar">{{
                $t("starchart.focus.stars.label")
            }}</label>
            <drop-down
                id="focusStar"
                :options="options"
                labeled-by="name"
                :place-holder="$t('common.dropdown.placeHolder')"
                @on-selected="handleSelected"
            />
        </nav>
    </div>
</template>

<style lang="scss" scoped>
.focus {
    padding: 8px;
    margin-bottom: 8px;

    @include respond-to("medium") {
        padding: 16px;
        margin-bottom: 16px;
    }

    @include themed() {
        background-color: t("g-bunker");
    }

    nav {
        display: flex;
        align-items: center;
        //justify-content: space-between;

        .vue-select {
            margin: 0 8px;

            @include respond-to("medium") {
                margin: 0 16px;
            }
        }
    }
}
</style>
