<script>
/******************************************************************************
 * Component: PlayerResources
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import StorageLevels from "./StorageLevels";
import UpgradeStorage from "./UpgradeStorage";
export default {
    name: "PlayerResource",
    props: {
        type: {
            type: String,
            required: true,
        },
        amount: {
            type: Number,
            required: true,
        },
        max: {
            type: Number,
            required: true,
        },
        storageLevel: {
            type: Number,
            required: true,
        },
        maxLevel: {
            type: Number,
            required: true,
        },
    },
    components: { Icon, StorageLevels, UpgradeStorage },
    setup(props) {
        const resourceBarWidth = computed(() => {
            return `${100 - (props.amount / props.max) * 100}%`;
        });
        const resourceTypeIcon = computed(() => {
            return "res-" + props.type;
        });
        const progressBarPct = computed(() => {
            return Math.round((props.amount / props.max) * 100);
        });
        const buttonDisabled = computed(() => {
            return props.storageLevel >= props.maxLevel;
        });
        const buttonClass = computed(() => {
            let classList =
                props.storageLevel >= props.maxLevel ? ["disabled"] : [];
            return classList.join(" ");
        });
        const showModal = ref(false);
        return {
            resourceBarWidth,
            resourceTypeIcon,
            progressBarPct,
            buttonDisabled,
            buttonClass,
            showModal,
            ...useI18n(),
        };
    },
};
</script>

<template>
    <button
        class="resource-type"
        :class="buttonClass"
        :aria-label="
            buttonDisabled
                ? ''
                : t('common.header.storageUpgrades.buttonAriaLabel')
        "
        :disabled="buttonDisabled"
        :aria-disabled="buttonDisabled"
        @click="showModal = true"
    >
        <span
            class="res"
            :title="
                t('common.resourceTypes.label') +
                ': ' +
                t('common.resourceTypes.' + type)
            "
            :aria-label="
                t('common.resourceTypes.label') +
                ': ' +
                t('common.resourceTypes.' + type)
            "
        >
            <span
                class="res__bar"
                :style="{ right: resourceBarWidth }"
                role="progressbar"
                aria-valuemin="0"
                aria-valuemax="100"
                :aria-valuenow="progressBarPct"
                >{{ progressBarPct + "%" }}</span
            >
            <span class="res__type">
                <icon
                    :name="resourceTypeIcon"
                    class="res__icon"
                    aria-hidden="true"
                />
            </span>
            <span class="res__amount"
                >{{ amount }} / {{ max }} ({{ progressBarPct }}%)</span
            >
        </span>
        <storage-levels
            :level="storageLevel"
            :area="type"
            :max-level="maxLevel"
        />
    </button>
    <upgrade-storage
        v-if="showModal"
        :type="type"
        :amount="amount"
        :max="max"
        :storage-level="storageLevel"
        :max-level="maxLevel"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
.resource-type {
    display: flex;

    padding: 0;
    border: 0;
    margin: 0 6px 6px 0;

    background: transparent;
    cursor: pointer;

    font-size: 15px;
    font-weight: 300;
    line-height: 1.4;

    @include themed() {
        color: t("t-light");
    }

    &:not(.disabled):hover .res {
        @include themed() {
            background-color: t("g-bunker");
        }

        &__bar {
            @include themed() {
                background: t("s-active");
            }
        }
    }

    &[disabled],
    &.disabled {
        cursor: default;
    }

    &.upgrading {
        opacity: 0.8;

        cursor: not-allowed;
    }
}

.res {
    display: flex;
    position: relative;
    align-items: center;
    justify-content: center;

    width: 190px;
    padding: 5px;
    border: 1px solid transparent;

    transition: background-color linear map-get($animation-speeds, "fast");

    @include themed() {
        background-color: rgba(t("g-raven"), 0.8);
        border-color: t("g-sunken");
    }

    &__bar {
        display: block;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;

        text-indent: -1000em;

        transition: background-color linear map-get($animation-speeds, "fast");

        @include themed() {
            background: linear-gradient(
                to bottom,
                t("s-success") 0%,
                t("s-active") 100%
            );
        }
    }

    &__icon {
        display: block;

        margin-right: 5px;
    }

    &__type {
        display: block;

        padding-right: 5px;
    }

    &__type,
    &__amount {
        display: flex;
        z-index: z("form");
        align-items: center;
    }
}
</style>
