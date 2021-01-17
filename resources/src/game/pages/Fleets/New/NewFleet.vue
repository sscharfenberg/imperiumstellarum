<script>
/******************************************************************************
 * PageComponent: NewFleet
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import NewFleetLocation from "./NewFleetLocation";
import NewFleetName from "./NewFleetName";
import GameButton from "Components/Button/GameButton";
export default {
    name: "NewFleet",
    components: {
        SubHeadline,
        NewFleetLocation,
        NewFleetName,
        GameButton,
    },
    emits: ["close"],
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.fleets.requesting);
        const availableFleets = computed(
            () =>
                store.state.fleets.maxFleets - store.state.fleets.fleets.length
        );
        const fleetLocation = computed(() => store.state.fleets.createLocation);
        const fleetName = computed(() => store.state.fleets.createName);
        const onSubmit = () => {
            store.dispatch("fleets/CREATE_FLEET", {
                name: fleetName.value,
                location: fleetLocation.value,
            });
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
    <div class="app-form">
        <sub-headline
            :headline="
                $tc('fleets.new.explanation', availableFleets, {
                    num: availableFleets,
                })
            "
        />
        <new-fleet-location />
        <new-fleet-name />
        <div class="form-row submit">
            <div class="label"></div>
            <div class="input">
                <game-button
                    :text-string="$t('fleets.new.submitBtn')"
                    icon-name="save"
                    :disabled="!fleetLocation || !fleetName"
                    :loading="requesting"
                    @click="onSubmit"
                />
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.app-form {
    padding: 8px;
    margin: 0 0 16px 0;

    @include respond-to("medium") {
        padding: 16px;
    }

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
