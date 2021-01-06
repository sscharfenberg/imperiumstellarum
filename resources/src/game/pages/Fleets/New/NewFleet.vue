<script>
/******************************************************************************
 * PageComponent: NewFleet
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import AreaSection from "Components/AreaSection/AreaSection";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import NewFleetLocation from "./NewFleetLocation";
export default {
    name: "NewFleet",
    components: { AreaSection, SubHeadline, NewFleetLocation },
    setup() {
        const store = useStore();
        const fleets = computed(() => store.state.fleets.fleets);
        const maxFleets = computed(() => store.state.fleets.maxFleets);
        const availableFleets = computed(
            () => maxFleets.value - fleets.value.length
        );
        return {
            availableFleets,
        };
    },
};
</script>

<template>
    <area-section :headline="$t('fleets.new.headline')">
        <div class="app-form">
            <sub-headline
                :headline="
                    $tc('fleets.new.explanation', availableFleets, {
                        num: availableFleets,
                    })
                "
            />
            <new-fleet-location />
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
</style>
