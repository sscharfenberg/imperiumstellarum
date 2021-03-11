<script>
/******************************************************************************
 * PageComponent: ReportMessageModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { formatMessageBody } from "@/game/helpers/format";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
export default {
    name: "ReportMessageModal",
    props: {
        messageId: String,
    },
    components: { CollapsibleItem, GameButton, Modal },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const requesting = computed(() => store.state.messages.requesting);
        const message = computed(() =>
            store.getters["messages/messageById"](props.messageId)
        );
        const player = (playerId) =>
            store.getters["messages/playerById"](playerId);
        const you = computed(() => `[${store.state.empireTicker}]`);
        const yourId = computed(() => store.state.empireId);
        const explanation = computed({
            get: () => store.state.messages.reportMessageComment,
            set: (value) =>
                store.commit("messages/SET_REPORT_MESSAGE_COMMENT", value),
        });
        const rules = window.rules.messages.reportComment;
        const onSubmit = () => {
            store.dispatch("messages/REPORT_MESSAGE", {
                messageId: props.messageId,
                comment: explanation.value,
            });
            emit("close");
        };
        const onKeyDown = (ev) => {
            if (explanation.value.length > rules.max) {
                explanation.value = explanation.value.slice(0, rules.max);
            }
            if (
                explanation.value.length === rules.max &&
                ev.key !== "Backspace" &&
                ev.key !== "Delete"
            ) {
                ev.preventDefault();
            }
        };
        return {
            requesting,
            message,
            player,
            you,
            yourId,
            formatMessageBody,
            rules,
            onSubmit,
            explanation,
            onKeyDown,
        };
    },
};
</script>

<template>
    <modal
        :title="$t('messages.report.title')"
        @close="$emit('close')"
        :full-size="false"
    >
        <ul class="stats">
            <li class="text-left stats--two-col">
                {{ $t("messages.report.explanation") }}
            </li>
        </ul>

        <collapsible-item
            :collapsible-id="`reportMessage-${messageId}`"
            :alt-bg="true"
        >
            <template v-slot:topic>
                {{ $t("messages.report.message") }}
            </template>

            <ul class="stats">
                <li class="text-left">{{ $t("messages.details.sender") }}</li>
                <li class="text-left">
                    [{{ player(message.senderId).ticker }}]
                    {{ player(message.senderId).name }}
                </li>
                <li class="text-left">
                    {{
                        $tc(
                            "messages.details.recipients",
                            message.recipientIds.length
                        )
                    }}
                </li>
                <li class="text-left">
                    <span
                        v-for="(recipient, index) in message.recipientIds"
                        :key="recipient"
                    >
                        <span v-if="recipient === yourId">{{
                            $t("messages.details.you", { ticker: you })
                        }}</span
                        ><span v-else>[{{ player(recipient).ticker }}]</span
                        ><span v-if="index !== message.recipientIds.length - 1"
                            >,
                        </span>
                    </span>
                </li>

                <li class="stats--two-col text-left featured">
                    {{ $t("messages.details.subject") }}:
                </li>
                <li class="stats--two-col text-left">
                    {{ message.subject }}
                </li>

                <li class="stats--two-col text-left featured">
                    {{ $t("messages.details.body") }}:
                </li>
                <li
                    class="stats--two-col text-left"
                    v-html="formatMessageBody(message.body)"
                />
            </ul>
        </collapsible-item>

        <div class="app-form">
            <div class="form-row">
                <div class="label">
                    <label for="reportComment">{{
                        $t("messages.report.commentLabel")
                    }}</label>
                </div>
                <div class="input">
                    <textarea
                        class="form-control"
                        id="reportComment"
                        :placeholder="$t('messages.report.commentPlaceholder')"
                        v-model="explanation"
                        @keydown="onKeyDown"
                    ></textarea>
                </div>
                <div class="descr">
                    {{ $t("messages.new.body.charsUsed") }}:
                    <strong>{{ explanation.length }}</strong
                    >, {{ $t("messages.new.body.charsLeft") }}:
                    <strong>{{ rules.max - explanation.length }}</strong>
                </div>
            </div>
        </div>

        <ul class="stats abuse">
            <li class="text-left stats--two-col featured">
                {{ $t("messages.report.abuse") }}
            </li>
        </ul>

        <template v-slot:actions>
            <game-button
                :text-string="$t('messages.report.submit')"
                icon-name="warning"
                :loading="requesting"
                :disabled="
                    explanation.length < rules.min ||
                    explanation.length > rules.max
                "
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
.app-form {
    padding: 0;
    margin: 0;

    @include themed() {
        background-color: t("g-sunken");
    }

    .form-row.form-row {
        padding: 8px 0 0 0;

        .descr strong {
            @include themed() {
                color: t("t-bright");
            }
        }
    }

    .label {
        padding-bottom: 8px;
        flex: 0 0 100%;
    }

    .input {
        flex: 0 0 100%;
    }
}

.stats.abuse {
    margin: 16px 0 0 0;
}

textarea {
    height: 88px;
}
</style>
