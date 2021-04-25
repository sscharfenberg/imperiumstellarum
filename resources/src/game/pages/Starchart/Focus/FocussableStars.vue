<script>
/******************************************************************************
 * PageComponent: FocussableStars
 *****************************************************************************/
import { useStore } from "vuex";
import { ref } from "vue";
import FocussableStarsList from "./FocussableStarsList";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FocussableStars",
    props: {
        dimensions: Number,
    },
    components: { FocussableStarsList, GameButton },
    setup(props) {
        const store = useStore();
        const focusCoordX = ref("");
        const focusCoordY = ref("");
        const focusSelectModel = ref(null);

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

        const handleSubmitCoords = () => {
            store.commit("starchart/FOCUS_COORDS", {
                x: focusCoordX.value,
                y: focusCoordY.value,
            });
        };

        return {
            focusSelectModel,
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
        <focussable-stars-list />
        <nav class="focus-coords">
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
    flex-wrap: wrap;

    padding: 8px;

    @include respond-to("medium") {
        align-items: flex-start;
        flex-wrap: nowrap;

        padding: 16px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }

    .focus-coords {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        @include respond-to("medium") {
            justify-content: flex-end;
        }

        label {
            display: block;

            margin-bottom: 4px;
            flex-basis: 100%;

            @include respond-to("medium") {
                text-align: right;
            }
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
