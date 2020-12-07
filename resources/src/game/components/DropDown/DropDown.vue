<script>
/******************************************************************************
 * PageComponent: DropDown
 * https://iendeavor.github.io/vue-next-select/
 *****************************************************************************/
import { ref } from "vue";
import VueSelect from "vue-next-select";
export default {
    name: "DropDown",
    components: { VueSelect },
    props: {
        options: Array,
        labeledBy: String,
        placeHolder: String,
    },
    setup(props, { emit }) {
        const model = ref(null);
        const handleSelected = (selectedOption) => {
            emit("on-selected", selectedOption);
        };
        return {
            model,
            handleSelected,
        };
    },
};
</script>

<template>
    <vue-select
        v-model="model"
        :options="options"
        label-by="name"
        :track-by="(option) => option.name"
        :close-on-select="true"
        :placeholder="placeHolder"
        @selected="handleSelected"
    />
</template>

<style lang="scss">
.vue-select {
    display: flex;
    position: relative;
    align-items: flex-start;
    justify-content: flex-start;
    flex-direction: column;

    box-sizing: border-box;
    padding: 5px;
    border: 1px solid transparent;

    outline: none;
    cursor: pointer;

    transition: border-color map-get($animation-speeds, "fast") linear,
        background-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-raven");
        border-color: t("g-abbey");
    }

    &[data-is-focusing="true"] {
        @include themed() {
            background-color: t("g-deep");
            border-color: t("g-asher");
        }
    }

    .icon.delete {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 8px;
        min-width: 8px;
        max-width: 8px;
        height: 8px;
        min-height: 8px;
        max-height: 8px;

        padding: 0;
        border: none;
        margin: 0;

        background: none;
        cursor: pointer;
    }

    .icon.arrow-downward {
        width: 0;
        height: 0;
        border-width: 0 6px 6px;

        border-style: solid;

        transition: transform 0.2s linear;

        @include themed() {
            border-color: transparent transparent t("g-iron") transparent;
        }

        &.active {
            transform: rotate(180deg);
        }
    }

    &.disabled {
        background-color: rgb(239, 239, 239);

        * {
            cursor: not-allowed;
        }
    }

    .vue-select-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        width: 100%;
    }

    .vue-select-header > .icon.loading,
    .vue-select-header > .icon.arrow-downward {
        margin-right: 4px;
    }

    .vue-tags {
        display: flex;
        flex-wrap: wrap;

        min-height: calc(1rem + 4px);
        padding: 2px;
        margin: 0;

        user-select: none;
    }

    .vue-tags.collapsed {
        flex-wrap: nowrap;

        overflow: auto;
    }

    .vue-tag {
        display: none;
        align-items: center;
        justify-content: center;

        min-height: 1rem;
        padding: 0 4px;
        margin: 2px;

        background-color: #999;
        list-style-type: none;

        font-size: 0.8rem;

        > span {
            margin-right: 4px;
        }

        &.selected {
            display: flex;
            align-items: center;
            justify-content: center;

            padding: 0 4px;

            background-color: #999;

            font-size: 0.8rem;
        }
    }

    .vue-input {
        display: flex;
        align-items: center;

        box-sizing: border-box;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        padding: 2px;
        border: none;

        outline: none;
    }

    .vue-input > input {
        width: 100%;
        min-width: 0;
        padding: 0;
        border: none;

        background: transparent;
        outline: none;

        font-size: 16px;

        @include themed() {
            color: t("t-bright");
        }

        &::placeholder {
            @include themed() {
                color: t("t-bright");
            }
        }
    }

    .vue-input > input[disabled] {
        background-color: rgb(239, 239, 239);
    }

    .vue-select-header > .vue-input > input[disabled] {
        background-color: unset;
    }

    .vue-select-header > .vue-input > input[readonly] {
        cursor: pointer;
    }

    .vue-dropdown {
        position: absolute;
        top: 35px !important;
        left: -1px;
        z-index: z("form");

        box-sizing: border-box;
        overflow-y: auto;
        width: inherit;
        min-width: 200px;
        max-width: 400px;
        max-height: 50vh;
        padding: 0;
        border: 1px solid transparent;
        margin: 0;

        list-style-type: none;

        @include themed() {
            background-color: t("g-sunken");
            border-color: t("g-abbey");
        }
    }

    .vue-dropdown[data-visible-length="0"] {
        border: none;
    }

    .vue-dropdown-item {
        padding: 5px 6px;
        border-bottom: 1px solid transparent;

        list-style-type: none;
        cursor: pointer;

        font-weight: 300;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear;

        &:last-child {
            border-bottom: 0;
        }

        @include themed() {
            border-bottom-color: t("g-abbey");
        }

        &:hover {
            @include themed() {
                background-color: t("g-ebony");
                color: t("b-viking");
            }
        }
    }

    .vue-dropdown-item.selected {
        @include themed() {
            background-color: t("g-ebony");
            color: t("b-viking");
        }
    }

    .icon.loading {
        display: inline-block;
        position: relative;

        width: 8px;
        min-width: 8px;
        height: 8px;
        min-height: 8px;
    }

    .icon.loading div {
        display: block;
        position: absolute;

        box-sizing: border-box;
        width: 8px;
        height: 8px;
        border: 1px solid #999;

        border-radius: 50%;
        border-color: #999 transparent transparent transparent;

        animation: loading 1s cubic-bezier(0.5, 0, 0.5, 1) infinite;
    }

    .icon.loading div:nth-child(1) {
        animation-delay: -0.08s;
    }
    .icon.loading div:nth-child(2) {
        animation-delay: -0.16s;
    }
}

@keyframes loading {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
