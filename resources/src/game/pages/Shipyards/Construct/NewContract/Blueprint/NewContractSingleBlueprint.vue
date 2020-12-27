<script>
/******************************************************************************
 * PageComponent: NewContractSingleBlueprint
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "NewContractSingleBlueprint",
    props: {
        blueprintId: String,
        className: String,
        hullType: String,
    },
    setup(props) {
        const store = useStore();
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
        return {
            blueprint,
            isSelected,
            onSelect,
        };
    },
};
</script>

<template>
    <li class="blueprint__item">
        <button @click="onSelect" :class="{ active: isSelected }">
            {{ className }}
            {{ hullType }}
            {{ blueprintId }}
            {{ isSelected }}
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
                background-color: t("g-deep");
                border-color: t("g-abbey");
            }
        }
    }
}
</style>
