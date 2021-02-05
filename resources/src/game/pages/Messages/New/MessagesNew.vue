<script>
/******************************************************************************
 * PageComponent: MessagesNew
 *****************************************************************************/
//import { computed } from "vue";
//import { useStore } from "vuex";
import MessagesNewChooseRecipient from "./MessagesNewChooseRecipient";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "MessagesNew",
    components: { MessagesNewChooseRecipient, SubHeadline },
    setup() {
        //const store = useStore();
        return {};
    },
};
</script>

<template>
    <div class="new-message app-form">
        <sub-headline headline="Choose Recipient" />
        <messages-new-choose-recipient />
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
}
</style>
