<script>
/******************************************************************************
 * PageComponent: MessagesNewRepliesTo
 *****************************************************************************/
import { formatMessageBody } from "@/game/helpers/format";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "MessagesNewRepliesTo",
    props: {
        messageBody: String,
        senderTicker: String,
    },
    components: { SubHeadline },
    setup() {
        return { formatMessageBody };
    },
};
</script>

<template>
    <sub-headline :headline="$t('messages.new.repliesTo.headline')" />
    <div class="form-row has-divider">
        <div class="label">
            <label>{{
                $t("messages.new.repliesTo.label", { ticker: senderTicker })
            }}</label>
        </div>
        <div class="input" v-html="formatMessageBody(messageBody)"></div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}
</style>
