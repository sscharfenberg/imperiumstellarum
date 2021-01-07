<script>
/******************************************************************************
 * PageComponent: NewFleet
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import NewFleetLocation from "./NewFleetLocation";
import NewFleetName from "./NewFleetName";
import GameButton from "Components/Button/GameButton";
export default {
    name: "NewFleet",
    components: {
        AreaSection,
        SubHeadline,
        NewFleetLocation,
        NewFleetName,
        GameButton,
    },
    setup() {
        const store = useStore();
        const requesting = computed(() => store.state.fleets.requesting);
        const availableFleets = computed(
            () =>
                store.state.fleets.maxFleets - store.state.fleets.fleets.length
        );
        const showCreateForm = computed(() => store.state.fleets.create.show);
        const fleetLocation = computed(
            () => store.state.fleets.create.location
        );
        const fleetName = computed(() => store.state.fleets.create.name);
        const onSubmit = () => {
            store.dispatch("fleets/CREATE_FLEET", {
                name: fleetName.value,
                location: fleetLocation.value,
            });
        };
        const onShow = () => {
            store.commit("fleets/SET_SHOW_CREATE", true);
        };
        return {
            onShow,
            showCreateForm,
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
    <area-section :headline="$t('fleets.new.headline')">
        <game-button
            v-if="!showCreateForm"
            :text-string="$t('fleets.new.start')"
            icon-name="fleets"
            @click="onShow"
        />
        <div class="app-form" v-if="showCreateForm">
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
    </area-section>
</template>

<style lang="scss" scoped>
.app-form {
    padding: 8px;
    margin: 0;

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
