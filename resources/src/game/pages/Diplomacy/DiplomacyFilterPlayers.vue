<script>
/******************************************************************************
 * PageComponent: DiplomacyFilterPlayers
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AppCheckbox from "Components/Checkbox/AppCheckbox";
import GameButton from "Components/Button/GameButton";
export default {
    name: "PageDiplomacy",
    components: { AppCheckbox, GameButton },
    setup() {
        const store = useStore();
        const showAllies = computed({
            get: () => store.state.diplomacy.showAllies,
            set: (value) => store.commit("diplomacy/SET_SHOW_ALLIES", value),
        });
        const showNeutrals = computed({
            get: () => store.state.diplomacy.showNeutrals,
            set: (value) => store.commit("diplomacy/SET_SHOW_NEUTRALS", value),
        });
        const showHostiles = computed({
            get: () => store.state.diplomacy.showHostiles,
            set: (value) => store.commit("diplomacy/SET_SHOW_HOSTILES", value),
        });
        const sort = computed({
            get: () => store.state.diplomacy.sort,
            set: (value) => store.commit("diplomacy/SET_SORT_DIRECTION", value),
        });
        const ticker = computed({
            get: () => store.state.diplomacy.filterByTicker,
            set: (value) =>
                store.commit(
                    "diplomacy/SET_FILTER_TICKER",
                    value.toUpperCase()
                ),
        });
        return {
            showAllies,
            showHostiles,
            showNeutrals,
            sort,
            ticker,
        };
    },
};
</script>

<template>
    <nav class="filters">
        <label class="filters__ticker" for="filterTicker">
            {{ $t("diplomacy.list.filterTicker") }}
        </label>
        <input
            class="filters__ticker form-control"
            type="text"
            id="filterTicker"
            v-model="ticker"
        />
        <game-button
            class="filters__sort"
            icon-name="expand_less"
            :primary="sort === 'asc'"
            @click="sort = 'asc'"
            :title="$t('diplomacy.list.sort.asc')"
            :aria-label="$t('diplomacy.list.sort.asc')"
        />
        <game-button
            class="filters__sort"
            icon-name="expand_more"
            :primary="sort === 'desc'"
            @click="sort = 'desc'"
            :title="$t('diplomacy.list.sort.desc')"
            :aria-label="$t('diplomacy.list.sort.desc')"
        />
        <app-checkbox
            class="filters__relation"
            :checked-initially="showAllies"
            @checked="showAllies = true"
            @unchecked="showAllies = false"
        >
            {{ $t("diplomacy.list.filter.2") }}
        </app-checkbox>
        <app-checkbox
            class="filters__relation"
            :checked-initially="showNeutrals"
            @checked="showNeutrals = true"
            @unchecked="showNeutrals = false"
        >
            {{ $t("diplomacy.list.filter.1") }}
        </app-checkbox>
        <app-checkbox
            class="filters__relation"
            :checked-initially="showHostiles"
            @checked="showHostiles = true"
            @unchecked="showHostiles = false"
        >
            {{ $t("diplomacy.list.filter.0") }}
        </app-checkbox>
    </nav>
</template>

<style lang="scss" scoped>
.filters {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 8px 8px 4px 8px;
    border-bottom: 1px solid transparent;

    @include themed() {
        border-color: t("g-deep");
    }

    @include respond-to("medium") {
        padding: 16px 16px 8px 16px;
    }

    &__relation {
        margin: 0 8px 4px 0;

        @include respond-to("medium") {
            margin: 0 16px 8px 0;
        }

        &:first-of-type {
            margin-left: auto;
        }

        &:last-of-type {
            margin-right: 0;
        }
    }

    &__sort {
        margin: 0 8px 4px 0;

        @include respond-to("medium") {
            margin: 0 16px 8px 0;
        }
    }

    &__ticker {
        display: flex;
        align-items: center;

        margin: 0 4px 4px 0;

        @include respond-to("medium") {
            margin: 0 16px 8px 0;
        }

        input[type="text"] {
            height: 32px;
            margin-left: 8px;

            @include respond-to("medium") {
                margin-left: 16px;
            }
        }

        &.form-control {
            flex-grow: 0;
        }
    }
}
</style>
