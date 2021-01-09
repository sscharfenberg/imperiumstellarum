<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaActions
 *****************************************************************************/
import GameButton from "Components/Button/GameButton";
export default {
    name: "ShowFleet",
    props: {
        fleetId: String,
    },
    components: { GameButton },
    setup() {
        return {};
    },
};
</script>

<template>
    <nav class="fleet-meta__actions">
        <game-button
            icon-name="edit"
            :text-string="$t('fleets.active.actions.edit')"
        />
        <game-button
            icon-name="delete"
            :text-string="$t('fleets.active.actions.delete')"
        />
        <game-button
            icon-name="transit"
            :text-string="$t('fleets.active.actions.move')"
        />
        <game-button
            icon-name="transfer"
            :text-string="$t('fleets.active.actions.transfer')"
        />
    </nav>
</template>

<style lang="scss" scoped>
.fleet-meta__actions {
    @include respond-to("medium") {
        order: -1;
    }

    .btn {
        width: auto;
        margin: 0 4px 4px 0;

        @include respond-to("medium") {
            margin: 0 8px 8px 0;
        }

        &:last-of-type {
            margin-right: 0;
        }
    }
}
</style>
