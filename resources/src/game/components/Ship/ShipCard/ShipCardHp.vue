<script>
/******************************************************************************
 * Component: ShipCardHp
 *****************************************************************************/
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import { formatNumber } from "@/game/helpers/format";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipCardHp",
    props: {
        shieldsCurrent: Number,
        shieldsMax: Number,
        armourCurrent: Number,
        armourMax: Number,
        structureCurrent: Number,
        structureMax: Number,
    },
    components: { Icon },
    setup(props) {
        const t = useI18n().t;
        const shieldsLabel = computed(() =>
            t("fleets.ship.hp.shields", {
                cur: props.shieldsCurrent,
                max: props.shieldsMax,
                pct: ((props.shieldsCurrent / props.shieldsMax) * 100).toFixed(
                    2
                ),
            })
        );
        const armourLabel = computed(() =>
            t("fleets.ship.hp.armour", {
                cur: props.armourCurrent,
                max: props.armourMax,
                pct: ((props.armourCurrent / props.armourMax) * 100).toFixed(2),
            })
        );
        const structureLabel = computed(() =>
            t("fleets.ship.hp.structure", {
                cur: props.structureCurrent,
                max: props.structureMax,
                pct: (
                    (props.structureCurrent / props.structureMax) *
                    100
                ).toFixed(2),
            })
        );
        return { formatNumber, shieldsLabel, armourLabel, structureLabel };
    },
};
</script>

<template>
    <li :title="shieldsLabel" :aria-label="shieldsLabel">
        <icon name="tech-shields" />{{ formatNumber(shieldsCurrent) }}
    </li>
    <li :title="armourLabel" :aria-label="armourLabel">
        <icon name="tech-armour" />{{ formatNumber(armourCurrent) }}
    </li>
    <li :title="structureLabel" :aria-label="structureLabel">
        <icon name="tech-structure" />{{ formatNumber(structureCurrent) }}
    </li>
</template>
