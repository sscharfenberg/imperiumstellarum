<script>
/******************************************************************************
 * Page: ShowStar
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "ShowStar",
    props: {
        id: String,
    },
    setup(props) {
        const store = useStore();
        console.log(store.getters);
        const star = computed(() => store.getters["empire/starById"](props.id));
        return {
            star,
        };
    },
};
</script>

<template>
    <li>{{ star }}</li>
</template>
