<script>
/******************************************************************************
 * PageComponent: MessagesNewSelectRecipient
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "MessagesNewAddRecipient",
    props: {
        playerId: String,
        ticker: String,
        name: String,
        relation: Number,
        locale: String,
    },
    setup(props) {
        const store = useStore();
        const recipients = computed(() => store.state.messages.new.recipients);
        const addDisabled = computed(
            () =>
                recipients.value.length >= window.rules.messages.recipients.max
        );
        const onClick = () => {
            if (!recipients.value.includes(props.playerId)) {
                store.commit("messages/ADD_RECIPIENT", props.playerId);
            }
        };
        return { recipients, onClick, addDisabled };
    },
};
</script>

<template>
    <button
        class="recipient"
        @click="onClick"
        @keyup.enter="onClick"
        :class="{ selected: recipients.includes(playerId) }"
        :disabled="addDisabled"
    >
        <span class="recipient__name">[{{ ticker }}] {{ name }}</span>
        <span
            class="recipient__relation"
            :class="{
                allied: relation === 2,
                neutral: relation === 1,
                hostile: relation === 0,
            }"
        >
            {{ $t("diplomacy.status." + relation) }}
            ({{ relation }})
        </span>
    </button>
</template>

<style lang="scss" scoped>
.recipient {
    display: flex;
    align-items: center;

    padding: 4px;
    border: 2px solid transparent;

    background: transparent;

    outline: 0;
    cursor: pointer;

    text-align: left;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        color: t("t-light");
        border-color: t("g-deep");
    }

    &:hover,
    &:focus {
        @include themed() {
            background-color: t("g-ebony");
            border-color: t("g-asher");
        }
    }

    &.selected {
        @include themed() {
            border-color: t("b-gorse");
        }
    }

    &__name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__relation {
        padding: 4px;
        border: 1px solid transparent;
        margin-left: auto;

        white-space: nowrap;

        &.allied {
            @include themed() {
                border-color: t("s-success");
            }
        }
        &.hostile {
            @include themed() {
                border-color: t("s-error");
            }
        }
        &.neutral {
            @include themed() {
                border-color: t("s-building");
            }
        }
    }

    &:disabled {
        opacity: 0.6;

        cursor: not-allowed;
    }
}
</style>
