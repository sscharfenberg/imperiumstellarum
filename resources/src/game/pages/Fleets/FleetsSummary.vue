<script>
/******************************************************************************
 * Page: FleetsSummary
 *****************************************************************************/
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "FleetsSummary",
    components: { Icon },
    props: {
        numFleets: Number,
        maxFleets: Number,
        numShipyards: Number,
        ships: Array,
    },
    setup(props) {
        const numShips = computed(() => props.ships.length);
        const numArks = computed(
            () => props.ships.filter((s) => s.hullType === "ark").length
        );
        const numSmall = computed(
            () => props.ships.filter((s) => s.hullType === "small").length
        );
        const numMedium = computed(
            () => props.ships.filter((s) => s.hullType === "medium").length
        );
        const numLarge = computed(
            () => props.ships.filter((s) => s.hullType === "large").length
        );
        const numXLarge = computed(
            () => props.ships.filter((s) => s.hullType === "xlarge").length
        );
        return { numShips, numArks, numSmall, numMedium, numLarge, numXLarge };
    },
};
</script>

<template>
    <ul class="summary">
        <li
            class="summary__item"
            :aria-label="$tc('fleets.summary.fleetsLabel', numFleets)"
        >
            <h3>
                <icon name="fleets" :size="3" />
                {{ $tc("fleets.summary.fleets", numFleets) }}
            </h3>
            <aside class="summary__item-number">
                {{ numFleets }} / {{ maxFleets }}
            </aside>
        </li>
        <li
            class="summary__item"
            :aria-label="$tc('fleets.summary.shipyardsLabel', numShipyards)"
        >
            <h3>
                <icon name="shipyards" :size="3" />
                {{ $tc("fleets.summary.shipyards", numShipyards) }}
            </h3>
            <aside class="summary__item-number">{{ numShipyards }}</aside>
        </li>
        <li class="summary__item">
            <h3>{{ $tc("fleets.summary.ships", numShips) }}</h3>
            <aside class="summary__item-number">{{ numShips }}</aside>
            <ul class="summary__ships">
                <li
                    v-if="numArks"
                    class="summary__ship"
                    :aria-label="
                        $tc('fleets.summary.hulls.ark', numArks, {
                            num: numArks,
                        })
                    "
                >
                    {{ numArks }} <icon name="hull-ark" />
                </li>
                <li
                    v-if="numSmall"
                    class="summary__ship"
                    :aria-label="
                        $tc('fleets.summary.hulls.small', numSmall, {
                            num: numSmall,
                        })
                    "
                >
                    {{ numSmall }} <icon name="hull-small" />
                </li>
                <li
                    v-if="numMedium"
                    class="summary__ship"
                    :aria-label="
                        $tc('fleets.summary.hulls.medium', numMedium, {
                            num: numMedium,
                        })
                    "
                >
                    {{ numMedium }} <icon name="hull-medium" />
                </li>
                <li
                    v-if="numLarge"
                    class="summary__ship"
                    :aria-label="
                        $tc('fleets.summary.hulls.large', numLarge, {
                            num: numLarge,
                        })
                    "
                >
                    {{ numLarge }} <icon name="hull-large" />
                </li>
                <li
                    v-if="numXLarge"
                    class="summary__ship"
                    :aria-label="
                        $tc('fleets.summary.hulls.medium', numXLarge, {
                            num: numXLarge,
                        })
                    "
                >
                    {{ numXLarge }} <icon name="hull-xlarge" />
                </li>
            </ul>
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.summary {
    display: grid;

    padding: 0;
    margin: 0 0 16px 0;
    grid-gap: 4px;

    list-style: none;

    @include respond-to("medium") {
        grid-template-columns: 1fr 1fr 1fr;

        grid-gap: 16px;
    }

    &__item {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;

        padding: 4px;
        border: 2px solid transparent;

        text-align: center;

        @include respond-to("medium") {
            padding: 16px;
        }

        @include themed() {
            background-color: t("g-sunken");
            border-color: t("g-deep");
        }

        > h3 {
            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 0 8px 0;

            font-size: 20px;
            font-weight: 300;

            @include themed() {
                color: t("t-bright");
            }

            > .icon {
                margin-right: 8px;

                @include respond-to("medium") {
                    margin-right: 16px;
                }

                @include themed() {
                    color: t("b-christine");
                }
            }
        }
    }

    &__item-number {
        display: inline-block;

        padding: 4px 8px;
        border: 2px solid transparent;

        @include themed() {
            background-color: t("g-bunker");
            color: t("b-viking");
            border-color: t("g-deep");
        }
    }

    &__ships {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;

        padding: 0;
        margin: 8px 0 0 0;

        list-style: none;
    }

    &__ship {
        display: flex;
        align-items: center;

        margin-right: 8px;

        &:last-child {
            margin-right: 0;
        }

        @include respond-to("medium") {
            margin-right: 16px;
        }

        .icon {
            margin-left: 4px;

            @include respond-to("medium") {
                margin-left: 8px;
            }
        }
    }
}
</style>
