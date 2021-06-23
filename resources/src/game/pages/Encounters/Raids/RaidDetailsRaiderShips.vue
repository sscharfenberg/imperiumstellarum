<script>
/******************************************************************************
 * Page: RaidDetailsRaiderShips.vue
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "RaidDetailsRaiderShips",
    props: {
        ark: Number,
        small: Number,
        medium: Number,
        large: Number,
        xlarge: Number,
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <ul
        class="ships"
        :title="$t('encounters.raids.ships')"
        :aria-label="$t('encounters.raids.ships')"
    >
        <li v-if="ark > 0"><icon name="hull-ark" />{{ ark }}</li>
        <li v-if="small > 0"><icon name="hull-small" />{{ small }}</li>
        <li v-if="medium > 0"><icon name="hull-medium" />{{ medium }}</li>
        <li v-if="large > 0"><icon name="hull-large" />{{ large }}</li>
        <li v-if="xlarge > 0"><icon name="hull-xlarge" />{{ xlarge }}</li>
    </ul>
</template>

<style lang="scss" scoped>
.ships {
    display: flex;
    align-items: center;

    padding: 2px 4px;
    border: 2px solid transparent;
    margin: 0;
    gap: 6px;

    list-style: none;

    @include respond-to("medium") {
        padding: 4px 8px;
        gap: 12px;
    }

    @include themed() {
        border-color: t("g-deep");
    }

    > li {
        display: flex;
        align-items: center;

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }
}
</style>
