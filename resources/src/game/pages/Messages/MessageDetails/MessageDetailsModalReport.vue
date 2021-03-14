<script>
/******************************************************************************
 * PageComponent: MessageDetailsModalReport
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import { formatMessageBody } from "@/game/helpers/format";
export default {
    name: "MessageDetailsModalReport",
    props: {
        comment: String,
        resolved: Boolean,
        adminMessageId: String,
    },
    components: { SubHeadline },
    setup(props) {
        const store = useStore();
        const adminReply = computed(() =>
            store.getters["messages/messageById"](props.adminMessageId)
        );
        return { formatMessageBody, adminReply };
    },
};
</script>

<template>
    <sub-headline :headline="$t('messages.mailbox.report.headline')" />
    <ul class="stats">
        <li class="text-left">
            {{ $t("messages.mailbox.report.commentLabel") }}
        </li>
        <li class="text-left" v-html="formatMessageBody(comment)" />
        <li class="text-left">
            {{ $t("messages.mailbox.report.resolvedLabel") }}
        </li>
        <li class="text-left">
            {{ resolved ? $t("common.boolean.yes") : $t("common.boolean.no") }}
        </li>
        <li v-if="adminReply.id" class="text-left">
            {{ $t("messages.mailbox.report.replyLabel") }}
        </li>
        <li
            v-if="adminReply.id"
            class="text-left"
            v-html="formatMessageBody(adminReply.body)"
        />
    </ul>
</template>

<style lang="scss" scoped>
.stats {
    grid-template-columns: calc(40% - 2px) calc(60% - 2px);
}
</style>
