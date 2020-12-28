<script>
/******************************************************************************
 * PageComponent: NewContractSingleBlueprint
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "NewContractSingleBlueprint",
    props: {
        blueprintId: String,
        className: String,
        hullType: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const blueprint = computed(() =>
            store.getters["shipyards/blueprintById"](props.blueprintId)
        );
        const isSelected = computed(() => {
            const selectedBlueprint =
                store.state.shipyards.newContract.blueprint;
            return selectedBlueprint === props.blueprintId;
        });
        const onSelect = () => {
            if (!isSelected.value) {
                store.commit(
                    "shipyards/SET_CONTRACT_BLUEPRINT",
                    props.blueprintId
                );
            } else {
                store.commit("shipyards/SET_CONTRACT_BLUEPRINT", "");
            }
        };
        const title = computed(() =>
            isSelected.value
                ? i18n.t("shipyards.construct.newContract.blueprint.labelNo")
                : i18n.t("shipyards.construct.newContract.blueprint.label")
        );
        return {
            blueprint,
            isSelected,
            onSelect,
            title,
        };
    },
};
</script>

<template>
    <li class="blueprint__item">
        <button
            @click="onSelect"
            :class="{ active: isSelected }"
            :title="title"
            :aria-label="title"
        >
            <icon :name="`hull-${hullType}`" />
            {{ className }}
        </button>
    </li>
</template>

<style lang="scss" scoped>
.blueprint__item {
    margin-bottom: 4px;

    @include respond-to("medium") {
        margin-bottom: 8px;
    }

    &:last-of-type {
        margin-bottom: 0;
    }

    button {
        display: flex;
        align-items: center;

        width: 100%;
        padding: 8px;
        border: 1px solid transparent;

        background-color: transparent;
        outline: 0;
        cursor: pointer;

        text-align: left;

        @include themed() {
            color: t("t-light");
            border-color: t("g-deep");
        }

        &.active {
            @include themed() {
                background-color: t("g-iron");
                color: t("t-dark");
                border-color: t("g-white");
            }
        }

        .icon {
            margin-right: 8px;

            @include respond-to("medium") {
                margin-right: 16px;
            }
        }
    }
}
</style>
