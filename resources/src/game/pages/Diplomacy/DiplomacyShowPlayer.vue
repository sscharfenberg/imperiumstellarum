<script>
/******************************************************************************
 * PageComponent: DiplomacyShowPlayer
 *****************************************************************************/
import { computed, ref } from "vue";
import { useStore } from "vuex";
import DiplomacyShowPlayerModal from "./DiplomacyShowPlayerModal";
export default {
    name: "DiplomacyShowPlayer",
    props: {
        player: {
            type: Object,
            required: true,
        },
    },
    components: { DiplomacyShowPlayerModal },
    setup(props) {
        const store = useStore();
        const showModal = ref(false);
        const empireTicker = computed(() => store.state.empireTicker);
        const relationChangePending = computed(() => {
            const relationChangePending = store.state.diplomacy.relationChanges.find(
                (r) => r.playerId === props.player.id
            );
            if (relationChangePending) {
                return relationChangePending.untilDone;
            } else {
                return 0;
            }
        });
        return { showModal, relationChangePending, empireTicker };
    },
};
</script>

<template>
    <button
        class="player"
        @click="showModal = true"
        :title="$t('diplomacy.list.buttonLabel', { ticker: player.ticker })"
        :aria-label="
            $t('diplomacy.list.buttonLabel', { ticker: player.ticker })
        "
    >
        <span
            class="player__ticker"
            :style="{ '--playerColour': '#' + player.colour }"
            >[{{ player.ticker }}]</span
        >
        <span class="player__name">{{ player.name }}</span>
        <ul class="player__relations">
            <li
                class="player__relation"
                :class="{
                    allied: player.relationEffective === 2,
                    neutral: player.relationEffective === 1,
                    hostile: player.relationEffective === 0,
                }"
            >
                {{ $t("diplomacy.list.relation.effective") }}
                <div>{{ player.relationEffective }}</div>
            </li>

            <li
                class="player__relation"
                :class="{
                    allied: player.relationSet === 2,
                    neutral: player.relationSet === 1,
                    hostile: player.relationSet === 0,
                    unset: player.relationSet === 9,
                }"
            >
                [{{ empireTicker }}] > [{{ player.ticker }}]
                <div v-if="!relationChangePending">
                    {{ player.relationSet !== 9 ? player.relationSet : "-" }}
                </div>
                <div v-if="relationChangePending" class="change-pending">
                    <span
                        v-for="n in relationChangePending"
                        role="presentation"
                        aria-hidden="true"
                        :key="n"
                        >•</span
                    >
                </div>
            </li>

            <li
                class="player__relation"
                :class="{
                    allied: player.relationRecipientSet === 2,
                    neutral: player.relationRecipientSet === 1,
                    hostile: player.relationRecipientSet === 0,
                    unset: player.relationRecipientSet === 9,
                }"
            >
                [{{ player.ticker }}] > [{{ empireTicker }}]
                <div>
                    {{
                        player.relationRecipientSet !== 9
                            ? player.relationRecipientSet
                            : "-"
                    }}
                </div>
            </li>
        </ul>
    </button>
    <diplomacy-show-player-modal
        v-if="showModal"
        :player-id="player.id"
        :player-name="player.name"
        :player-ticker="player.ticker"
        :relation-set="player.relationSet"
        :relation-recipient-set="player.relationRecipientSet"
        :relation-effective="player.relationEffective"
        @close="showModal = false"
    />
</template>

<style lang="scss" scoped>
.player {
    display: flex;
    flex-direction: column;

    padding: 0;
    border: 2px solid transparent;

    outline: 0;
    cursor: pointer;

    transition: background-color map-get($animation-speeds, "fast") linear;

    @include themed() {
        background-color: t("g-bunker");
        color: t("t-light");
        border-color: t("g-deep");
    }

    &:hover {
        @include themed() {
            background-color: t("g-ebony");
        }
    }

    &__ticker {
        display: block;

        width: 100%;
        padding: 8px;

        background-color: var(--playerColour);

        font-weight: 600;
        text-align: center;

        @include themed() {
            color: t("g-white");

            text-shadow: 1px 1px t("g-black"), -1px 1px t("g-black"),
                1px -1px t("g-black"), -1px -1px t("g-black");
            letter-spacing: 2px;
        }

        @include respond-to("medium") {
            padding: 16px;
        }
    }

    &__name {
        display: flex;
        align-items: center;
        justify-content: center;

        width: 100%;
        height: 45px;
        padding: 4px;

        @include respond-to("medium") {
            height: 55px;
            padding: 8px;
        }
    }

    &__relations {
        display: grid;
        grid-template-columns: 1fr;

        width: calc(100% - 16px);
        padding: 0;
        margin: 0 8px 8px 8px;
        grid-gap: 2px;

        list-style: none;

        @include respond-to("medium") {
            grid-gap: 4px;
        }
    }

    &__relation {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 2px;
        border: 2px solid transparent;

        @include respond-to("medium") {
            padding: 4px;
        }

        &.allied {
            @include themed() {
                border-color: t("s-success");
            }
        }
        &.hostile {
            @include themed() {
                border-color: t("s-error");
            }
        }
        &.neutral {
            @include themed() {
                border-color: t("s-building");
            }
        }
        &.unset {
            @include themed() {
                border-color: t("g-deep");
            }
        }

        > div {
            font-size: 20px;
            font-weight: 600;
        }

        .change-pending {
            display: flex;
            align-items: center;
            justify-content: center;

            margin-top: 16px;

            > span {
                width: 4px;
                height: 4px;
                margin: 0 4px 0 0;

                border-radius: 50%;

                text-indent: -1000em;

                @include themed() {
                    background: linear-gradient(
                        to bottom,
                        t("s-warning") 0%,
                        t("s-error") 100%
                    );
                }

                &:last-child {
                    margin-right: 0;
                }
            }
        }
    }
}
</style>
