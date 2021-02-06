<script>
/******************************************************************************
 * PageComponent: MessagesNewShowRecipient
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import PlayerLocale from "Components/Icon/PlayerLocale";
export default {
    name: "MessagesNewShowRecipient",
    props: {
        playerId: String,
        ticker: String,
        name: String,
        relation: Number,
        locale: String,
    },
    components: { PlayerLocale },
    setup(props) {
        const store = useStore();
        const recipientId = computed(
            () => store.state.messages.new.selectedRecipientId
        );
        const onClick = () => {
            const players = store.state.messages.players;
            store.commit("messages/SET_RECIPIENT_ID", props.playerId);
            store.commit(
                "messages/SET_SEARCH_TICKER",
                players.find((p) => p.id === props.playerId).ticker
            );
        };
        return { recipientId, onClick };
    },
};
</script>

<template>
    <button
        class="recipient"
        @click="onClick"
        @keyup.enter="onClick"
        :class="{ selected: recipientId === playerId }"
    >
        <player-locale :locale="locale" />
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

    padding: 8px;
    border: 2px solid transparent;

    background: transparent;

    outline: 0;
    cursor: pointer;

    text-align: left;

    transition: background-color map-get($animation-speeds, "fast") linear,
        border-color map-get($animation-speeds, "fast") linear;

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

        margin-left: 4px;

        white-space: nowrap;
        text-overflow: ellipsis;

        @include respond-to("medium") {
            margin-left: 8px;
        }
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
}
</style>
