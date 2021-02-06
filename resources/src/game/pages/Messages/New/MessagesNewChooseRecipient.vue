<script>
/******************************************************************************
 * PageComponent: MessagesNewChooseRecipient
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
import MessagesNewShowRecipient from "./MessagesNewShowRecipient";
export default {
    name: "MessagesNewChooseRecipient",
    components: { GameButton, Icon, MessagesNewShowRecipient },
    setup() {
        const store = useStore();
        const players = computed(() =>
            store.state.messages.players.filter((p) =>
                p.ticker.includes(ticker.value)
            )
        );
        const relations = computed(() => store.state.messages.relations);
        const maxTickerLength = window.rules.player.ticker.max;
        const ticker = computed({
            get: () => store.state.messages.new.tickerSearch,
            set: (value) => {
                store.commit("messages/SET_SEARCH_TICKER", value);
            },
        });
        const recipientId = computed(
            () => store.state.messages.new.recipientId
        );
        const recipientRelation = (playerId) => {
            const relation = relations.value.find(
                (r) => r.playerId === playerId
            );
            if (relation) return relation.effective;
            return 1;
        };
        const onClear = () => {
            store.commit("messages/SET_SEARCH_TICKER", "");
            store.commit("messages/SET_RECIPIENT_ID", "");
        };

        return {
            players,
            relations,
            maxTickerLength,
            ticker,
            recipientId,
            recipientRelation,
            onClear,
        };
    },
};
</script>

<template>
    <div class="form-row has-divider">
        <div class="label">
            <label for="recipientTicker">{{
                $t("messages.new.recipient.tickerLabel")
            }}</label>
        </div>
        <div class="input">
            <input
                type="text"
                class="form-control"
                id="recipientTicker"
                :placeholder="$t('messages.new.recipient.tickerPlaceholder')"
                autocomplete="off"
                v-model="ticker"
                :maxlength="maxTickerLength"
                @keyup="ticker = ticker.toUpperCase()"
            />
            <div class="addon" v-if="!ticker.length">
                <icon name="empire" />
            </div>
            <div
                class="addon cancel"
                v-if="ticker.length || recipientId.length"
            >
                <game-button
                    icon-name="cancel"
                    :size="1"
                    :title="$t('messages.new.recipient.cancel')"
                    :aria-label="$t('messages.new.recipient.cancel')"
                    @click="onClear"
                    @keyup.enter="onClear"
                />
            </div>
        </div>
        <nav
            class="descr recipients"
            v-if="players.length > 0 && ticker.length > 0"
        >
            <messages-new-show-recipient
                v-for="player in players"
                :key="player.id"
                :player-id="player.id"
                :ticker="player.ticker"
                :name="player.name"
                :locale="player.locale"
                :relation="recipientRelation(player.id)"
            />
        </nav>
        <div
            class="descr recipients recipients--none"
            v-if="players.length === 0 && ticker.length > 0"
        >
            {{ $t("messages.new.recipient.none") }}
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}

.form-row > .descr.recipients {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

    padding: 0;
    border-width: 0;
    margin: 8px 0 0 0;
    grid-gap: 4px;

    list-style: none;

    @include respond-to("medium") {
        margin: 16px 0 0 0;
    }

    &--none {
        padding: 8px;
        border-width: 2px;
        margin-left: auto;
        flex: 0 0 70%;
    }
}

.addon.cancel {
    display: flex;
    align-items: center;

    padding: 0 5px;
}
</style>
