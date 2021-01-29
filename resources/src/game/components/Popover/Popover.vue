<script>
/******************************************************************************
 * Component: Popover
 *****************************************************************************/
import { ref, onMounted, onBeforeUnmount } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "Info",
    props: {
        icon: {
            type: String,
            default: "help",
        },
        align: {
            type: String,
            default: "left",
        },
    },
    components: { Icon },
    setup() {
        const show = ref(false);
        const triggerId = Math.random().toString(36).substring(2);
        const handleOutsideClick = (e) => {
            const el = e.target;
            const trigger = document.getElementById(triggerId);
            if (!(el && el instanceof Node && trigger.contains(el))) {
                show.value = false;
            }
        };
        onMounted(() => {
            window.addEventListener("click", handleOutsideClick);
        });
        onBeforeUnmount(() => {
            window.removeEventListener("click", handleOutsideClick);
        });
        return { show, triggerId };
    },
};
</script>

<template>
    <div class="popover">
        <div
            v-if="show"
            class="popover__modal"
            :class="{
                'popover__modal--left': align === 'left',
                'popover__modal--right': align === 'right',
            }"
        >
            <slot></slot>
        </div>
        <icon
            :id="triggerId"
            :name="icon"
            class="popover__trigger"
            :size="2"
            @click="show = !show"
        />
    </div>
</template>

<style lang="scss" scoped>
.popover {
    display: inline-block;
    position: relative;

    &__trigger {
        width: 32px;
        height: 32px;
        padding: 4px;

        cursor: pointer;

        &:hover {
            @include themed() {
                color: t("b-viking");
            }
        }
    }

    &__modal {
        position: absolute;
        top: calc(100% + 9px);
        z-index: z("popover");

        width: 320px;
        padding: 16px;
        border: 3px solid transparent;

        cursor: default;

        @include themed() {
            background-color: rgba(t("g-black"), 0.85);
            border-color: t("g-raven");
        }

        &::before {
            display: block;
            position: absolute;
            top: -11px;

            width: 0;
            height: 0;
            border-width: 0 8px 8px;

            border-style: solid;

            content: " ";

            @include themed() {
                border-color: transparent transparent t("g-raven") transparent;
            }
        }

        /**
         * align modal window
         */
        &--left {
            left: 0;

            &::before {
                left: 5px;
            }
        }

        &--right {
            right: 0;

            &::before {
                right: 5px;
            }
        }
    }
}
</style>
