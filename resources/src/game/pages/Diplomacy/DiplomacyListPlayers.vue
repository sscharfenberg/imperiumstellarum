<script>
/******************************************************************************
 * PageComponent: DiplomacyListPlayers
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import DiplomacyShowPlayer from "Pages/Diplomacy/DiplomacyShowPlayer";
import SubHeadline from "Components/SubHeadline/SubHeadline";
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
                        player.relationRecipientSet = relation.recipientSet;
                    } else {
                        player.relationEffective = 1;
                        player.relationSet = 9;
                        player.relationRecipientSet = 9;
                    }
                    return player;
                });
        });

        /**
         * @function filter and sort mapped players
         * @param rel
         * @returns {Array}
         */
        const filteredPlayers = (rel) => {
            const locale = document.documentElement.lang;
            const collator = new Intl.Collator(locale, {
                numeric: true,
                sensitivity: "base",
            });
            const tickerFilter = store.state.diplomacy.filterByTicker;
            const sortDirection = store.state.diplomacy.sort;
            const mapped = mappedPlayers.value.filter((p) => {
                return (
                    p.relationEffective === rel &&
                    p.ticker.includes(tickerFilter)
                );
            });
            return sortDirection === "desc"
                ? mapped.sort((a, b) => collator.compare(a.ticker, b.ticker))
                : mapped.sort((a, b) => collator.compare(b.ticker, a.ticker));
        };

        return {
            allPlayers,
            showAllies,
            showNeutrals,
            showHostiles,
            filteredPlayers,
        };
    },
};
</script>

<template>
    <div class="list-players">
        <div class="list-players__category">
            <sub-headline
                :headline="
                    $tc(
                        'diplomacy.list.categories.2',
                        filteredPlayers(2).length
                    )
                "
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
                :headline="
                    $tc(
                        'diplomacy.list.categories.0',
                        filteredPlayers(0).length
                    )
                "
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
                :headline="
                    $tc(
                        'diplomacy.list.categories.1',
                        filteredPlayers(1).length
                    )
                "
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

        &:last-of-type {
            margin-bottom: 0;
        }
    }

    &__list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));

        grid-gap: 8px;

        @include respond-to("medium") {
            grid-gap: 16px;
        }
    }
}
</style>
