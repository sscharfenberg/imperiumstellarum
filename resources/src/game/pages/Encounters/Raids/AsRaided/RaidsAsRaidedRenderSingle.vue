<script>
/******************************************************************************
 * Page: RaidsAsRaiderRenderSingle.vue
 *****************************************************************************/
export default {
    name: "RaidsAsRaiderRenderSingle",
    props: {
        raid: {
            type: Object,
            required: true,
        },
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <li>{{raid}}</li>
</template>
