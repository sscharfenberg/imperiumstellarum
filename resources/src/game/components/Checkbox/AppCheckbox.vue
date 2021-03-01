<script>
/******************************************************************************
 * Component: AppCheckbox
 *****************************************************************************/
import { onBeforeUpdate, onBeforeMount, computed, ref } from "vue";
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
        const checkboxStatus = ref(false);
        const emitEvent = () => {
            checkboxStatus.value = !checkboxStatus.value;
            checkboxStatus.value ? emit("checked") : emit("unchecked");
        };
        const showSlot = computed(() => slots.default);
        onBeforeUpdate(() => {
            checkboxStatus.value = props.checkedInitially;
        });
        onBeforeMount(() => {
            checkboxStatus.value = props.checkedInitially;
        });
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
                :checked="!!checkboxStatus"
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
