<script>
/******************************************************************************
 * Component: AppSwitch
 *****************************************************************************/
import { computed, ref } from "vue";
export default {
    name: "AppSwitch",
    props: {
        refId: String,
        checkedInitially: Boolean,
        labelChecked: String,
        labelUnChecked: String,
    },
    emits: ["checked", "unchecked"],
    setup(props, { emit }) {
        const reference = computed(
            () => props.refId ?? Math.random().toString(36).substring(2)
        );
        const checkboxStatus = ref(props.checkedInitially);
        const emitEvent = () =>
            checkboxStatus.value ? emit("checked") : emit("unchecked");
        return { reference, checkboxStatus, emitEvent };
    },
};
</script>

<template>
    <div class="app-switch">
        <input
            type="checkbox"
            :id="reference"
            name="reference"
            v-model="checkboxStatus"
            @change="emitEvent"
        />
        <label class="off" :for="reference">{{ labelUnChecked }}</label>
        <label class="switch" :for="reference"></label>
        <label class="on" :for="reference">{{ labelChecked }}</label>
    </div>
</template>

<style lang="scss">
// styles for this can be found in
// resources/src/app/styles/components/_switch.scss
</style>
