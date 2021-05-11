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
    <span class="star__name encounter-item">{{ name }}</span>
</template>
