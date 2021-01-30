<script>
/******************************************************************************
 * Component: ShipClassCard
 *****************************************************************************/
import { computed } from "vue";
import {
    calculateEffectiveHitpoints,
    calculateShipDamage,
    calculateAcceleration,
    calculateShipCosts,
} from "../useShipCalculations";
import { formatNumber } from "@/game/helpers/format";
import DamageType from "./DamageType";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipClassCard",
    props: {
        hullType: String,
        className: String,
        modules: Array,
        tls: Array,
        amount: Number,
    },
    components: { Icon, DamageType },
    setup(props) {
        const hp = computed(() =>
            calculateEffectiveHitpoints(
                props.hullType,
                props.modules,
                props.tls
            )
        );
        const dmg = computed(() =>
            calculateShipDamage(props.hullType, props.modules, props.tls)
        );
        const acceleration = computed(() =>
            calculateAcceleration(props.hullType, props.modules)
        );
        const ftl = computed(() => props.modules.includes("ftl"));
        const resourceCostsOne = computed(() =>
            calculateShipCosts(props.hullType, props.modules)
        );
        const sortedTls = computed(() => {
            const preferedSortOrder = Object.keys(window.rules.tech.areas);
            const tls = props.tls;
            return tls.sort((a, b) => {
                return (
                    preferedSortOrder.indexOf(a.type) -
                    preferedSortOrder.indexOf(b.type)
                );
            });
        });
        return {
            hp,
            dmg,
            acceleration,
            ftl,
            resourceCostsOne,
            sortedTls,
            formatNumber,
        };
    },
};
</script>

<template>
    <ul class="ship-class">
        <li class="label">{{ $t("shipyards.design.preview.type") }}</li>
        <li class="has-icon">
            <icon :name="`hull-${hullType}`" />
            {{ $t("shipyards.hulls." + hullType) }}
        </li>
        <li v-if="className" class="label">
            {{ $t("shipyards.design.preview.name") }}
        </li>
        <li v-if="className">{{ className }}</li>
        <li class="section">{{ $t("shipyards.ship.defense") }}</li>
        <li class="label">{{ $t("shipyards.ship.structure") }}</li>
        <li class="has-icon">
            <icon name="tech-structure" />
            {{ formatNumber(hp.structure) }}
        </li>
        <li class="label">{{ $t("shipyards.ship.armour") }}</li>
        <li class="has-icon">
            <icon name="tech-armour" />
            {{ formatNumber(hp.armour) }}
        </li>
        <li class="label">{{ $t("shipyards.ship.shields") }}</li>
        <li class="has-icon">
            <icon name="tech-shields" />
            {{ formatNumber(hp.shields) }}
        </li>
        <li class="section" v-if="dmg.length">
            {{ $t("shipyards.ship.offense") }}
        </li>
        <damage-type
            v-for="(d, index) in dmg"
            :key="d.type"
            :type="d.type"
            :dmg="d.dmg"
            :range="d.range"
            :use-divider="index + 1 < dmg.length"
        />
        <li class="section">
            {{ $t("shipyards.ship.engineering") }}
        </li>
        <li class="label">
            {{ $t("shipyards.design.preview.engineering.jumpdrive") }}
        </li>
        <li v-if="modules.includes('ftl')" class="has-icon">
            <icon name="tech-ftl" />
            {{ $t("common.boolean.yes") }}
        </li>
        <li v-if="!modules.includes('ftl')" class="has-icon warning">
            {{ $t("common.boolean.no") }}
        </li>
        <li class="label">
            {{ $t("shipyards.design.preview.engineering.acceleration") }}
        </li>
        <li class="has-icon">
            <icon name="tech-engine" />
            {{ acceleration }}g
        </li>
        <li v-if="resourceCostsOne.population" class="label">
            {{ $t("research.tl.colony") }}
        </li>
        <li v-if="resourceCostsOne.population" class="has-icon">
            <icon name="population" />
            {{ $t("common.boolean.yes") }}
        </li>
        <li class="section">
            {{ $t("shipyards.design.preview.techLevels") }}
        </li>
        <li class="tls">
            <span class="tl" v-for="tl in sortedTls" :key="tl.type">
                <icon :name="`tech-${tl.type}`" />
                {{ tl.level }}
            </span>
        </li>
        <li class="section">
            {{ $t("shipyards.design.preview.costs") }}
        </li>
        <li class="resources">
            <div
                v-for="[type, value] of Object.entries(resourceCostsOne)"
                :key="type"
            >
                {{ formatNumber(value) }}
                <icon v-if="type !== 'population'" :name="`res-${type}`" />
                <icon v-if="type === 'population'" name="population" />
            </div>
        </li>
        <li v-if="amount" class="section">
            {{ $t("shipyards.design.preview.costsAll", { num: amount }) }}
        </li>
        <li v-if="amount" class="resources">
            <div
                v-for="[type, value] of Object.entries(resourceCostsOne)"
                :key="type"
            >
                {{ formatNumber(value * amount) }}
                <icon v-if="type !== 'population'" :name="`res-${type}`" />
                <icon v-if="type === 'population'" name="population" />
            </div>
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.ship-class {
    display: grid;
    grid-template-columns: 3fr 4fr;

    padding: 0;
    margin: 0;
    grid-gap: 4px;

    list-style: none;

    @include respond-to("medium") {
        grid-gap: 8px;
    }

    > li {
        display: flex;
        align-items: center;
        flex-wrap: wrap;

        padding: 4px;
        border: 1px solid transparent;

        font-size: 14px;

        @include respond-to("medium") {
            padding: 8px;

            font-size: 16px;
        }

        @include themed() {
            background-color: t("g-bunker");
            border-color: t("g-deep");
        }

        &.has-icon {
            display: flex;
            align-items: center;

            .icon {
                margin-right: 4px;

                @include respond-to("medium") {
                    margin-right: 8px;
                }
            }
        }

        &.section {
            grid-column-start: span 2;

            border: 0;
            clip-path: polygon(
                0 0,
                calc(100% - 10px) 0,
                100% 10px,
                100% 100%,
                10px 100%,
                0 calc(100% - 10px)
            );

            @include themed() {
                background-color: t("g-deep");
            }
        }

        &.divider {
            grid-column-start: span 2;

            height: 2px;
            padding: 0;
            border: 0;

            @include themed() {
                background: linear-gradient(
                    to right,
                    t("g-deep"),
                    t("g-raven")
                );
            }
        }

        &.warning {
            @include themed() {
                border-color: t("s-warning");
            }
        }

        &.label {
            overflow: hidden;

            white-space: nowrap;
            text-overflow: ellipsis;
        }

        &.resources {
            display: flex;
            align-items: center;
            grid-column-start: span 2;

            //padding: 0;

            > div {
                display: flex;
                align-items: center;
                justify-content: center;

                margin-right: 8px;

                &:last-child {
                    margin-right: auto;
                }

                &:first-child {
                    margin-left: auto;
                }

                .icon {
                    margin-left: 4px;
                }
            }
        }

        &.tls {
            display: flex;

            grid-column-start: span 2;

            .tl {
                margin-right: 8px;

                &:last-child {
                    margin-right: auto;
                }

                &:first-child {
                    margin-left: auto;
                }

                .icon {
                    margin-right: 4px;
                }
            }
        }
    }
}
</style>
