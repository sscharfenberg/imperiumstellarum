<script>
/******************************************************************************
 * PageComponent: EncounterDetailsTurnsNav
 *****************************************************************************/
import { useStore } from "vuex";
export default {
    name: "EncounterDetailsTurnsNav",
    props: {
        turns: Array,
        currentTurn: Number,
    },
    setup() {
        const store = useStore();
        const onTurnClick = (turn) => store.commit("encounters/SET_TURN", turn);
        return { onTurnClick };
    },
};
</script>

<template>
    <nav class="turns">
        <span class="turns__label">{{
            $t("encounters.details.turns.label", { num: turns.length - 1 })
        }}</span>
        <button
            v-for="turn in turns"
            :key="turn"
            class="turns__turn"
            @click="onTurnClick(turn)"
            :class="{ 'turns__turn--active': turn === currentTurn }"
        >
            {{ turn }}
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.turns {
    display: flex;
    flex-wrap: wrap;

    padding: 8px;
    border-top: 1px solid transparent;
    gap: 2px;

    @include themed() {
        border-top-color: t("b-christine");
    }

    @include respond-to("medium") {
        padding: 16px;
        margin-top: 8px;
        gap: 4px;
    }

    &__label {
        display: flex;
        align-items: center;

        margin-right: 4px;

        @include respond-to("medium") {
            margin-right: 8px;
        }
    }

    &__turn {
        padding: 2px 4px;
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-deep");
            color: t("t-light");
            border-color: t("g-raven");
        }

        @include respond-to("medium") {
            padding: 4px 8px;
        }

        &--active {
            @include themed() {
                background-color: t("b-viking");
                color: t("t-dark");
            }
        }

        &:hover:not(.turns__turn--active) {
            cursor: pointer;

            @include themed() {
                background: t("g-bunker");
                color: t("b-viking");
                border-color: t("g-raven");
            }
        }
    }
}
</style>
