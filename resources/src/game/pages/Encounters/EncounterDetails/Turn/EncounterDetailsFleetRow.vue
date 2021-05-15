<script>
/******************************************************************************
 * Page: EncounterDetailsFleetRow
 *****************************************************************************/
import EncounterDetailsRenderFleet from "./EncounterDetailsRenderFleet";
export default {
    name: "EncounterDetailsFleetRow",
    props: {
        fleetId: String,
        row: Number,
        col: Number,
        name: String,
        ships: Object,
        ownerId: String,
        columnWidth: Array,
    },
    components: { EncounterDetailsRenderFleet },
    setup(props) {
        /**
         * calculate column margin
         * @returns {string}
         */
        const columnMargin = () => {
            const col = props.col;
            let margin = 0;
            for (let i = 0; i < col; i++) {
                margin += props.columnWidth[i];
            }
            return margin + "px";
        };
        return { columnMargin };
    },
};
</script>

<template>
    <div
        :title="name"
        class="fleetrow"
        :style="{ '--fleet-margin': columnMargin() }"
    >
        <encounter-details-render-fleet
            :name="name"
            :ships="ships"
            :owner-id="ownerId"
            :column-width="columnWidth[col]"
        />
    </div>
</template>

<style lang="scss" scoped>
.fleetrow {
    display: flex;
    align-items: center;

    padding: 4px 0;
    border-bottom: 1px solid transparent;

    &:last-child {
        padding-bottom: 0;
        border-bottom-width: 0;
    }

    &:first-child {
        padding-top: 0;
    }

    @include themed() {
        border-color: t("g-ebony");
    }
}
</style>
