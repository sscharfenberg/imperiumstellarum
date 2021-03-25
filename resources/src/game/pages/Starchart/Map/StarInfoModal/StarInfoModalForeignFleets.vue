<script>
/******************************************************************************
 * PageComponent: StarInfoModalForeignFleets
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "StarInfoModalForeignFleets",
    props: {
        starId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const fleets = computed(() => store.state.starchart.foreignFleets);
        const foreignFleetsAtStar = computed(() =>
            fleets.value.filter((f) => f.starId === props.starId)
        );

        /**
         * @function get a fleets hullTypes from xhr response. Since the xhr is an object with the number of
         * ships for each hullType, we can't use the FleetSummary Component (Components/Fleet/FleetSummary)
         * The xhr is different since we don't want to send information to the client that is not displayed.
         * @param {Object} ships
         * @returns {Array}
         */
        const fleetHullTypes = (ships) =>
            Object.keys(ships)
                .filter((ht) => ships[ht] !== 0)
                .reverse();

        return { foreignFleetsAtStar, fleetHullTypes };
    },
};
</script>

<template>
    <li class="text-left" v-if="foreignFleetsAtStar.length">
        {{ $t("starchart.star.foreignFleetsLabel") }}
    </li>
    <li class="text-left" v-if="foreignFleetsAtStar.length">
        <ul class="foreign-fleets">
            <li
                v-for="fleet in foreignFleetsAtStar"
                :key="fleet.id"
                class="foreign-fleets__fleet"
                :class="{
                    hostile: fleet.playerRelation === 0,
                    allied: fleet.playerRelation === 2,
                    neutral: fleet.playerRelation === 1,
                }"
            >
                <icon name="fleets" :size="1" />
                <span
                    class="foreign-fleets__ticker"
                    :style="{ '--playerColour': '#' + fleet.playerColour }"
                    >[{{ fleet.playerTicker }}]</span
                >
                <span class="foreign-fleets__name">{{ fleet.name }}</span>
                <ul
                    class="foreign-fleets__ship-summary"
                    :title="$t('fleets.other.summary')"
                    :aria-label="$t('fleets.other.summary')"
                >
                    <li
                        v-for="hullType in fleetHullTypes(fleet.numShips)"
                        :key="hullType"
                    >
                        {{ fleet.numShips[hullType] }}
                        <icon :name="`hull-${hullType}`" />
                    </li>
                </ul>
            </li>
        </ul>
    </li>
</template>

<style lang="scss" scoped>
.foreign-fleets {
    display: flex;
    flex-direction: column;

    padding: 0;
    margin: 0;
    gap: 4px;

    list-style: none;

    &__fleet {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        padding: 4px;
        border: 2px solid transparent;
        gap: 4px;

        &.hostile {
            @include themed() {
                border-color: t("s-error");
            }

            .icon {
                @include themed() {
                    color: t("s-error");
                }
            }
        }

        &.allied {
            @include themed() {
                border-color: t("s-success");
            }

            .icon {
                @include themed() {
                    color: t("s-success");
                }
            }
        }

        &.neutral {
            @include themed() {
                border-color: t("g-bunker");
            }

            .icon {
                @include themed() {
                    color: t("g-bunker");
                }
            }
        }
    }

    &__ticker {
        padding: 2px 4px;

        background: var(--playerColour);

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
            letter-spacing: 2px;
        }
    }

    &__ship-summary {
        display: inline-flex;
        align-items: center;

        padding: 0;
        margin: 0 0 0 0;
        gap: 12px;

        list-style: none;

        > li {
            display: flex;
            align-items: center;

            line-height: 1;
        }

        .icon {
            margin-left: 8px;
        }
    }
}
</style>
