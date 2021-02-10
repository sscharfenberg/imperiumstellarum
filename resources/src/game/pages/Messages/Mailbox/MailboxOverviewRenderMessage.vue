<script>
/******************************************************************************
 * PageComponent: MailboxOverviewRenderMessage
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { format, addSeconds } from "date-fns";
export default {
    name: "MailboxOverviewRenderMessage",
    props: {
        messageId: String,
        senderId: String,
        datetime: Number,
        subject: String,
        body: String,
    },
    setup(props) {
        const store = useStore();
        const sender = computed(() => {
            return store.getters["messages/playerById"](props.senderId);
        });
        const senderTicker = computed(() => {
            if (!sender.value) return undefined;
            return sender.value.ticker;
        });
        const senderName = computed(() => {
            if (!sender.value) return undefined;
            return sender.value.name;
        });
        const timestamp = computed(() => {
            console.log(props.datetime);
            return format(
                addSeconds(new Date(), props.datetime),
                "dd.MM.uuuu HH:mm:ss"
            );
        });

        return { senderTicker, senderName, timestamp };
    },
};
</script>

<template>
    <li class="message__sender">[{{ senderTicker }}] {{ senderName }}</li>
    <li class="message__subject">subject whoa {{ subject }}</li>
    <li class="message__timestamp">{{ timestamp }}</li>
    <li class="message__body">{{ body }}</li>
</template>

<style lang="scss" scoped>
.message {
    &__sender,
    &__timestamp,
    &__subject,
    &__body {
        padding: 4px;
        border: 1px solid transparent;

        @include respond-to("medium") {
            padding: 8px;
        }

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }
    }

    &__sender {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__subject {
        grid-column: span 2;

        @include respond-to("medium") {
            grid-column: 2;
        }
    }

    &__body {
        grid-column: span 2;

        @include respond-to("medium") {
            grid-column: span 3;
        }
    }
}
</style>
