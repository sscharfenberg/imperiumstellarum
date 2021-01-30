<script>
/******************************************************************************
 * PageComponent: StarInfoModalFleets
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import router from "@/game/router";
import FleetShipSummary from "Components/Fleet/FleetShipSummary";
import Icon from "Components/Icon/Icon";
export default {
    name: "StarInfoModalFleets",
    props: {
        fleets: Array, // of fleets
        starId: String,
    },
    components: { Icon, FleetShipSummary },
    setup(props) {
        const store = useStore();
        const ships = computed(() => store.state.starchart.ships);

        const movements = computed(() =>
            store.state.starchart.fleetMovements.filter(
                (m) => m.destinationId === props.starId
            )
        );

        const onClick = (fleetId) => {
            const collapsibleExpandedIds = store.state.collapsibleExpandedIds;
            if (!collapsibleExpandedIds.includes(fleetId)) {
                store.commit("TOGGLE_COLLAPSIBLE", fleetId);
            }
            router.push({ name: "Fleets" });
        };
        return { ships, movements, onClick };
    },
};
</script>

<template>
    <div class="fleets">
        <button
            class="fleet"
            v-for="fleet in fleets"
            :key="fleet.id"
            @click="onClick(fleet.id)"
            :title="$t('starchart.star.fleetsLabel')"
            :aria-label="$t('starchart.star.fleetsLabel')"
        >
            <icon class="fleet__icon" name="fleets" :size="1" />
            <span class="fleet__name">{{ fleet.name }}</span>
            <icon
                class="fleet__ftl"
                name="tech-ftl"
                v-if="fleet.ftl"
                :size="1"
            />
            <fleet-ship-summary
                v-if="ships.filter((s) => s.fleetId === fleet.id).length"
                :ships="ships.filter((s) => s.fleetId === fleet.id)"
            />
            <span
                v-if="movements.find((m) => m.fleetId)"
                class="fleet__movement"
            >
                <icon name="res-turns" :size="1" />
                {{ movements.find((m) => m.fleetId).untilArrival }}
            </span>
        </button>
    </div>
</template>

<style lang="scss" scoped>
.fleets {
    display: grid;

    padding: 0;
    margin: 0;
    grid-gap: 4px;

    list-style: none;
}

.fleet {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 4px;
    border: 2px solid transparent;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-deep");
        color: t("t-light");
        border-color: t("g-bunker");
    }

    &:hover {
        @include themed() {
            background-color: t("g-bunker");
            color: t("t-light");
            border-color: t("g-ebony");
        }
    }

    &__icon {
        margin-right: 8px;

        @include themed() {
            color: t("b-christine");
        }
    }

    &__name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__ftl {
        margin-left: auto;
    }

    .summary {
        margin: 4px 0 0 0;
        flex: 0 0 100%;
    }

    &__movement {
        display: flex;
        align-items: center;

        margin: 4px 0 0 0;
        flex: 0 0 100%;

        .icon {
            margin-right: 4px;
        }
    }
}
</style>
