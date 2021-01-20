<script>
/******************************************************************************
 * PageComponent: FleetMoveAnySystem
 *****************************************************************************/
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
export default {
    name: "FleetMoveOtherSystem",
    props: {
        fleetId: String,
    },
    components: { GameButton },
    setup(props) {
        const store = useStore();
        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );

        const requesting = computed(() => store.state.fleets.requesting);
        const input = ref(null);
        const coordX = computed({
            get: () => store.state.fleets.moveCoordX,
            set: (value) =>
                store.commit("fleets/SET_DESTINATION_COORD_X", value),
        });
        const coordY = computed({
            get: () => store.state.fleets.moveCoordY,
            set: (value) =>
                store.commit("fleets/SET_DESTINATION_COORD_Y", value),
        });

        const onSubmit = () => {
            store.dispatch("fleets/FIND_DESTINATION_BY_COORDS", {
                x: coordX.value,
                y: coordY.value,
                fromId: fleet.value.starId,
            });
        };

        onMounted(() => {
            input.value?.focus();
        });
        return { requesting, fleet, input, coordX, coordY, onSubmit };
    },
};
</script>

<template>
    <div class="find-coordinates">
        <label class="find-coordinates__coord" for="coord-x">
            x
            <input
                class="form-control"
                type="number"
                id="coord-x"
                ref="input"
                v-model="coordX"
                :readonly="requesting"
                @keyup.enter="onSubmit"
            />
        </label>
        <label class="find-coordinates__coord" for="coord-y">
            y
            <input
                class="form-control"
                type="number"
                id="coord-y"
                v-model="coordY"
                :readonly="requesting"
                @keyup.enter="onSubmit"
            />
        </label>
        <game-button
            icon-name="search"
            :text-string="$t('fleets.move.coordinates.submit')"
            @click="onSubmit"
            :disabled="!coordX.length || !coordY.length"
            :loading="requesting"
        />
    </div>
</template>

<style lang="scss" scoped>
.find-coordinates {
    display: flex;

    padding: 0;
    margin: 0;
    gap: 8px;

    list-style: none;

    &__coord {
        display: flex;
        align-items: center;

        padding: 0;

        input[type="number"] {
            width: 60px;
            margin-left: 4px;

            @include respond-to("medium") {
                width: 100px;
            }
        }
    }

    .btn {
        height: 42px;

        @include respond-to("medium") {
            flex-grow: 1;
        }
    }
}
</style>
