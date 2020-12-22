<script>
/******************************************************************************
 * PageComponent: SingleBlueprint
 *****************************************************************************/
import { ref } from "vue";
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
        const showDeleteModal = ref(false);
        const onPreview = () => {
            console.log("do preview of blueprint", props.id);
        };
        const onRename = () => {
            console.log("do rename of class", props.id);
        };
        return {
            onPreview,
            onRename,
            showDeleteModal,
        };
    },
};
</script>

<template>
    <li class="blueprint__item">
        <span>
            {{
                $t("shipyards.manage.blueprintName", {
                    className,
                    hullType: $t("shipyards.hulls." + hullType),
                })
            }}
        </span>
        <game-button
            icon-name="search"
            :text-string="$t('shipyards.manage.preview')"
            :size="1"
            :hide-text-for-mobile="true"
            @click="onPreview"
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
