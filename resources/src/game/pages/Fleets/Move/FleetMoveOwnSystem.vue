<script>
/******************************************************************************
 * PageComponent: FleetMoveOwnSystem
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetMoveOwnSystem",
    props: {
        fleetId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const stars = computed(() =>
            store.state.fleets.stars.filter(
                (s) => s.ownerId === store.state.empireId
            )
        );
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const destinationId = computed(
            () => store.state.fleets.moveDestinationStarId
        );
        const onClick = (id) => {
            store.dispatch("fleets/GET_OWN_SYSTEMS", {
                fromId: fleet.value.starId,
                toId: id,
            });

            //store.commit("fleets/SET_DESTINATION_STAR_ID", id);
            //store.commit(
            //    "fleets/SET_DESTINATION_STAR",
            //    store.getters["fleets/starById"](destinationId.value)
            //);
            //store.commit("fleets/SET_DESTINATION_OWNER", {});
            // TODO: change to action that gets travel time.
        };

        return { stars, fleet, destinationId, onClick };
    },
};
</script>

<template>
    <nav class="destination">
        <button
            class="destination__system"
            :class="{
                'destination__system--active': destinationId === star.id,
            }"
            v-for="star in stars"
            :key="star.id"
            :disabled="star.id === fleet.starId"
            @click="onClick(star.id)"
        >
            <span class="destination__system-name">{{ star.name }}</span>
            <icon name="location" :size="1" />
            <span class="destination__system-location"
                >{{ star.x }}/{{ star.y }}</span
            >
        </button>
    </nav>
</template>

<style lang="scss" scoped>
.destination {
    display: flex;
    flex-wrap: wrap;

    gap: 4px;

    &__label {
        margin: 0 0 8px 0;

        h1 {
            margin: 0;

            font-weight: 300;
            line-height: 1;
        }
    }

    &__system {
        display: flex;
        align-items: center;

        max-width: 100%;
        padding: 4px 8px;
        border: 1px solid transparent;

        outline: 0;
        cursor: pointer;

        font-weight: 300;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear,
            border-color map-get($animation-speeds, "fast") linear;

        @include respond-to("medium") {
            max-width: calc(50% - 2px);
        }

        @include themed() {
            background: t("g-raven");
            color: t("t-light");
            border-color: t("g-asher");
        }

        .icon {
            margin: 0 4px;

            @include respond-to("medium") {
                margin: 0 8px;
            }
        }

        &:hover:not([disabled]):not(.destination__system--active),
        &:focus:not([disabled]):not(.destination__system--active),
        &:active:not([disabled]):not(.destination__system--active) {
            @include themed() {
                background: t("g-bunker");
                color: t("b-viking");
                border-color: t("g-raven");
            }
        }

        &--active {
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
    }

    &__system-name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }
}
</style>
