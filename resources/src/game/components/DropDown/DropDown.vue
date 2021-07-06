<script>
/******************************************************************************
 * Component: Dropdown
 * CSS Styling of native <select>s. <select multiple> not yet supported.
 *****************************************************************************/
import { computed, ref } from "vue";
export default {
    name: "Dropdown",
    props: {
        refId: String, // html id for label.
        options: { type: Array, required: true }, // array of options. props = val, label
        selectedOption: String, // val of the selected option
        chooseString: String, // "Please select"
        label: String, // html label for the select
        disabled: { type: Boolean, default: false }, // is the select disabled?
    },
    emits: ["valuechanged"],
    setup(props, { emit }) {
        const reference = computed(
            () => props.refId ?? Math.random().toString(36).substring(2)
        );
        const model = ref(props.selectedOption);
        const onChange = () => {
            emit("valuechanged", model.value);
        };
        return { model, onChange, reference };
    },
};
</script>

<template>
    <label v-if="label" :for="`select-${reference}`">{{ label }}</label>
    <div class="select" :class="{ disabled }">
        <select
            :id="`select-${reference}`"
            @change="onChange"
            v-model="model"
            :disabled="disabled"
        >
            <option v-if="chooseString" value="">{{ chooseString }}</option>
            <option
                v-for="option in options"
                :key="option.val"
                :value="option.val"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>

<style lang="scss" scoped>
/*
 * reset of styles, including removing the default dropdown arrow
 */
select {
    /* Stack above custom arrow */
    z-index: 1;

    width: 100%;
    padding: 8px 16px;
    border: 2px solid transparent;
    margin: 0 8px 0 0;

    appearance: none;

    background-color: transparent;
    outline: none; /* Remove focus outline, will add on alternate element */
    cursor: inherit;

    font-family: inherit;
    font-size: inherit;
    font-weight: inherit;
    line-height: inherit;

    /*
     * Remove dropdown arrow in IE10 & IE11
     * @link https://www.filamentgroup.com/lab/select-css.html
     */
    &::-ms-expand {
        display: none;
    }

    @include themed() {
        color: t("t-bright");
    }
}

.select {
    display: grid;
    position: relative;
    align-items: center;
    grid-template-areas: "select";

    max-width: 300px;
    padding: 0;
    border: 2px solid transparent;

    border-radius: 0;
    cursor: pointer;

    font-size: 16px;
    font-weight: 300;
    line-height: 1;

    @include themed() {
        background: transparent;
        color: t("t-bright");
        border-color: t("g-raven");
    }

    select,
    &::after {
        grid-area: select;
    }

    /* Custom arrow */
    &:not(.select--multiple)::after {
        justify-self: end;

        width: 12px;
        height: 6px;
        margin: 0 8px 0 0;
        clip-path: polygon(100% 0%, 0 0%, 50% 100%);

        content: " ";

        transition: transform map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-charcoal");
        }
    }

    &:focus-within {
        @include themed() {
            border-color: t("b-viking");
        }

        &::after {
            transform: rotate(180deg);
        }
    }

    &.disabled {
        cursor: not-allowed;

        font-style: italic;

        @include themed() {
            color: t("g-fog");
            border-color: t("g-mystic");
        }

        &::after {
            @include themed() {
                background-color: t("g-fog");
            }
        }
    }

    option {
        white-space: normal;

        @include themed() {
            background-color: t("g-sunken");
            outline-color: t("b-viking");
        }
    }
}
</style>
