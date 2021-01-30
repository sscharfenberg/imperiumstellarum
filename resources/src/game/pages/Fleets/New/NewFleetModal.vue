<script>
/******************************************************************************
 * PageComponent: NewFleet
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
import NewFleetLocation from "./NewFleetLocation";
import NewFleetName from "./NewFleetName";
export default {
    name: "NewFleet",
    components: {
        GameButton,
        Modal,
        NewFleetLocation,
        NewFleetName,
    },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const requesting = computed(() => store.state.fleets.requesting);
        const availableFleets = computed(
            () =>
                store.state.fleets.maxFleets - store.state.fleets.fleets.length
        );
        const fleetLocation = computed(() => store.state.fleets.createLocation);
        const fleetName = computed(() => store.state.fleets.createName);
        const onSubmit = () => {
            if (!fleetLocation.value || !fleetName.value) return;
            store.dispatch("fleets/CREATE_FLEET", {
                name: fleetName.value,
                location: fleetLocation.value,
            });
            emit("close");
        };
        return {
            requesting,
            availableFleets,
            fleetLocation,
            fleetName,
            onSubmit,
        };
    },
};
</script>

<template>
    <modal :title="$t('fleets.new.headline')" @close="$emit('close')">
        <div class="app-form">
            <p>
                {{
                    $tc("fleets.new.explanation", availableFleets, {
                        num: availableFleets,
                    })
                }}
            </p>
            <new-fleet-location />
            <new-fleet-name @submit="onSubmit" />
        </div>
        <template v-slot:actions>
            <game-button
                :text-string="$t('fleets.new.submitBtn')"
                icon-name="save"
                :disabled="!fleetLocation || !fleetName"
                :loading="requesting"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.app-form {
    padding: 0;
    margin: 0;

    @include themed() {
        background-color: t("g-sunken");
    }

    p {
        margin: 0;
    }
}

.form-row.submit {
    padding: 0;
}
</style>
