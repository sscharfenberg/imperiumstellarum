<script>
/******************************************************************************
 * PageComponent: MessagesNewBody
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    name: "MessagesNewBody",
    setup() {
        const store = useStore();
        const body = computed({
            get: () => store.state.messages.new.body,
            set: (value) => {
                store.commit("messages/SET_BODY", value);
            },
        });
        const bodyRules = window.rules.messages.body;
        const onKeyDown = (ev) => {
            if (body.value.length > bodyRules.max) {
                body.value = body.value.slice(0, bodyRules.max);
            }
            if (
                body.value.length === bodyRules.max &&
                ev.key !== "Backspace" &&
                ev.key !== "Delete"
            ) {
                ev.preventDefault();
            }
        };
        return { body, bodyRules, onKeyDown };
    },
};
</script>

<template>
    <div class="form-row has-divider">
        <div class="label">
            <label for="messageBody">{{ $t("messages.new.body.label") }}</label>
        </div>
        <div class="input">
            <textarea
                class="form-control"
                id="messageBody"
                v-model="body"
                @keydown="onKeyDown"
            ></textarea>
        </div>
        <div class="descr">
            {{ $t("messages.new.body.charsUsed") }}:
            <strong>{{ body.length }}</strong
            >, {{ $t("messages.new.body.charsLeft") }}:
            <strong>{{ bodyRules.max - body.length }}</strong>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 16px 0;
}
textarea {
    height: 20vh;
}
.form-row.form-row .descr {
    @include respond-to("medium") {
        margin-left: auto;
        flex: 0 0 70%;
    }

    strong {
        @include themed() {
            color: t("t-bright");
        }
    }
}
</style>
