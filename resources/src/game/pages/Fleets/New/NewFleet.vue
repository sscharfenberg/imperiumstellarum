<script>
/******************************************************************************
 * PageComponent: NewFleet
 *****************************************************************************/
import { computed, ref } from "vue";
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
        const location = ref("");
        const name = ref("");
        const availableFleets = computed(
            () =>
                store.state.fleets.maxFleets - store.state.fleets.fleets.length
        );
        const start = ref(false);
        const onLocationSelected = (val) => (location.value = val);
        const onNameChanged = (val) => (name.value = val);
        const onSubmit = () => {
            store.dispatch("fleets/CREATE_FLEET", {
                name: name.value,
                location: location.value,
            });
        };
        return {
            start,
            requesting,
            availableFleets,
            location,
            name,
            onLocationSelected,
            onNameChanged,
            onSubmit,
        };
    },
};
</script>

<template>
    <area-section :headline="$t('fleets.new.headline')">
        <game-button
            v-if="!start"
            :text-string="$t('fleets.new.start')"
            icon-name="fleets"
            @click="start = true"
        />
        <div class="app-form" v-if="start">
            <sub-headline
                :headline="
                    $tc('fleets.new.explanation', availableFleets, {
                        num: availableFleets,
                    })
                "
            />
            <new-fleet-location @selected="onLocationSelected" />
            <new-fleet-name @changed="onNameChanged" />
            <div class="form-row submit">
                <div class="label"></div>
                <div class="input">
                    <game-button
                        :text-string="$t('fleets.new.submitBtn')"
                        icon-name="save"
                        :disabled="!location || !name"
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
