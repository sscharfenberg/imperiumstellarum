<script>
/******************************************************************************
 * PageComponent: MessagesInbox
 *****************************************************************************/
import MailboxOverviewRenderMessage from "../Mailbox/MailboxOverviewRenderMessage";
export default {
    name: "MessagesInbox",
    props: {
        messages: Array,
        mailbox: String, // "in" || "out"
    },
    components: { MailboxOverviewRenderMessage },
    setup() {
        return {};
    },
};
</script>

<template>
    <div class="mailbox">
        <ul class="messages">
            <li class="messages__from">From</li>
            <li class="messages__subject">Subject</li>
            <li class="messages__timestamp" v-if="mailbox === 'in'">
                Recieved
            </li>
            <li class="messages__timestamp" v-if="mailbox === 'out'">Sent</li>
            <mailbox-overview-render-message
                v-for="message in messages"
                :key="message.id"
                :message-id="message.id"
                :sender-id="message.senderId"
                :datetime="message.timestamp"
                :subject="message.subject"
                :body="message.body"
            />
        </ul>
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
    grid-gap: 2px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: 1fr 2fr 1fr;

        grid-gap: 4px;
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
            border-color: t("g-asher");
        }
    }
}
</style>
