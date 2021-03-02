<script>
/******************************************************************************
 * PageComponent: MailboxOverview
 *****************************************************************************/
import { useStore } from "vuex";
import { computed, ref } from "vue";
import AppCheckbox from "Components/Checkbox/AppCheckbox";
import Icon from "Components/Icon/Icon";
import MailboxOverviewRenderMessage from "../Mailbox/MailboxOverviewRenderMessage";
import MailboxPagination from "Pages/Messages/Mailbox/MailboxPagination";
export default {
    name: "MailboxOverview",
    props: {
        messages: Array,
        mailbox: String, // "in" || "out"
    },
    components: {
        AppCheckbox,
        Icon,
        MailboxOverviewRenderMessage,
        MailboxPagination,
    },
    setup(props) {
        const store = useStore();

        const sortDesc = ref(true);
        const page = ref(1);
        const perPage = computed({
            get: () => store.state.messages.perPage[props.mailbox],
            set: (value) =>
                store.commit("messages/SET_PER_PAGE", {
                    mailbox: props.mailbox,
                    perPage: value,
                }),
        });

        /**
         * @function all sorted messages, without pagination
         * @type {ComputedRef<*>}
         */
        const messagesSorted = computed(() =>
            props.messages
                .slice() // create a shallow copy so we don't mutate props.
                .sort((a, b) =>
                    sortDesc.value
                        ? b.timestamp - a.timestamp
                        : a.timestamp - b.timestamp
                )
        );

        const displayedMessages = computed(() => {
            const from = page.value * perPage.value - perPage.value;
            const to = page.value * perPage.value;
            return messagesSorted.value.slice(from, to);
        });

        const onPageChange = (changedPage) => (page.value = changedPage);
        const onPerPageChange = (changedPerPage) =>
            (perPage.value = changedPerPage);

        const onDeleteAllChecked = () => {
            store.commit(
                "messages/SET_MASS_DELETE_IDS",
                displayedMessages.value.map((m) => m.id)
            );
        };
        const onDeleteAllUnchecked = () => {
            store.commit("messages/SET_MASS_DELETE_IDS", []);
        };

        const massDeleteMessageIds = computed(
            () => store.state.messages.massDeleteIds
        );

        return {
            massDeleteMessageIds,
            page,
            perPage,
            onPageChange,
            onPerPageChange,
            onDeleteAllChecked,
            onDeleteAllUnchecked,
            sortDesc,
            displayedMessages,
        };
    },
};
</script>

<template>
    <mailbox-pagination
        v-if="messages.length > 0"
        :current-page="page"
        :per-page="perPage"
        :num-messages="messages.length"
        @changepage="onPageChange"
        @changeperpage="onPerPageChange"
    />
    <div class="mailbox" v-if="messages.length > 0">
        <ul class="messages">
            <li class="messages__delete">
                <app-checkbox
                    ref-id="deleteAll"
                    :checked-initially="
                        massDeleteMessageIds.length === displayedMessages.length
                    "
                    @checked="onDeleteAllChecked"
                    @unchecked="onDeleteAllUnchecked"
                />
            </li>
            <li class="messages__from">
                <span v-if="mailbox === 'in' || mailbox === 'sys'">{{
                    $t("messages.mailbox.from")
                }}</span>
                <span v-if="mailbox === 'out'">{{
                    $t("messages.mailbox.to")
                }}</span>
            </li>
            <li class="messages__timestamp">
                <span v-if="mailbox === 'in' || mailbox === 'sys'">{{
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
            v-for="message in displayedMessages"
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
    grid-template-columns: 32px 1fr 1fr;

    padding: 0;
    margin: 0;
    grid-gap: 1px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: 40px 2fr 1fr 2fr;

        grid-gap: 2px;
    }

    &__subject {
        display: none;

        @include respond-to("medium") {
            display: block;
        }
    }

    &__delete,
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
