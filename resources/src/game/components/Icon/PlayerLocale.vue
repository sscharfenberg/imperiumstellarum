<script>
/******************************************************************************
 * Component: PlayerLocale
 *****************************************************************************/
import { computed } from "vue";
export default {
    name: "PlayerLocale",
    props: {
        locale: {
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
    <img
        :src="`/assets/images/flags/${locale}.svg`"
        :alt="$t('common.locale.' + locale)"
    />
</template>

<style lang="scss" scoped>
img {
    display: inline-block;

    width: 28px;
    height: 14px;
    border: 1px solid transparent;
    flex: 0 0 30px;

    vertical-align: middle;

    @include themed() {
        border-color: t("g-abbey");
    }

    &.tiny {
        width: 10px;
        height: 20px;
        flex: 0 0 10px;
    }

    &.small {
        width: 12px;
        height: 24px;
        flex: 0 0 24px;
    }

    &.large {
        width: 16px;
        height: 32px;
        flex: 0 0 32px;
    }

    &.xlarge {
        width: 20px;
        height: 40px;
        flex: 0 0 40px;
    }
}
</style>
