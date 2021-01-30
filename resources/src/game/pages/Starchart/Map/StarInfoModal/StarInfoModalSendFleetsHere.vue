<script>
/******************************************************************************
 * PageComponent: StarInfoModalSendFleetsHere
 *****************************************************************************/
import { computed, onBeforeMount, ref } from "vue";
import { useStore } from "vuex";
import FleetShipSummary from "Components/Fleet/FleetShipSummary";
import Icon from "Components/Icon/Icon";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "StarInfoModalSendFleetsHere",
    props: {
        starId: String,
    },
    components: { SubHeadline, Icon, FleetShipSummary },
    setup(props) {
        const store = useStore();
        const availableFleets = computed(() =>
            store.state.starchart.fleets.filter(
                (f) => f.starId !== props.starId && f.starId && f.ftl
            )
        );
        const stars = computed(() => store.state.starchart.stars);
        const ships = computed(() => store.state.starchart.ships);
        const selectedMoveId = computed(
            () => store.state.starchart.starMoveFleetHereId
        );
        const onSelect = (id) => {
            store.commit("starchart/SET_STAR_MOVE_FLEET_HERE", id);
        };
        const travelTimes = ref([]);

        onBeforeMount(() => {
            store.commit("starchart/SET_STAR_MOVE_FLEET_HERE", "");
            const gameId = document.getElementById("game").dataset.gameId;
            window.axios
                .post(`/api/game/${gameId}/starchart/travelTime`, {
                    destinationId: props.starId,
                    fromIds: availableFleets.value.map((f) => f.starId),
                })
                .then((response) => {
                    if (response.status === 200 && response.data.length) {
                        travelTimes.value = response.data;
                    }
                })
                .catch((e) => {
                    console.error(e);
                });
        });
        return {
            availableFleets,
            ships,
            stars,
            selectedMoveId,
            onSelect,
            travelTimes,
        };
    },
};
</script>

<template>
    <div v-if="availableFleets.length" class="send-here">
        <sub-headline :headline="$t('starchart.star.sendHere.headline')" />
        <button
            class="send-here__fleet"
            :class="{ active: selectedMoveId === fleet.id }"
            v-for="fleet in availableFleets"
            :key="fleet.id"
            @click="onSelect(fleet.id)"
        >
            <icon class="send-here__fleet-icon" name="fleets" />
            <span class="send-here__fleet-name">{{ fleet.name }}</span>
            <fleet-ship-summary
                v-if="ships.filter((s) => s.fleetId === fleet.id).length"
                :ships="ships.filter((s) => s.fleetId === fleet.id)"
            />
            <span class="send-here__fleet-travel">
                <span
                    class="send-here__fleet-travel-time"
                    v-if="travelTimes.length"
                    :title="$t('starchart.star.sendHere.travelTime')"
                    :aria-label="$t('starchart.star.sendHere.travelTime')"
                >
                    <icon name="res-turns" />
                    {{
                        travelTimes.find((t) => t.fromId === fleet.starId)
                            .travelTime
                    }}
                </span>
                <span class="send-here__fleet-location">
                    <icon name="location" />
                    {{ stars.find((s) => s.id === fleet.starId).x }}/{{
                        stars.find((s) => s.id === fleet.starId).y
                    }}
                </span>
            </span>
        </button>
    </div>
</template>

<style lang="scss" scoped>
.send-here {
    margin: 16px 0 0 0;

    &__fleet {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        width: 100%;
        padding: 4px;
        border: 3px solid transparent;
        margin: 0 0 4px 0;

        outline: 0;
        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear,
            border-color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-deep");
            color: t("t-light");
            border-color: t("g-abbey");
        }

        &:hover {
            @include themed() {
                background-color: t("g-bunker");
                color: t("t-light");
                border-color: t("g-ebony");
            }
        }

        &:last-of-type {
            margin-bottom: 0;
        }

        .summary {
            margin-left: auto;
        }

        &.active {
            @include themed() {
                background-color: t("g-iron");
                color: t("t-dark");
                border-color: t("b-christine");
            }

            .icon {
                @include themed() {
                    color: t("t-dark");
                }
            }

            .send-here__fleet-location {
                @include themed() {
                    background: t("g-fog");
                }
            }
        }
    }

    &__fleet-icon {
        margin-right: 8px;

        @include themed() {
            color: t("b-christine");
        }
    }

    &__fleet-name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__fleet-travel {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin: 4px 0 0 0;
        flex: 0 0 100%;
    }

    &__fleet-travel-time {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 8px;
        }
    }

    &__fleet-location {
        display: flex;
        align-items: center;

        padding: 4px 8px;
        margin-left: auto;

        .icon {
            margin-right: 8px;
        }

        @include themed() {
            background: t("g-raven");
        }
    }
}
</style>
