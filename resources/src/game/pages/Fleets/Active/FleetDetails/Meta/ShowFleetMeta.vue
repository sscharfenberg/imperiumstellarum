<script>
/******************************************************************************
 * PageComponent: ShowFleetMeta
 *****************************************************************************/
import ShowFleetMetaActions from "./ShowFleetMetaActions";
import ShowFleetMetaStatus from "./ShowFleetMetaStatus";
export default {
    name: "ShowFleetMeta",
    props: {
        fleetId: String,
    },
    components: { ShowFleetMetaActions, ShowFleetMetaStatus },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="fleet-meta">
        <show-fleet-meta-status :fleet-id="fleetId" />
        <show-fleet-meta-actions :fleet-id="fleetId" />
    </div>
</template>

<style lang="scss" scoped>
.fleet-meta {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;

    @include respond-to("medium") {
        flex-direction: row;
    }
}
</style>
