<script>
/******************************************************************************
 * Component: ModalDialogue
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed, onMounted, onUnmounted } from "vue";
import Icon from "Components/Icon/Icon";
import GameButton from "Components/Button/GameButton";
export default {
    name: "ModalDialogue",
    props: {
        refId: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
    },
    components: { Icon, GameButton },
    setup(props, { emit, slots }) {
        const onKeyDown = (e) => {
            if (e.key === "Escape") {
                document.removeEventListener("keydown", onKeyDown);
                emit("close");
            }
        };
        const renderActions = computed(() => slots.actions);
        onMounted(() => {
            document.addEventListener("keydown", onKeyDown);
        });
        onUnmounted(() => {
            document.removeEventListener("keydown", onKeyDown);
        });
        return {
            renderActions,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <div class="modal" data-modal="{{ refId }}" :style="{ display: 'block' }">
        <div class="modal__backdrop">
            <div class="modal__dialog">
                <header class="modal__head">
                    <h1>{{ title }}</h1>
                    <button class="app-btn small close" @click="$emit('close')">
                        <icon name="cancel" :size="2" />
                    </button>
                </header>
                <main class="modal__body">
                    <slot></slot>
                </main>
                <nav class="modal__actions" v-if="renderActions">
                    <game-button
                        @click="showModal = false"
                        text-string="Cancel"
                        icon-name="cancel"
                    />
                    <slot name="actions"></slot>
                </nav>
            </div>
        </div>
    </div>
</template>
