<script>
/******************************************************************************
 * PageComponent: EncountersListStarOwner
 *****************************************************************************/
export default {
    name: "EncountersListStarOwner",
    props: {
        ticker: String,
        name: String,
        colour: String,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <span
        class="encounter-item star__owner"
        :title="
            $t('encounters.list.starOwner', { name: `[${ticker}] ${name}` })
        "
        >[{{ ticker }}]</span
    >
</template>
