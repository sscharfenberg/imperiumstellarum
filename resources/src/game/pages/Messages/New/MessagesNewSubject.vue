<script>
/******************************************************************************
 * PageComponent: MessagesNewSubject
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Icon from "Components/Icon/Icon";
export default {
    name: "MessagesNewSubject",
    components: { Icon },
    setup() {
        const store = useStore();
        const subject = computed({
            get: () => store.state.messages.new.subject,
            set: (value) => {
                store.commit("messages/SET_SUBJECT", value);
            },
        });
        const subjectRules = window.rules.messages.subject;
        return { subject, subjectRules };
    },
};
</script>

<template>
    <div class="form-row has-divider">
        <div class="label">
            <label for="messageSubject">{{
                $t("messages.new.subject.label")
            }}</label>
        </div>
        <div class="input">
            <input
                type="text"
                class="form-control"
                id="messageSubject"
                v-model="subject"
                aria-required="true"
                :maxlength="subjectRules.max"
                :placeholder="$t('messages.new.subject.placeholder')"
            />
            <div class="addon"><icon name="lightbulb" /></div>
        </div>
        <div class="descr">
            {{
                $t("messages.new.subject.description", {
                    min: subjectRules.min,
                    max: subjectRules.max,
                })
            }}
        </div>
    </div>
</template>

<style lang="scss" scoped>
.form-row {
    padding: 0 0 16px 0;
}
.form-row.form-row .descr {
    @include respond-to("medium") {
        margin-left: auto;
        flex: 0 0 70%;
    }
}
</style>
