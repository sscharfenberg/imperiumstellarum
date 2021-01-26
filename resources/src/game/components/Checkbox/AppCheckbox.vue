<script>
/******************************************************************************
 * Component: AppCheckbox
 *****************************************************************************/
import { computed, ref } from "vue";
export default {
    name: "AppCheckbox",
    props: {
        refId: String,
        checkedInitially: Boolean,
    },
    emits: ["checked", "unchecked"],
    setup(props, { slots, emit }) {
        const reference = computed(
            () => props.refId ?? Math.random().toString(36).substring(2)
        );
        const checkboxStatus = ref(props.checkedInitially);
        const emitEvent = () =>
            checkboxStatus.value ? emit("checked") : emit("unchecked");
        const showSlot = computed(() => slots.default);
        return { reference, checkboxStatus, emitEvent, showSlot };
    },
};
</script>

<template>
    <div class="checkbox__wrapper">
        <label class="checkbox__label" :for="reference">
            <input
                type="checkbox"
                :name="reference"
                :id="reference"
                v-model="checkboxStatus"
                @change="emitEvent"
            />
            <span class="checkbox__inner"></span>
        </label>
        <label v-if="showSlot" class="checkbox__text" :for="reference"
            ><slot></slot
        ></label>
    </div>
</template>

<style lang="scss">
// styles for this can be found in
// resources/src/app/styles/components/_checkbox.scss
</style>
