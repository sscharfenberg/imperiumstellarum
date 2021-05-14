<script>
/******************************************************************************
 * Page: EncounterTape
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import GameButton from "Components/Button/GameButton";
export default {
    name: "EncounterTape",
    components: { GameButton },
    setup() {
        const store = useStore();
        const timer = ref(null);
        const playing = computed(() => store.state.encounters.playing);
        const currentTurn = computed(() => store.state.encounters.renderTurn);
        const maxTurn = computed(
            () => store.state.encounters.encounterDetails.turns.length - 1
        );

        /**
         * Action buttons
         */
        const onPlayClicked = () => {
            startTape();
        };
        const onPauseClicked = () => {
            stopTape();
        };
        const onStopClicked = () => {
            store.commit("encounters/SET_TURN", 0);
            stopTape();
        };

        /**
         * Timer functions
         */
        const startTape = () => {
            store.commit("encounters/SET_TAPE_PLAYING", true);
            timer.value = window.setInterval(() => {
                tapeForward();
            }, 1500);
        };
        const tapeForward = () => {
            if (currentTurn.value < maxTurn.value) {
                store.commit("encounters/SET_TURN", currentTurn.value + 1);
            } else {
                stopTape(); // if tape is at the end, stop and rewind.
            }
        };
        const stopTape = () => {
            store.commit("encounters/SET_TAPE_PLAYING", false);
            window.clearInterval(timer.value);
        };

        return {
            playing,
            currentTurn,
            maxTurn,
            onPlayClicked,
            onPauseClicked,
            onStopClicked,
        };
    },
};
</script>

<template>
    <nav class="encounter-tape">
        <game-button
            text-string="Play"
            icon-name="play"
            :primary="playing"
            @click="onPlayClicked"
            :disabled="currentTurn === maxTurn"
        />
        <game-button
            text-string="Pause"
            icon-name="pause"
            :primary="!playing && currentTurn !== 0"
            @click="onPauseClicked"
        />
        <game-button
            text-string="Stop"
            icon-name="stop"
            @click="onStopClicked"
            :primary="!playing && currentTurn === 0"
        />
    </nav>
</template>

<style lang="scss" scoped>
.encounter-tape {
    display: flex;

    padding: 0 8px 8px 8px;
    gap: 2px;

    @include respond-to("medium") {
        padding: 0 16px 16px 16px;
        gap: 4px;
    }
}
.active {
    @include themed() {
        background-color: t("b-viking");
        color: t("t-dark");
    }
}
</style>
