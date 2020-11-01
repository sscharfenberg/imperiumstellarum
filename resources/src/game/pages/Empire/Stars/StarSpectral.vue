<script>
/******************************************************************************
 * PageComponent: StarSpectral
 *****************************************************************************/
import { computed } from "vue";
import { useI18n } from "vue-i18n";
export default {
    name: "StarSpectral",
    props: {
        spectral: String,
    },
    setup(props) {
        const i18n = useI18n();
        const spectralClassName = computed(() => `spectral--${props.spectral}`);
        const spectralTypeString = computed(
            () => i18n.t("empire.star.spectralType") + ": " + props.spectral
        );
        return {
            spectralClassName,
            spectralTypeString,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <div
        class="spectral"
        :class="spectralClassName"
        :aria-label="spectralTypeString"
        :title="spectralTypeString"
    >
        •
    </div>
</template>

<style lang="scss" scoped>
.spectral {
    z-index: z("form");

    height: 48px;
    margin-right: 1.2rem;
    flex: 0 0 48px;

    background: url("@/theme/spectral-types.png") 0 0 no-repeat;
    background-size: 48px;
    border-top-right-radius: 50%;
    border-bottom-right-radius: 50%;

    text-indent: -1000em;

    @include respond-to("medium") {
        margin-right: 2rem;
    }

    &--O {
        background-position: 0 0;
    }
    &--B {
        background-position: 0 -48px;
    }
    &--A {
        background-position: 0 -96px;
    }
    &--F {
        background-position: 0 -144px;
    }
    &--G {
        background-position: 0 -192px;
    }
    &--K {
        background-position: 0 -240px;
    }
    &--M {
        background-position: 0 -288px;
    }
    &--Y {
        background-position: 0 -336px;
    }
}
</style>
