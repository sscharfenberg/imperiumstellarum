<script>
/******************************************************************************
 * Page: EncounterListRenderSingle
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "EncounterListRenderSingle",
    props: {
        encounter: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const store = useStore();
        const star = computed(() =>
            store.getters["encounters/starById"](props.encounter.starId)
        );
        const starOwner = computed(() =>
            store.getters["encounters/playerById"](props.encounter.ownerId)
        );
        return { star, starOwner };
    },
};
</script>

<template>
    <li>
        <router-link
            :to="{
                name: 'EncounterDetails',
                params: { encounterId: encounter.id },
            }"
        >
            {{ encounter }}<br />
            {{ star }}<br />
            {{ starOwner }}
        </router-link>
    </li>
</template>
