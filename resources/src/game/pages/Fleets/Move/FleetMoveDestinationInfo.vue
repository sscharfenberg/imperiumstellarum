<script>
/******************************************************************************
 * PageComponent: FleetMoveDestinationInfo
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetMoveModal",
    components: { Icon },
    setup() {
        const store = useStore();
        const destinationStar = computed(
            () => store.state.fleets.destinationStar
        );
        const destinationOwner = computed(
            () => store.state.fleets.destinationOwner
        );
        const ownerColour = computed(() =>
            destinationOwner.value.colour
                ? `#${destinationOwner.value.colour}`
                : "transparent"
        );
        return { destinationStar, destinationOwner, ownerColour };
    },
};
</script>

<template>
    <ul class="stats">
        <li class="text-left">
            {{ $t("fleets.move.destination.systemLabel") }}
        </li>
        <li class="system">
            <span class="system__name">{{ destinationStar.name }}</span>
            <span class="system__location">
                <icon name="location" :size="1" />
                {{ destinationStar.x }}/{{ destinationStar.y }}
            </span>
        </li>
        <li class="text-left">
            {{ $t("fleets.move.systemOwnerLabel") }}
        </li>
        <li
            class="text-left owner"
            v-if="destinationOwner.name"
            :style="{ '--borderColour': ownerColour }"
        >
            [{{ destinationOwner.ticker }}] {{ destinationOwner.name }}
        </li>
        <li class="text-left" v-if="!destinationOwner.name">
            {{ $t("fleets.move.destination.noOwner") }}
        </li>
        <li class="text-left">
            {{ $t("fleets.move.destination.travelTime") }}
        </li>
        <li class="text-left">
            <icon name="res-turns" :size="1" />
            {{ destinationStar.travelTime }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.stats {
    grid-template-columns: 2fr 3fr;

    margin-bottom: 0;

    > li.owner {
        border-color: var(--borderColour);
    }
}
</style>
