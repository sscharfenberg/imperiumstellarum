<script>
/******************************************************************************
 * Page: RaidsDetailsResources.vue
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "RaidsDetailsResources",
    props: {
        energy: Number,
        minerals: Number,
        food: Number,
        research: Number,
    },
    components: { Icon },
    setup() {
        return {};
    },
};
</script>

<template>
    <ul
        class="res"
        :title="$t('encounters.raids.resources')"
        :aria-label="$t('encounters.raids.resources')"
    >
        <li v-if="energy && energy !== 0">
            <icon name="res-energy" />{{ energy > 0 ? "+" + energy : energy }}
        </li>
        <li v-if="minerals && minerals !== 0">
            <icon name="res-minerals" />{{
                minerals > 0 ? "+" + minerals : minerals
            }}
        </li>
        <li v-if="food && food !== 0">
            <icon name="res-food" />{{ food > 0 ? "+" + food : food }}
        </li>
        <li v-if="research && research !== 0">
            <icon name="res-research" />{{
                research > 0 ? "+" + research : research
            }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.res {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

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
