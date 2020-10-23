<script>
/******************************************************************************
 * App entry component
 *****************************************************************************/
import { useI18n } from "vue-i18n";
export default {
    name: "App",
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <section class="content">
        <router-view />
    </section>
</template>

<style lang="scss" scoped>
.page {
    padding: 0.5rem 0.5rem 2rem 0.5rem;

    @include respond-to("small") {
        padding: 1.5rem 1.5rem 4rem 1.5rem;
    }

    @include themed() {
        background: t("g-bunker");
    }
}
</style>
