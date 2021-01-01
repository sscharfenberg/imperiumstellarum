<script>
/******************************************************************************
 * PageComponent: SingleBlueprint
 *****************************************************************************/
import { computed, ref, onUpdated } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import DeleteBlueprintModal from "./DeleteBlueprintModal";
import Loading from "Components/Loading/Loading";
export default {
    name: "SingleBlueprint",
    components: { GameButton, DeleteBlueprintModal, Loading },
    props: {
        id: String,
        className: String,
        hullType: String,
    },
    setup(props) {
        const store = useStore();
        const showDeleteModal = ref(false);
        const isRenaming = ref(false);
        const inputName = ref(props.className);
        const inputRef = ref(null);
        const bpRequestingRename = computed(
            () => store.state.shipyards.changingBpName === props.id
        );
        const blueprint = computed(() =>
            store.getters["shipyards/blueprintById"](props.id)
        );
        const constructingBpIds = computed(() =>
            store.state.shipyards.constructionContracts.map(
                (c) => c.blueprintId
            )
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
        const onDoneRenaming = () => {
            console.log("done renaming.");
            store.dispatch("shipyards/CHANGE_BLUEPRINT_NAME", {
                id: props.id,
                name: inputName.value,
            });
            isRenaming.value = false;
        };
        const onCancelRenaming = () => {
            console.log("cancel renaming.");
            isRenaming.value = false;
            inputName.value = props.className;
        };
        onUpdated(() => {
            if (isRenaming.value) {
                inputRef.value?.focus();
            }
        });
        return {
            inputRef,
            inputName,
            onPreview,
            onCancelPreview,
            showDeleteModal,
            isPreviewing,
            isRenaming,
            onDoneRenaming,
            onCancelRenaming,
            bpRequestingRename,
            constructingBpIds,
        };
    },
};
</script>

<template>
    <li class="blueprint__item" :class="{ active: isPreviewing }">
        <span v-if="!isRenaming">
            {{ className }}
        </span>
        <input
            v-if="isRenaming"
            type="text"
            class="blueprint__name"
            ref="inputRef"
            v-model="inputName"
            @keyup.enter="onDoneRenaming"
            @keyup.escape="onCancelRenaming"
        />
        <loading v-if="bpRequestingRename" :size="30" />
        <game-button
            v-if="isRenaming"
            icon-name="done"
            :size="1"
            @click="onDoneRenaming"
        />
        <game-button
            v-if="isRenaming"
            icon-name="cancel"
            :size="1"
            @click="onCancelRenaming"
        />
        <game-button
            v-if="!isPreviewing && !isRenaming"
            icon-name="search"
            :text-string="$t('shipyards.manage.preview')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="onPreview"
        />
        <game-button
            v-if="isPreviewing && !isRenaming"
            icon-name="cancel"
            :text-string="$t('shipyards.manage.cancelPreview')"
            :size="1"
            :hide-text-for-mobile="true"
            :primary="true"
            @click="onCancelPreview"
        />
        <game-button
            v-if="!isRenaming"
            icon-name="edit"
            :text-string="$t('shipyards.manage.rename')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="isRenaming = true"
        />
        <game-button
            v-if="!isRenaming"
            icon-name="delete"
            :text-string="$t('shipyards.manage.delete')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="showDeleteModal = true"
            :disabled="constructingBpIds.includes(id)"
            :aria-disabled="constructingBpIds.includes(id)"
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

.blueprint__name {
    height: 30px;
    padding: 4px 8px;
    border: 0;
    margin-right: 16px;
    flex-grow: 1;

    outline: 0;

    @include themed() {
        background: t("g-bunker");
        color: t("b-viking");
    }
}

.spinner {
    margin: 0 16px 0 auto;
}
</style>
