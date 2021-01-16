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
            return stationary.value
                ? store.getters["fleets/starById"](holder.value.starId)
                : "transit"; // TODO
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
        return {
            holder,
            stationary,
            label,
            coordsText,
        };
    },
};
</script>

<template>
    <aside
        class="location"
        v-if="stationary"
        :title="label"
        :aria-label="label"
    >
        <icon class="location-icon" name="location" />
        <span>{{ coordsText }}</span>
    </aside>
    <aside class="location" v-if="!stationary">TODO: in transit</aside>
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

        margin-right: 10px;

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
</style>
