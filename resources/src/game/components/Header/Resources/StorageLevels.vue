<script>
/******************************************************************************
 * Component: StorageLevels
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed } from "vue";
export default {
    name: "StorageLevels",
    props: {
        level: {
            type: Number,
            required: true,
        },
        maxLevel: {
            type: Number,
            required: true,
        },
        area: {
            type: String,
            required: true,
        },
    },
    setup() {
        const levelClass = computed(() => {
            let classList = ["upgrading"];
            return classList.join(" ");
        });
        return {
            levelClass,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <ul class="storage-upgrades" aria-label="d" title="f">
        <li
            v-for="n in maxLevel"
            :key="n"
            class="level"
            :class="{ active: n <= level }"
        >
            {{ n }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.storage-upgrades {
    display: flex;
    justify-content: space-between;
    flex-direction: column-reverse;

    height: 100%;
    padding: 0;
    margin: 0 0 0 2px;

    list-style: none;

    > .level {
        width: 8px;
        height: 8px;
        border: 1px solid transparent;
        margin: 0;

        text-indent: -1000em;

        @include themed() {
            background: rgba(t("g-charcoal"), 0.7);
            border-color: t("g-sunken");
        }

        &.active {
            @include themed() {
                background: linear-gradient(
                    to top left,
                    t("s-active") 0%,
                    t("b-gorse") 100%
                );
            }
        }

        &.building {
            @include themed() {
                background-color: rgba(t("s-warning"), 0.7);
            }
        }
    }
}
</style>
