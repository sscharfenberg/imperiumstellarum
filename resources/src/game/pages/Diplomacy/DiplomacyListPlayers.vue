<script>
/******************************************************************************
 * PageComponent: DiplomacyListPlayers
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import SubHeadline from "Components/SubHeadline/SubHeadline";
import DiplomacyShowPlayer from "Pages/Diplomacy/DiplomacyShowPlayer";
export default {
    name: "PageDiplomacy",
    components: { SubHeadline, DiplomacyShowPlayer },
    setup() {
        const store = useStore();
        const allPlayers = computed(() => store.state.diplomacy.players);
        const showAllies = computed(() => store.state.diplomacy.showAllies);
        const showNeutrals = computed(() => store.state.diplomacy.showNeutrals);
        const showHostiles = computed(() => store.state.diplomacy.showHostiles);

        /**
         * @function add relations to state players
         * @type {ComputedRef<T[]>}
         * @returns {Array}
         */
        const mappedPlayers = computed(() => {
            const relations = store.state.diplomacy.relations;
            return store.state.diplomacy.players
                .filter((p) => p.id !== store.state.empireId)
                .map((player) => {
                    const relation = relations.find(
                        (rel) => rel.playerId === player.id
                    );
                    if (relation) {
                        player.relationEffective = relation.effective;
                        player.relationSet = relation.set;
                    } else {
                        player.relationEffective = 1;
                        player.relationSet = 9;
                    }
                    return player;
                });
        });

        const filteredPlayers = (rel) => {
            const tickerFilter = store.state.diplomacy.filterByTicker;
            return mappedPlayers.value.filter((p) => {
                return (
                    p.relationEffective === rel &&
                    p.ticker.includes(tickerFilter)
                );
            });
        };

        return {
            allPlayers,
            showAllies,
            showNeutrals,
            showHostiles,
            filteredPlayers,
            mappedPlayers,
        };
    },
};
</script>

<template>
    <div class="list-players">
        <div class="list-players__category">
            <sub-headline
                :headline="$t('diplomacy.list.categories.2')"
                :centered="true"
            />
            <div class="list-players__list" v-if="showAllies">
                <diplomacy-show-player
                    v-for="player in filteredPlayers(2)"
                    :key="player.id"
                    :player="player"
                />
            </div>
        </div>
        <div class="list-players__category">
            <sub-headline
                :headline="$t('diplomacy.list.categories.0')"
                :centered="true"
            />
            <div class="list-players__list" v-if="showHostiles">
                <diplomacy-show-player
                    v-for="player in filteredPlayers(0)"
                    :key="player.id"
                    :player="player"
                />
            </div>
        </div>
        <div class="list-players__category">
            <sub-headline
                :headline="$t('diplomacy.list.categories.1')"
                :centered="true"
            />
            <div class="list-players__list" v-if="showNeutrals">
                <diplomacy-show-player
                    v-for="player in filteredPlayers(1)"
                    :key="player.id"
                    :player="player"
                />
            </div>
        </div>
    </div>
    {{ mappedPlayers }}
</template>

<style lang="scss" scoped>
.list-players {
    padding: 8px;

    @include respond-to("medium") {
        padding: 16px;
    }

    &__category {
        margin-bottom: 8px;

        @include respond-to("medium") {
            margin-bottom: 16px;
        }
    }

    &__list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));

        grid-gap: 8px;

        @include respond-to("medium") {
            grid-gap: 16px;
        }
    }
}
</style>
