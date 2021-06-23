<script>
/******************************************************************************
 * Page: RaidsAsRaiderRenderSingle.vue
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import CollapsibleItem from "Components/Collapsible/CollapsibleItem";
import Icon from "Components/Icon/Icon";
import RaidDetails from "../RaidDetails";
export default {
    name: "RaidsAsRaiderRenderSingle",
    props: {
        raid: {
            type: Object,
            required: true,
        },
    },
    components: { CollapsibleItem, Icon, RaidDetails },
    setup(props) {
        const store = useStore();
        const star = computed(() =>
            store.getters["encounters/starById"](props.raid.starId)
        );
        const raidedPlayer = computed(() => {
            const raidedPlayerId = props.raid.players.find(
                (raidPlayer) => !raidPlayer.raider
            ).playerId;
            return store.getters["encounters/playerById"](raidedPlayerId);
        });
        const ourRaidPlayer = computed(() => {
            const ourId = store.state.empireId;
            return props.raid.players.find(
                (raidedPlayer) => raidedPlayer.playerId === ourId
            );
        });
        return { ourRaidPlayer, raidedPlayer, star };
    },
};
</script>

<template>
    <collapsible-item :key="raid.id" :collapsible-id="raid.id">
        <template v-slot:topic>
            <div
                class="topic-item"
                :title="
                    $t('encounters.raids.turns', {
                        start: raid.startTurn,
                        end: raid.endTurn,
                    })
                "
                :aria-label="
                    $t('encounters.raids.turns', {
                        start: raid.startTurn,
                        end: raid.endTurn,
                    })
                "
            >
                <icon name="res-turns" /> {{ raid.startTurn }} -
                {{ raid.endTurn }}
            </div>
            <div
                class="topic-item hide-for-mobile"
                :title="$t('encounters.raids.starName', { name: star.name })"
                :aria-label="
                    $t('encounters.raids.starName', { name: star.name })
                "
            >
                {{ star.name }}
            </div>
            <div
                class="topic-item"
                :title="
                    $t('encounters.raids.starLocation', {
                        x: star.x,
                        y: star.y,
                    })
                "
                :aria-label="
                    $t('encounters.raids.starLocation', {
                        x: star.x,
                        y: star.y,
                    })
                "
            >
                <icon name="location" /> {{ star.x }}/{{ star.y }}
            </div>
            <div
                class="topic-item"
                :title="
                    $t('encounters.raids.owner', {
                        ticker: raidedPlayer.ticker,
                        name: raidedPlayer.name,
                    })
                "
                :aria-label="
                    $t('encounters.raids.owner', {
                        ticker: raidedPlayer.ticker,
                        name: raidedPlayer.name,
                    })
                "
            >
                [{{ raidedPlayer.ticker }}]
            </div>
            <div
                class="topic-item topic-item--resources hide-for-mobile"
                :title="$t('encounters.raidsAsRaider.resources')"
                :aria-label="$t('encounters.raidsAsRaider.resources')"
            >
                <span v-if="ourRaidPlayer.energy > 0">
                    <icon name="res-energy" /> {{ ourRaidPlayer.energy }}
                </span>
                <span v-if="ourRaidPlayer.minerals > 0">
                    <icon name="res-minerals" /> {{ ourRaidPlayer.minerals }}
                </span>
                <span v-if="ourRaidPlayer.food > 0">
                    <icon name="res-food" /> {{ ourRaidPlayer.food }}
                </span>
                <span v-if="ourRaidPlayer.research > 0">
                    <icon name="res-research" /> {{ ourRaidPlayer.research }}
                </span>
            </div>
        </template>
        <raid-details :raid-players="raid.players" />
    </collapsible-item>
</template>

<style lang="scss" scoped>
.topic-item {
    display: flex;
    align-items: center;

    height: 40px;
    padding: 4px;
    border: 1px solid;

    @include respond-to("medium") {
        padding: 8px;
    }

    @include themed() {
        background-color: t("g-bunker");
        border-color: t("g-deep");
    }

    &:not(:last-of-type) {
        margin-right: 4px;
    }

    &.hide-for-mobile {
        display: none;

        @include respond-to("medium") {
            display: flex;
        }
    }

    .icon {
        margin-right: 4px;

        @include respond-to("medium") {
            margin-right: 8px;
        }

        @include themed() {
            color: t("t-subdued");
        }
    }

    &--resources {
        .icon {
            margin-right: 2px;

            @include respond-to("medium") {
                margin-right: 4px;
            }
        }

        > span {
            display: flex;
            align-items: center;

            &:not(:last-of-type) {
                margin-right: 4px;

                @include respond-to("medium") {
                    margin-right: 8px;
                }
            }
        }
    }
}
</style>
