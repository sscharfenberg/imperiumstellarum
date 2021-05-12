<script>
/******************************************************************************
 * PageComponent: EncountersListStarName
 *****************************************************************************/
export default {
    name: "EncountersListStarName",
    props: {
        name: String,
    },
    setup() {},
};
</script>

<template>
    <span
        class="star__name encounter-item"
        :title="$t('encounters.list.starName', { name })"
        :aria-label="$t('encounters.list.starName', { name })"
        >{{ name }}</span
    >
</template>
