<script>
/******************************************************************************
 * Page: ListForeignFleets
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
import ForeignFleetStar from "Pages/Fleets/ForeignFleets/ForeignFleetStar";
export default {
    name: "ListForeignFleets",
    props: {
        fleets: Array,
    },
    components: { ForeignFleetStar, Icon },
    setup() {
        const i18n = useI18n();
        const store = useStore();

        /**
         * @function determines the label of the fleet icon
         * @param {Number} rel
         * @returns {string}
         */
        const iconLabel = (rel) => {
            if (rel === 0) return i18n.t("diplomacy.empireStatus.0");
            if (rel === 2) return i18n.t("diplomacy.empireStatus.2");
            return i18n.t("diplomacy.empireStatus.1");
        };

        /**
         * @function check if the star is one of the player's own stars
         * @param {String} starId
         * @returns {boolean}
         */
        const ownStar = (starId) => {
            const playerId = store.state.empireId;
            return store.state.fleets.stars
                .filter((s) => s.ownerId === playerId) // only own stars (ownerId === empireId)
                .map((s) => s.id) // only the IDs
                .includes(starId); // does the array of IDs contain the star in question?
        };

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

        return { iconLabel, ownStar, fleetHullTypes };
    },
};
</script>

<template>
    <ul class="foreign">
        <li v-for="fleet in fleets" :key="fleet.id" class="foreign__fleet">
            <span
                class="foreign__fleet-icon"
                :title="iconLabel(fleet.playerRelation)"
                :aria-label="iconLabel(fleet.playerRelation)"
            >
                <icon
                    name="fleets"
                    :class="{
                        'foreign__fleet--hostile': fleet.playerRelation === 0,
                        'foreign__fleet--allied': fleet.playerRelation === 2,
                    }"
                />
            </span>
            <span
                class="foreign__player-ticker"
                :style="{ '--playerColour': '#' + fleet.playerColour }"
                >[{{ fleet.playerTicker }}]</span
            >
            <span class="foreign__fleet-name">{{ fleet.name }}</span>
            <ul
                class="foreign__ship-summary"
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
            <foreign-fleet-star
                :coord-x="fleet.coordX"
                :coord-y="fleet.coordY"
                :star-name="fleet.starName"
                :own-star="ownStar(fleet.starId)"
            />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.foreign {
    padding: 0;
    margin: 0;

    list-style: none;

    &__fleet {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        padding: 4px;
        gap: 4px;

        @include themed() {
            background-color: t("g-sunken");
        }

        @include respond-to("medium") {
            padding: 4px 8px;
            gap: 16px;
        }

        &:not(:last-child) {
            margin-bottom: 4px;
        }

        &--hostile {
            @include themed() {
                color: t("s-error");
            }
        }

        &--allied {
            @include themed() {
                color: t("s-success");
            }
        }
    }

    &__player-ticker {
        padding: 5px;

        background-color: var(--playerColour);

        font-weight: 600;
        text-align: center;

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
            letter-spacing: 2px;
        }
    }

    &__fleet-name {
        @include themed() {
            color: t("b-viking");
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
