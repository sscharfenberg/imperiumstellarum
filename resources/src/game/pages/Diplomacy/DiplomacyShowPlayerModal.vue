<script>
/******************************************************************************
 * PageComponent: DiplomacyShowPlayerModal
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import GameButton from "Components/Button/GameButton";
import Modal from "Components/Modal/Modal";
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
    components: { Modal, SubHeadline, GameButton },
    emits: ["close"],
    setup(props, { emit }) {
        const store = useStore();
        const empireTicker = computed(() => store.state.empireTicker);
        const setTo = ref(undefined);
        const requesting = store.state.diplomacy.requesting;
        const rules = window.rules.diplomacy;
        const relationChangePending = computed(() =>
            store.getters["diplomacy/relationChangeByRecipientId"](
                props.playerId
            )
        );
        const onSubmit = () => {
            console.log("submit", setTo.value, props.playerId);
            store.dispatch("diplomacy/CHANGE_RELATION", {
                recipient: props.playerId,
                set: setTo.value,
            });
            emit("close");
        };
        return {
            empireTicker,
            setTo,
            rules,
            requesting,
            onSubmit,
            relationChangePending,
        };
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
        <ul class="stats">
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

            <li class="text-left">
                [{{ empireTicker }}] > [{{ playerTicker }}]
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
                [{{ playerTicker }}] > [{{ empireTicker }}]
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
        </ul>
        <sub-headline
            v-if="!relationChangePending"
            :headline="$t('diplomacy.modal.change.headline')"
        />
        <div v-if="!relationChangePending" class="set-relation">
            <game-button
                :disabled="relationSet === 0 || relationSet === 2"
                :text-string="'0 ' + $t('diplomacy.status.0')"
                :primary="setTo === 0"
                @click="setTo = 0"
            />
            <game-button
                :disabled="relationSet === 1 || relationSet === 9"
                :text-string="'1 ' + $t('diplomacy.status.1')"
                :primary="setTo === 1"
                @click="setTo = 1"
            />
            <game-button
                :disabled="relationSet === 2 || relationSet === 0"
                :text-string="'2 ' + $t('diplomacy.status.2')"
                :primary="setTo === 2"
                @click="setTo = 2"
            />
        </div>
        <div v-if="!relationChangePending">
            {{
                $t("diplomacy.modal.change.explanation", {
                    num: rules.turnsUntilEffective,
                })
            }}
        </div>
        <div class="relation__pending" v-if="relationChangePending">
            {{
                $t("diplomacy.modal.changePending", {
                    ticker: playerTicker,
                    turns: relationChangePending.untilDone,
                    status: relationChangePending.set,
                })
            }}
        </div>
        <template v-slot:actions v-if="!relationChangePending">
            <game-button
                :text-string="$t('diplomacy.modal.change.submit')"
                icon-name="save"
                :disabled="setTo === undefined"
                :loading="requesting"
                @click="onSubmit"
            />
        </template>
    </modal>
</template>

<style lang="scss" scoped>
p {
    margin: 0 0 8px 0;
}
.allied {
    @include themed() {
        border-color: t("s-success");
    }
}
.hostile {
    @include themed() {
        border-color: t("s-error");
    }
}
.neutral {
    @include themed() {
        border-color: t("s-building");
    }
}

.set-relation {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;

    margin: 0 0 8px 0;
    grid-gap: 2px;

    @include respond-to("medium") {
        margin: 0 0 16px 0;
        grid-gap: 8px;
    }
}
</style>
