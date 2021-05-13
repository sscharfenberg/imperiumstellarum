<script>
/******************************************************************************
 * PageComponent: EncountersListParticipants
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "EncountersListParticipants",
    props: {
        participants: Array,
    },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();
        const participants = computed(() =>
            props.participants.map(function (player) {
                const data = store.getters["encounters/playerById"](player);
                if (store.state.empireId !== data.id) {
                    data.relation = store.getters[
                        "encounters/playerRelationByPlayerId"
                    ](player);
                }
                return data;
            })
        );
        const sortedPlayers = computed(() => {
            const data = participants.value;
            return data.sort((a, b) => a.ticker.localeCompare(b.ticker));
        });
        const getRelationLabel = (rel) => {
            if (rel || rel === 0) {
                return ` - ${i18n.t("diplomacy.empireStatus." + rel)}`;
            } else if (!rel) return ` (${i18n.t("diplomacy.you")})`;
        };
        return { sortedPlayers, getRelationLabel };
    },
};
</script>

<template>
    <span
        v-for="player in sortedPlayers"
        :key="player.id"
        class="participant"
        :class="{
            allied: player.relation === 2,
            hostile: player.relation === 0,
        }"
        :title="player.name + getRelationLabel(player.relation)"
        :aria-label="player.name + getRelationLabel(player.relation)"
    >
        {{ player.ticker }}
    </span>
</template>

<style lang="scss" scoped>
.participant {
    display: flex;
    align-items: center;

    height: 40px;
    padding: 4px;
    border: 2px solid;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        background-color: t("g-bunker");
        border-color: t("g-deep");
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
}
</style>
