<script>
/******************************************************************************
 * PageComponent: MessagesNewChooseRecipient
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "MessagesNewChooseRecipient",
    components: { Icon },
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
        const recipientRelation = (playerId) => {
            const relation = relations.value.find(
                (r) => r.playerId === playerId
            );
            if (relation) return relation.effective;
            return 1;
        };

        return {
            players,
            relations,
            maxTickerLength,
            ticker,
            recipientRelation,
        };
    },
};
</script>

<template>
    <nav class="form-row">
        <div class="label">
            <label for="fleetName">{{
                $t("messages.new.recipient.tickerLabel")
            }}</label>
        </div>
        <div class="input">
            <input
                type="text"
                class="form-control"
                id="fleetName"
                :placeholder="$t('messages.new.recipient.tickerPlaceholder')"
                autocomplete="off"
                v-model="ticker"
                :maxlength="maxTickerLength"
                @keyup="ticker = ticker.toUpperCase()"
            />
            <div class="addon"><icon name="empire" /></div>
        </div>
        <div
            class="descr recipients"
            v-if="players.length > 0 && ticker.length > 0"
        >
            <button
                class="recipient"
                v-for="player in players"
                :key="player.id"
            >
                <span class="recipient__name"
                    >[{{ player.ticker }}] {{ player.name }}</span
                >
                <span
                    class="recipient__relation"
                    :class="{
                        allied: recipientRelation(player.id) === 2,
                        neutral: recipientRelation(player.id) === 1,
                        hostile: recipientRelation(player.id) === 0,
                    }"
                >
                    {{ $t("diplomacy.status." + recipientRelation(player.id)) }}
                    ({{ recipientRelation(player.id) }})
                </span>
            </button>
        </div>
        <div
            class="descr recipients recipients--none"
            v-if="players.length === 0 && ticker.length > 0"
        >
            No empires with this ticker
        </div>
        {{ relations }}
    </nav>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0;
}

.form-row > .descr.recipients {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

    padding: 4px;
    margin: 8px 0 0 0;
    grid-gap: 4px;

    list-style: none;

    @include respond-to("medium") {
        margin: 16px 0 0 0;
    }

    &--none {
        margin-left: auto;
        flex: 0 0 70%;
    }

    @include themed() {
        border-color: t("g-deep");
    }
}

.recipient {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 8px;
    border: 1px solid transparent;

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

    &:hover {
        @include themed() {
            background-color: t("g-ebony");
            border-color: t("g-asher");
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
