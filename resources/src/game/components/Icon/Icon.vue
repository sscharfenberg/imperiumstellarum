<script>
/******************************************************************************
 * Icon Component
 * these svg icons use a external svg sprite sheet
 * and reference the id in that file
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "Icon",
    props: {
        name: {
            type: String,
            required: true,
        },
        size: {
            type: Number, // [0..4]
            default: 2,
        },
    },
    setup(props) {
        const sizeClass = computed(() => {
            switch (props.size) {
                case 0:
                    return "tiny";
                case 1:
                    return "small";
                case 2:
                default:
                    return "";
                case 3:
                    return "large";
                case 4:
                    return "xlarge";
            }
        });
        return {
            sizeClass,
        };
    },
};
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
        class="icon"
        :class="sizeClass"
    >
        <use :xlink:href="`/storage/iconsprite.svg#${this.name}`" />
    </svg>
</template>

<style lang="scss" scoped>
.icon {
    display: inline-block;

    width: 2.4rem;
    height: 2.4rem;

    fill: currentColor;

    vertical-align: middle;

    &.tiny {
        width: 1.6rem;
        height: 1.6rem;
    }

    &.small {
        width: 2rem;
        height: 2rem;
    }

    &.large {
        width: 3.2rem;
        height: 3.2rem;
    }

    &.xlarge {
        width: 6.4rem;
        height: 6.4rem;
    }
}
</style>
