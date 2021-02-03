<script>
/******************************************************************************
 * PageComponent: FleetMovePlayerSystem
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetMovePlayerSystem",
    props: {
        fleetId: String,
    },
    components: { GameButton, Icon },
    setup(props) {
        const store = useStore();
        const ticker = ref("");
        const requesting = computed(() => store.state.fleets.requesting);
        const rules = computed(() => window.rules.player.ticker);
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const onSubmit = () => {
            store.dispatch("fleets/AVAILABLE_SYSTEMS_BY_TICKER", {
                fromId: fleet.value.starId,
                ticker: ticker.value,
            });
        };
        const availableDestinations = computed(
            () => store.state.fleets.availableDestinations
        );
        const destinationStarId = computed(
            () => store.state.fleets.destinationStar.id
        );
        const onSelect = (id) => {
            store.commit("fleets/SET_DESTINATION_STAR_ID", id);
            store.commit(
                "fleets/SET_DESTINATION_STAR",
                availableDestinations.value.find((star) => star.id === id)
            );
            store.commit(
                "fleets/SET_DESTINATION_OWNER",
                store.state.fleets.destinationOwner
            );
        };
        return {
            fleet,
            ticker,
            rules,
            requesting,
            onSubmit,
            onSelect,
            availableDestinations,
            destinationStarId,
        };
    },
};
</script>

<template>
    <div class="find-by-players">
        <input
            class="form-control find-by-players__input"
            type="text"
            id="coord-x"
            ref="input"
            v-model="ticker"
            :readonly="requesting"
            :placeholder="$t('fleets.move.players.placeholder')"
            :aria-label="$t('fleets.move.players.label')"
            :maxlength="rules.max"
            @keyup="ticker = ticker.toUpperCase()"
            @keyup.enter="onSubmit"
        />
        <game-button
            icon-name="search"
            :text-string="$t('fleets.move.players.submit')"
            :disabled="ticker.length < rules.min"
            @click="onSubmit"
            :loading="requesting"
        />
    </div>
    <div class="available-stars">
        <button
            v-for="star in availableDestinations"
            :key="star.id"
            :disabled="star.id === fleet.starId"
            :class="{ active: destinationStarId === star.id }"
            @click="onSelect(star.id)"
        >
            <span>{{ star.name }}</span>
            <span class="location">
                <icon name="location" :size="1" />
                {{ star.x }}/{{ star.y }}
            </span>
        </button>
    </div>
</template>

<style lang="scss" scoped>
.find-by-players {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 0;
    margin: 0 0 8px 0;
    gap: 8px;

    list-style: none;

    @include respond-to("medium") {
        margin-bottom: 16px;
    }

    &__label {
        padding: 0;
    }

    .btn {
        height: 42px;
    }
}
.available-stars button {
    display: flex;
    align-items: center;
    justify-content: space-between;

    width: 100%;
    padding: 4px 8px;
    border: 1px solid transparent;
    margin: 0 0 2px 0;

    outline: 0;
    cursor: pointer;

    font-weight: 300;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include respond-to("medium") {
        margin-bottom: 4px;
    }

    @include themed() {
        background: t("g-raven");
        color: t("t-light");
        border-color: t("g-asher");
    }

    &:hover:not([disabled]):not(.active),
    &:focus:not([disabled]):not(.active),
    &:active:not([disabled]):not(.active) {
        @include themed() {
            background: t("g-bunker");
            color: t("b-viking");
            border-color: t("g-raven");
        }
    }

    &.active {
        @include themed() {
            background: t("g-iron");
            color: t("t-dark");
        }

        .icon {
            @include themed() {
                color: t("s-active");
            }
        }
    }

    &[disabled] {
        opacity: 0.3;

        cursor: not-allowed;
    }

    .location {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 4px;
        }
    }
}
</style>
