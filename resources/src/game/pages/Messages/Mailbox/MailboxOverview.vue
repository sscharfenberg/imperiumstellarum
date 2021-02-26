<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import { computed, ref } from "vue";
import Icon from "Components/Icon/Icon";
import MailboxOverviewRenderMessage from "../Mailbox/MailboxOverviewRenderMessage";
export default {
    name: "MessagesInbox",
    props: {
        messages: Array,
        mailbox: String, // "in" || "out"
    },
    components: { Icon, MailboxOverviewRenderMessage },
    setup(props) {
        const onClick = (id) => console.log("clicked", id);
        const sortDesc = ref(true);
        const messagesSorted = computed(() =>
            props.messages
                .slice()
                .sort((a, b) =>
                    sortDesc.value
                        ? b.timestamp - a.timestamp
                        : a.timestamp - b.timestamp
                )
        );
        return { onClick, sortDesc, messagesSorted };
    },
};
</script>

<template>
    <div class="mailbox" v-if="messages.length > 0">
        <ul class="messages">
            <li class="messages__from">
                <span v-if="mailbox === 'in'">{{
                    $t("messages.mailbox.from")
                }}</span>
                <span v-if="mailbox === 'out'">{{
                    $t("messages.mailbox.to")
                }}</span>
            </li>
            <li class="messages__timestamp">
                <span v-if="mailbox === 'in'">{{
                    $t("messages.mailbox.recieved")
                }}</span>
                <span v-if="mailbox === 'out'">{{
                    $t("messages.mailbox.sent")
                }}</span>
                <button class="messages__sort" @click="sortDesc = !sortDesc">
                    <icon name="expand_less" :class="{ active: !sortDesc }" />
                    <icon name="expand_more" :class="{ active: sortDesc }" />
                </button>
            </li>
            <li class="messages__subject">
                {{ $t("messages.mailbox.subject") }}
            </li>
        </ul>
        <mailbox-overview-render-message
            v-for="message in messagesSorted"
            :key="message.id"
            :mailbox="mailbox"
            :message-id="message.id"
            :sender-id="message.senderId"
            :recipient-ids="message.recipientIds"
            :datetime="message.timestamp"
            :subject="message.subject"
            :body="message.body"
            :read="message.read"
        />
    </div>
    <div v-if="messages.length === 0" class="mailbox__empty">
        {{ $t("messages.mailbox.empty") }}
    </div>
</template>

<style lang="scss" scoped>
.mailbox {
    width: 100%;
    padding: 4px;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }
}

.messages {
    display: grid;
    grid-template-columns: 1fr 1fr;

    padding: 0;
    margin: 0;
    grid-gap: 1px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: 2fr 1fr 2fr;

        grid-gap: 2px;
    }

    &__subject {
        display: none;

        @include respond-to("medium") {
            display: block;
        }
    }

    &__from,
    &__timestamp,
    &__subject {
        padding: 4px;
        border: 1px solid transparent;

        @include respond-to("medium") {
            padding: 8px;
        }

        @include themed() {
            background-color: t("g-ebony");
            border-color: t("g-deep");
        }
    }

    &__timestamp {
        display: flex;
        position: relative;
        align-items: center;
    }

    &__sort {
        display: flex;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        flex-direction: column;

        padding: 0 4px;
        border: 0;
        margin: 0;

        background-color: transparent;
        outline: 0;
        cursor: pointer;

        transition: background-color map-get($animation-speeds, "fast") linear,
            color map-get($animation-speeds, "fast") linear;

        @include themed() {
            background-color: t("g-deep");
            color: t("t-subdued");
        }

        &:hover {
            @include themed() {
                background-color: t("g-sunken");
            }
        }

        .icon {
            width: 14px;
            height: 14px;
            flex: 0 0 14px;

            @include respond-to("medium") {
                width: 19px;
                height: 19px;
                flex: 0 0 19px;
            }

            &.active {
                @include themed() {
                    color: t("b-viking");
                }
            }
        }
    }
}
</style>
