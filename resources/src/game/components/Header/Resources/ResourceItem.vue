<script>
/******************************************************************************
 * Component: PlayerResources
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import StorageLevels from "./StorageLevels";
import Modal from "Components/Modal/Modal";
import GameButton from "Components/Button/GameButton";
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
    components: { Icon, StorageLevels, Modal, GameButton },
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
                buttonDisabled
                    ? ''
                    : t('common.resourceTypes.label') +
                      ': ' +
                      t('common.resourceTypes.' + type)
            "
            :aria-label="
                buttonDisabled
                    ? ''
                    : t('common.resourceTypes.label') +
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
    <modal
        v-if="showModal"
        :ref-id="`ResourceModal${type.toUpperCase()}`"
        :title="type"
        @close="showModal = false"
    >
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem corporis
        eius enim et eveniet exercitationem id laudantium non officia optio quae
        quidem quod ratione recusandae, sapiente similique sint voluptates.
        Alias animi autem, commodi dolor dolore doloribus, est facilis fuga hic
        iure magnam nesciunt officia provident quam quasi quos sed tempora vero?
        Adipisci distinctio dolorem doloribus ducimus eius facilis magni minus
        modi molestias, nemo nostrum quas quasi, quod soluta tempora tempore,
        totam. Dolorem iusto magnam nostrum soluta. Dolore itaque nisi numquam
        quod similique sunt. Debitis earum molestiae necessitatibus nulla
        tempore? Cum nam nesciunt officia praesentium recusandae saepe
        temporibus vitae voluptate voluptatibus! Lorem ipsum dolor sit amet,
        consectetur adipisicing elit. Autem corporis eius enim et eveniet
        exercitationem id laudantium non officia optio quae quidem quod ratione
        recusandae, sapiente similique sint voluptates. Alias animi autem,
        commodi dolor dolore doloribus, est facilis fuga hic iure magnam
        nesciunt officia provident quam quasi quos sed tempora vero? Adipisci
        distinctio dolorem doloribus ducimus eius facilis magni minus modi
        molestias, nemo nostrum quas quasi, quod soluta tempora tempore, totam.
        Dolorem iusto magnam nostrum soluta. Dolore itaque nisi numquam quod
        similique sunt. Debitis earum molestiae necessitatibus nulla tempore?
        Cum nam nesciunt officia praesentium recusandae saepe temporibus vitae
        voluptate voluptatibus!
        <template v-slot:actions>
            <game-button text-string="Upgrade" icon-name="done" />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.resource-type {
    display: flex;

    padding: 0;
    border: 0;
    margin: 0 0.6rem 0.6rem 0;

    background: transparent;
    cursor: pointer;

    font-size: 1.5rem;
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

    width: 19rem;
    padding: 0.5rem;
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

        margin-right: 0.5rem;
    }

    &__type {
        display: block;

        padding-right: 0.5rem;
    }

    &__type,
    &__amount {
        display: flex;
        z-index: z("form");
        align-items: center;
    }
}
</style>
