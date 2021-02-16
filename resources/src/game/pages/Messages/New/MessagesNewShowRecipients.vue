<script>
/******************************************************************************
 * PageComponent: MessagesNewShowRecipients
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "MessagesNewShowRecipients",
    components: { GameButton, SubHeadline },
    setup() {
        const store = useStore();
        const recipients = computed(() => {
            const recipientIds = store.state.messages.new.recipients;
            return store.state.messages.players.filter((p) =>
                recipientIds.includes(p.id)
            );
        });
        const onClear = () => {
            store.commit("messages/RESET_RECIPIENTS");
        };
        const onRemove = (playerId) => {
            store.commit("messages/REMOVE_RECIPIENT", playerId);
        };
        return { recipients, onClear, onRemove };
    },
};
</script>

<template>
    <sub-headline :headline="$t('messages.new.recipients.headline')">
        <game-button
            icon-name="cancel"
            @click="onClear"
            tabindex="-1"
            :text-string="$t('messages.new.recipients.clear')"
            :size="0"
        />
    </sub-headline>
    <ul class="recipients">
        <li
            class="recipients__recipient"
            v-for="player in recipients"
            :key="player.id"
        >
            <span class="recipients__recipient-name">
                [{{ player.ticker }}]
                {{ player.name }}
            </span>
            <game-button
                icon-name="cancel"
                :size="0"
                tabindex="-1"
                @click="onRemove(player.id)"
            />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.recipients {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));

    padding: 0 0 16px 0;
    margin: 0 0 16px 0;
    grid-gap: 4px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }

    @include themed() {
        border-bottom: 2px solid t("g-ebony");
    }

    &__recipient {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 4px;
        clip-path: polygon(
            0 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% 100%,
            5px 100%,
            0 calc(100% - 5px)
        );

        @include respond-to("medium") {
            padding: 4px 8px;
            clip-path: polygon(
                0 0,
                calc(100% - 10px) 0,
                100% 10px,
                100% 100%,
                10px 100%,
                0 calc(100% - 10px)
            );
        }

        @include themed() {
            background-color: t("g-deep");
        }
    }

    &__recipient-name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }
}
</style>
