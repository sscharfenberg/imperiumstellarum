<script>
/******************************************************************************
 * PageComponent: EncountersNavigation
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "EncountersNavigation",
    setup() {
        const store = useStore();
        const pageIndex = computed({
            get: () => store.state.encounters.page,
            set: (value) => {
                store.commit("encounters/SET_PAGE", value);
            },
        });
        const raidsRaided = computed(
            () => store.getters["encounters/raidedRaids"].length
        );
        const raidsRaider = computed(
            () => store.getters["encounters/raiderRaids"].length
        );
        const encounters = computed(
            () => store.getters["encounters/sortedEncounters"].length
        );
        return {
            encounters,
            pageIndex,
            raidsRaided,
            raidsRaider,
        };
    },
};
</script>

<template>
    <nav class="encounters-nav">
        <button
            class="encounters-nav__link"
            @click="pageIndex = 0"
            :class="{ active: pageIndex === 0 }"
        >
            {{ $t("encounters.nav.encounters") }}
            <span v-if="encounters > 0" class="pill">
                {{ encounters }}
            </span>
        </button>
        <button
            class="encounters-nav__link"
            @click="pageIndex = 1"
            :class="{ active: pageIndex === 1 }"
        >
            {{ $t("encounters.nav.raidsRaider") }}
            <span v-if="raidsRaider > 0" class="pill">
                {{ raidsRaider }}
            </span>
        </button>
        <button
            class="encounters-nav__link"
            @click="pageIndex = 2"
            :class="{ active: pageIndex === 2 }"
        >
            {{ $t("encounters.nav.raidsRaided") }}
            <span v-if="raidsRaided > 0" class="pill">
                {{ raidsRaided }}
            </span>
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.encounters-nav {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));

    padding: 0;
    margin: 0;
    grid-gap: 8px;

    list-style: none;

    @include respond-to("medium") {
        grid-gap: 16px;
    }

    &__link {
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;

        padding: 8px;
        border: 2px solid transparent;

        outline: 0;
        cursor: pointer;

        font-size: 18px;
        font-weight: 300;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear,
            border-color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-sunken");
            color: t("t-light");
            border-color: t("g-raven");
        }

        @include respond-to("medium") {
            padding: 20px 16px;

            font-size: 24px;
        }

        &:hover {
            @include themed() {
                background-color: t("g-ebony");
                color: t("t-light");
                border-color: t("g-asher");
            }
        }

        &.active {
            @include themed() {
                color: t("b-christine");
                border-color: t("b-gorse");
            }
        }

        .icon {
            width: 32px;
            height: 32px;
            margin-right: 8px;

            @include respond-to("medium") {
                width: 48px;
                height: 48px;
                margin-right: 16px;
            }
        }
    }

    .pill {
        position: absolute;
        top: 0;
        right: 0;

        padding: 2px 4px;

        font-size: 16px;

        @include respond-to("medium") {
            padding: 4px 8px;
        }

        @include themed() {
            background-color: t("g-raven");
            color: t("t-light");
        }
    }
}
</style>
