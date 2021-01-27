<script>
/******************************************************************************
 * PageComponent: DiplomacyFilterPlayers
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import AppCheckbox from "Components/Checkbox/AppCheckbox";
export default {
    name: "PageDiplomacy",
    components: { AppCheckbox },
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
            ticker,
        };
    },
};
</script>

<template>
    <nav class="filters">
        <span class="filters__relation-label">{{
            $t("diplomacy.list.filter.show")
        }}</span>
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
        <label class="filters__ticker" for="filterTicker">
            {{ $t("diplomacy.list.filterTicker") }}
            <input
                class="form-control"
                type="text"
                id="filterTicker"
                v-model="ticker"
            />
        </label>
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

    &__relation-label {
        margin: 0 8px 4px 0;

        @include respond-to("medium") {
            margin: 0 16px 8px 0;
        }
    }

    &__relation {
        margin: 0 8px 4px 0;

        @include respond-to("medium") {
            margin: 0 16px 8px 0;
        }
    }

    &__ticker {
        margin: 0 0 4px 0;

        @include respond-to("medium") {
            margin: 0 0 8px auto;
        }

        input[type="text"] {
            height: 32px;
            margin-left: 8px;

            @include respond-to("medium") {
                margin-left: 16px;
            }
        }
    }
}
</style>
