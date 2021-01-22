<script>
/******************************************************************************
 * PageComponent: ShowShipHolderLocation
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowShipHolderLocation",
    props: {
        holderId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const holder = computed(() => {
            const fleet = store.getters["fleets/fleetById"](props.holderId);
            const shipyard = store.getters["fleets/shipyardById"](
                props.holderId
            );
            return fleet && fleet.id ? fleet : shipyard;
        });
        const stationary = computed(() => holder.value.starId);
        const locationStar = computed(() => {
            if (stationary.value)
                return store.getters["fleets/starById"](holder.value.starId);
            return store.getters["fleets/fleetMovementByFleetId"](
                props.holderId
            );
        });
        const label = computed(() => {
            const star = {
                name: locationStar.value.name,
                x: locationStar.value.x,
                y: locationStar.value.y,
            };
            return stationary.value
                ? i18n.t("fleets.active.location.at", star)
                : i18n.t("fleets.active.location.transit", star);
        });
        const coordsText = computed(
            () => `${locationStar.value.x}/${locationStar.value.y}`
        );
        const travelTime = computed(() =>
            stationary.value ? 0 : locationStar.value.untilArrival
        );
        return {
            holder,
            stationary,
            label,
            coordsText,
            travelTime,
        };
    },
};
</script>

<template>
    <aside class="location" :title="label" :aria-label="label">
        <icon v-if="stationary" class="location-icon" name="location" />
        <icon v-if="!stationary" class="location-icon" name="transit" />
        <span>{{ coordsText }}</span>
        <span v-if="!stationary" class="travel-time">
            <icon name="res-turns" />
            {{ travelTime }}
        </span>
    </aside>
</template>

<style lang="scss" scoped>
.location {
    display: flex;
    align-items: center;

    padding: 5px;

    transition: background-color map-get($animation-speeds, "fast") linear,
        color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-bunker");
    }

    > .location-icon {
        display: none;

        margin-right: 4px;

        @include themed() {
            color: darken(t("t-subdued"), 25%);
        }

        @include respond-to("medium") {
            display: block;
        }
    }

    > span {
        min-width: 50px;

        text-align: center;

        @include themed() {
            color: t("t-subdued");
        }
    }
}
.travel-time {
    display: flex;
    align-items: center;

    .icon {
        margin: 0 4px;
    }
}
</style>
