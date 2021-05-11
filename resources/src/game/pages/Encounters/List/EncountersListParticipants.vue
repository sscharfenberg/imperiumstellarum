<script>
/******************************************************************************
 * PageComponent: EncountersListParticipants
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
export default {
    name: "EncountersListParticipants",
    props: {
        participants: Array,
    },
    setup(props) {
        const store = useStore();
        const players = computed(() =>
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
        return { players };
    },
};
</script>

<template>
    <span
        v-for="player in players"
        :key="player.id"
        class="participant"
        :class="{
            allied: player.relation === 2,
            hostile: player.relation === 0,
        }"
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
