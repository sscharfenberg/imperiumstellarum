<script>
/******************************************************************************
 * PageComponent: DiplomacyShowPlayerModal
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import Modal from "Components/Modal/Modal";
import Popover from "Components/Popover/Popover";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "DiplomacyShowPlayerModal",
    props: {
        playerId: String,
        playerTicker: String,
        playerName: String,
        relationSet: Number,
        relationRecipientSet: Number,
        relationEffective: Number,
    },
    components: { Modal, Popover, SubHeadline },
    emits: ["close"],
    setup() {
        const store = useStore();
        const empireTicker = computed(() => store.state.empireTicker);
        return { empireTicker };
    },
};
</script>

<template>
    <modal
        :title="
            $t('diplomacy.modal.headline', {
                ticker: playerTicker,
                name: playerName,
            })
        "
        @close="$emit('close')"
    >
        <p style="display: flex; align-items: center">
            <popover>
                {{ $t("diplomacy.modal.explanation") }}
            </popover>
            Fuddel Faddel
        </p>
        <ul class="stats">
            <li class="text-left">
                [{{ empireTicker }}] => [{{ playerTicker }}]
            </li>
            <li
                class="text-left"
                :class="{
                    allied: relationSet === 2,
                    neutral: relationSet === 1,
                    hostile: relationSet === 0,
                }"
            >
                {{ relationSet !== 9 ? relationSet : "-" }} ({{
                    $t("diplomacy.status." + relationSet)
                }})
            </li>

            <li class="text-left">
                [{{ playerTicker }}] => [{{ empireTicker }}]
            </li>
            <li
                class="text-left"
                :class="{
                    allied: relationRecipientSet === 2,
                    neutral: relationRecipientSet === 1,
                    hostile: relationRecipientSet === 0,
                }"
            >
                {{ relationRecipientSet !== 9 ? relationRecipientSet : "-" }}
                ({{ $t("diplomacy.status." + relationRecipientSet) }})
            </li>

            <li class="text-left">
                {{ $t("diplomacy.modal.labelEffective") }}
            </li>
            <li
                class="text-left"
                :class="{
                    allied: relationEffective === 2,
                    neutral: relationEffective === 1,
                    hostile: relationEffective === 0,
                }"
            >
                {{ relationEffective }} ({{
                    $t("diplomacy.status." + relationEffective)
                }})
            </li>
        </ul>
        <sub-headline :headline="$t('diplomacy.modal.change.headline')" />
    </modal>
</template>

<style lang="scss" scoped>
p {
    margin: 0 0 8px 0;
}
li.allied {
    @include themed() {
        border-color: t("s-success");
    }
}
li.hostile {
    @include themed() {
        border-color: t("s-error");
    }
}
li.neutral {
    @include themed() {
        border-color: t("s-building");
    }
}
</style>
