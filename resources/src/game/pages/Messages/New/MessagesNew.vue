<script>
/******************************************************************************
 * PageComponent: MessagesNew
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import MessagesNewBody from "Pages/Messages/New/MessagesNewBody";
import MessagesNewChooseRecipient from "./MessagesNewChooseRecipient";
import MessagesNewShowRecipients from "./MessagesNewShowRecipients";
import MessagesNewSubject from "Pages/Messages/New/MessagesNewSubject";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "MessagesNew",
    components: {
        GameButton,
        MessagesNewBody,
        MessagesNewChooseRecipient,
        MessagesNewShowRecipients,
        MessagesNewSubject,
        SubHeadline,
    },
    setup() {
        const store = useStore();
        const recipients = computed(() => store.state.messages.new.recipients);
        const subject = computed(() => store.state.messages.new.subject);
        const body = computed(() => store.state.messages.new.body);
        const rules = window.rules.messages;

        const onSubmit = () => {
            store.dispatch("messages/SEND_MESSAGE", {
                recipients: store.state.messages.new.recipients,
                subject: store.state.messages.new.subject,
                body: store.state.messages.new.body,
                repliesTo: "",
            });
        };

        const onCancel = () => {
            store.commit("messages/SET_SEARCH_TICKER", "");
            store.commit("messages/RESET_RECIPIENTS");
            store.commit("messages/SET_SUBJECT", "");
            store.commit("messages/SET_BODY", "");
        };

        return { recipients, subject, body, rules, onSubmit, onCancel };
    },
};
</script>

<template>
    <div class="new-message app-form">
        <messages-new-choose-recipient />
        <messages-new-show-recipients v-if="recipients.length > 0" />
        <sub-headline :headline="$t('messages.new.hdlCompose')" />
        <messages-new-subject />
        <messages-new-body />
        <div class="form-row submit">
            <div class="input input--justify no-label">
                <game-button
                    icon-name="send"
                    text-string="Send Message"
                    :primary="true"
                    :disabled="
                        recipients.length === 0 ||
                        subject.length < rules.subject.min ||
                        body.length < rules.body.min
                    "
                    @click="onSubmit"
                />
                <game-button
                    icon-name="cancel"
                    text-string="Cancel"
                    :disabled="
                        recipients.length === 0 ||
                        (!subject.length && !body.length)
                    "
                    @click="onCancel"
                />
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.new-message {
    padding: 8px;
    margin: 0;

    @include respond-to("medium") {
        padding: 16px;
    }

    @include themed() {
        background-color: t("g-sunken");
    }

    > .form-row.submit {
        padding: 16px 0 0 0;
    }
}
</style>
