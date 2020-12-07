<script>
/******************************************************************************
 * PageComponent: FocussableStars
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import DropDown from "Components/DropDown/DropDown";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FocussableStars",
    props: {
        dimensions: Number,
    },
    components: { DropDown, GameButton },
    setup(props) {
        const store = useStore();
        const stars = computed(() => store.state.starchart.playerStars);
        const sortOrder = computed(() => store.state.empire.starsSorted);
        const focusCoordX = ref("");
        const focusCoordY = ref("");

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

        /**
         * @function handle coord input
         * @param {KeyboardEvent} ev
         * @param {String} value
         */
        const handleInput = (event, value) => {
            if (event.defaultPrevented) {
                return; // Should do nothing if the default action has been cancelled
            }
            if (event.key !== undefined) {
                if (isNaN(parseInt(value + event.key, 10)))
                    event.preventDefault(); // prevent non-number chars
                const newValue = parseInt(value + event.key, 10);
                if (newValue < 0 || newValue > props.dimensions - 1) {
                    event.preventDefault();
                }
            }
        };
        const handleInputX = (ev) => {
            handleInput(ev, focusCoordX.value);
        };
        const handleInputY = (ev) => {
            handleInput(ev, focusCoordY.value);
        };

        // focus the map, either from select option or values from input
        const handleSelected = (selectedOption) => {
            store.commit("starchart/FOCUS_COORDS", {
                x: selectedOption.x,
                y: selectedOption.y,
            });
        };
        const handleSubmitCoords = () => {
            store.commit("starchart/FOCUS_COORDS", {
                x: focusCoordX.value,
                y: focusCoordY.value,
            });
        };

        return {
            options,
            handleSelected,
            handleInputX,
            handleInputY,
            handleSubmitCoords,
            focusCoordX,
            focusCoordY,
        };
    },
};
</script>

<template>
    <div class="focus">
        <nav class="col">
            <label for="focusStar">{{ $t("starchart.focus.stars") }}</label>
            <drop-down
                id="focusStar"
                :options="options"
                labeled-by="name"
                :place-holder="$t('common.dropdown.placeHolder')"
                @on-selected="handleSelected"
            />
        </nav>
        <nav class="col">
            <label for="focusX">{{ $t("starchart.focus.coords") }}</label>
            <input
                id="focusX"
                class="coord"
                type="number"
                v-model="focusCoordX"
                placeholder="x"
                min="0"
                :max="dimensions - 1"
                @keydown="handleInputX"
                @keyup.enter="handleSubmitCoords"
            />
            /
            <input
                class="coord"
                type="number"
                v-model="focusCoordY"
                placeholder="y"
                min="0"
                :max="dimensions - 1"
                @keydown="handleInputY"
                @keyup.enter="handleSubmitCoords"
            />
            <game-button
                :text-string="$t('starchart.focus.go')"
                icon-name="location"
                @click="handleSubmitCoords"
            />
        </nav>
    </div>
</template>

<style lang="scss" scoped>
.focus {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 8px;

    @include respond-to("medium") {
        padding: 16px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }

    .col {
        display: flex;
        align-items: center;

        margin-bottom: 8px;
        flex-basis: 100%;

        @include respond-to("medium") {
            margin-bottom: 0;
            flex-basis: auto;
        }

        .vue-select {
            margin: 0 0 0 auto;

            @include respond-to("medium") {
                margin: 0 0 0 16px;
            }
        }

        &:nth-child(2) {
            margin: 0 0 0 auto;
        }
    }

    .coord {
        width: 60px;
        padding: 7px;
        border: 1px solid transparent;

        &:focus,
        &:active {
            outline: 0;
        }

        &:first-of-type {
            margin-left: 8px;

            @include respond-to("medium") {
                margin-left: 16px;
            }
        }

        &:last-of-type {
            margin-right: 8px;

            @include respond-to("medium") {
                margin-right: 16px;
            }
        }

        @include themed() {
            background-color: t("g-raven");
            color: t("t-bright");
            border-color: t("g-abbey");
        }
    }
}
</style>
