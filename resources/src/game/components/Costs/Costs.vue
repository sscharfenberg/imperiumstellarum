<script>
/******************************************************************************
 * Component: DisplayCosts
 *****************************************************************************/
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "DisplayCosts",
    props: {
        costs: {
            type: Object,
            required: true,
        },
        showAffordable: {
            type: Boolean,
            default: true,
        },
    },
    components: { Icon },
    setup() {
        const state = useStore().state;
        const resources = computed(() => state.resources);
        return {
            resources,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <ul class="costs">
        <li class="title">{{ $t("common.costs.label") }}:</li>
        <li
            class="cost"
            v-for="(cost, key) in costs"
            :key="key"
            :class="{
                affordable:
                    key !== 'turns' &&
                    resources.filter((res) => res.type === key)[0].amount >=
                        cost,
                insufficient:
                    key !== 'turns' &&
                    resources.filter((res) => res.type === key)[0].amount <
                        cost,
            }"
            :title="
                key !== 'turns'
                    ? t('common.costs.ariaLabel', {
                          type: t('common.resourceTypes.' + key),
                          amount: cost,
                      })
                    : t('common.costs.duration', { turns: cost })
            "
            :aria-label="
                key !== 'turns'
                    ? t('common.costs.ariaLabel', {
                          type: t('common.resourceTypes.' + key),
                          amount: cost,
                      })
                    : t('common.costs.duration', { turns: cost })
            "
        >
            {{ cost }}
            <icon :name="'res-' + key" :size="1" />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.costs {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;

    list-style: none;

    > li {
        padding: 0.5rem 1rem;
        margin: 0 2px 2px 0;

        &:last-child {
            margin-right: 0;
        }
    }

    .title {
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-raven");
            border-color: t("g-abbey");
        }
    }

    .cost {
        display: flex;
        align-items: center;

        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-deep");
            border-color: t("g-abbey");
        }

        > svg {
            margin-left: 0.5rem;
        }

        &.insufficient {
            @include themed() {
                color: t("s-error");
                border-color: t("s-error");
            }
        }

        &.affordable {
            @include themed() {
                border-color: t("s-success");
            }
        }
    }
}
</style>
