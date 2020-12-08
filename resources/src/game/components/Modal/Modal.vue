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
    <div class="modal" :style="{ display: 'block' }">
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
                        @click="$emit('close')"
                        :text-string="t('common.modal.cancel')"
                        icon-name="cancel"
                    />
                    <slot name="actions"></slot>
                </nav>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.stats {
    display: grid;
    grid-template-columns: 50% 50%;

    padding: 0;
    margin: 0 0 1.6rem 0;
    grid-gap: 0.4rem;

    list-style: none;

    > li {
        padding: 0.4rem;

        text-align: center;

        @include themed() {
            border: 1px solid t("g-abbey");

            background: t("g-deep");
        }

        &.featured {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;

            @include themed() {
                border-color: t("g-infra");
            }
        }

        &.stats--two-col {
            grid-column: 1 / span 2;
        }

        &.stats__dots {
            padding: 0.4rem 0.8rem 0 0.8rem;
        }

        &.stats--centered {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        &.stats--padded {
            padding: 0.8rem;
        }

        &.text-left {
            justify-content: flex-start;

            text-align: left;
        }

        &.justified {
            justify-content: space-between;
        }
    }

    &__dot {
        display: inline-block;

        width: 4px;
        height: 4px;
        margin: 0 4px 4px 0;

        border-radius: 50%;

        text-indent: -1000em;

        @include themed() {
            background: linear-gradient(
                to bottom,
                t("s-warning") 0%,
                t("s-error") 100%
            );
        }
    }
}
</style>
