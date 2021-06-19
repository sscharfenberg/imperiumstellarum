<script>
/******************************************************************************
 * Page: RaidsDetails.vue
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import RaidDetailsRaiderShips from "./RaidDetailsRaiderShips";
import RaidDetailsResources from "./RaidDetailsResources";
import SubHeadline from "Components/SubHeadline/SubHeadline";
export default {
    name: "RaidsDetails",
    props: {
        raidPlayers: {
            type: Object,
            required: true,
        },
    },
    components: { RaidDetailsRaiderShips, RaidDetailsResources, SubHeadline },
    setup(props) {
        const store = useStore();
        const playerData = (id) => store.getters["encounters/playerById"](id);
        const playerRelation = (id) =>
            store.getters["encounters/playerRelationByPlayerId"](id);
        const raided = computed(
            () => props.raidPlayers.filter((player) => !player.raider)[0]
        );
        const raiders = computed(() =>
            props.raidPlayers.filter((player) => player.raider)
        );
        return { playerData, playerRelation, raided, raiders };
    },
};
</script>

<template>
    <div class="raid">
        <sub-headline headline="Owner of raided star" />
        <header class="raid__raided">
            <span
                class="ticker"
                :class="{
                    hostile: playerRelation(raided.playerId) === 0,
                    allied: playerRelation(raided.playerId) === 2,
                }"
                >[{{ playerData(raided.playerId).ticker }}]</span
            >
            <raid-details-resources
                :energy="raided.energy"
                :minerals="raided.minerals"
                :food="raided.food"
                :research="raided.research"
            />
        </header>
        <sub-headline headline="Raiders" />
        <ul class="raid__raiders">
            <li
                class="raid__raider"
                v-for="raider in raiders"
                :key="raider.playerId"
            >
                <span
                    class="ticker"
                    :class="{
                        hostile: playerRelation(raider.playerId) === 0,
                        allied: playerRelation(raider.playerId) === 2,
                    }"
                    >[{{ playerData(raider.playerId).ticker }}]</span
                >
                <raid-details-raider-ships
                    :ark="raider.ark"
                    :small="raider.small"
                    :medium="raider.medium"
                    :large="raider.large"
                    :xlarge="raider.xlarge"
                />
                <raid-details-resources
                    :energy="raider.energy"
                    :minerals="raider.minerals"
                    :food="raider.food"
                    :research="raider.research"
                />
            </li>
        </ul>
    </div>
</template>

<style lang="scss" scoped>
.raid {
    &__raided,
    &__raider {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;

        padding: 4px;
        margin: 0 0 8px 0;
        gap: 4px;

        @include themed() {
            background-color: t("g-bunker");
        }

        @include respond-to("medium") {
            padding: 8px;
            margin-bottom: 16px;
        }
    }

    &__raiders {
        padding: 0;
        margin: 0;
        gap: 4px;

        list-style: none;

        @include respond-to("medium") {
            gap: 8px;
        }
    }

    .ticker {
        display: flex;
        align-items: center;

        padding: 2px;
        border: 2px solid transparent;

        @include themed() {
            background-color: t("g-ebony");
            border-color: t("g-deep");
        }

        @include respond-to("medium") {
            padding: 4px 8px;
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
}
</style>
