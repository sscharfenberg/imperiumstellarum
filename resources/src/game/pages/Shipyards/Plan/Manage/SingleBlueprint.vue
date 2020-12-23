<script>
/******************************************************************************
 * PageComponent: SingleBlueprint
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import DeleteBlueprintModal from "./DeleteBlueprintModal";
export default {
    name: "SingleBlueprint",
    components: { GameButton, DeleteBlueprintModal },
    props: {
        id: String,
        className: String,
        hullType: String,
    },
    setup(props) {
        const store = useStore();
        const showDeleteModal = ref(false);
        const blueprint = computed(() =>
            store.getters["shipyards/blueprintById"](props.id)
        );
        const isPreviewing = computed(
            () => props.id === store.state.shipyards.preview.id
        );
        const onPreview = () => {
            store.commit("shipyards/SET_MANAGE_BLUEPRINT_PREVIEW", {
                id: blueprint.value.id,
                hullType: props.hullType,
                className: props.className,
                modules: blueprint.value.modules,
                techLevels: blueprint.value.techLevels,
            });
        };
        const onCancelPreview = () => {
            store.commit("shipyards/RESET_MANAGE_BLUEPRINT_PREVIEW");
        };
        const onRename = () => {
            console.log("do rename of class", props.id);
        };
        return {
            onPreview,
            onCancelPreview,
            onRename,
            showDeleteModal,
            isPreviewing,
        };
    },
};
</script>

<template>
    <li class="blueprint__item" :class="{ active: isPreviewing }">
        <span>
            {{ className }}
        </span>
        <game-button
            v-if="!isPreviewing"
            icon-name="search"
            :text-string="$t('shipyards.manage.preview')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="onPreview"
        />
        <game-button
            v-if="isPreviewing"
            icon-name="cancel"
            :text-string="$t('shipyards.manage.cancelPreview')"
            :size="1"
            :hide-text-for-mobile="true"
            :primary="true"
            @click="onCancelPreview"
        />
        <game-button
            icon-name="edit"
            :text-string="$t('shipyards.manage.rename')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="onRename"
        />
        <game-button
            icon-name="delete"
            :text-string="$t('shipyards.manage.delete')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="showDeleteModal = true"
        />
        <delete-blueprint-modal
            v-if="showDeleteModal"
            :blueprint-id="id"
            @close="showDeleteModal = false"
        />
    </li>
</template>

<style lang="scss" scoped>
.blueprint__item {
    display: flex;
    align-items: center;

    padding: 4px;
    border: 1px solid transparent;
    margin-bottom: 4px;

    @include respond-to("medium") {
        padding: 8px;
        margin-bottom: 8px;
    }

    @include themed() {
        border-color: t("g-deep");
    }

    &.active {
        @include themed() {
            background-color: t("g-deep");
            border-color: t("g-abbey");
        }
    }

    &:last-of-type {
        margin-bottom: 0;
    }

    > .btn {
        margin-left: 4px;

        @include respond-to("medium") {
            margin-left: 8px;
        }

        &:first-of-type {
            margin-left: auto;
        }
    }

    > span {
        margin-right: 4px;
    }
}
</style>
