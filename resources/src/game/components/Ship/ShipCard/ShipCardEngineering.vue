<script>
/******************************************************************************
 * Component: ShipCardEngineering
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipCardEngineering",
    props: {
        ftl: Boolean,
        colony: Boolean,
        acceleration: Number,
    },
    components: { Icon },
    setup() {
        const colonyRules = window.rules.modules.find(
            (m) => m.techType === "colony"
        );
        return { colonyRules };
    },
};
</script>

<template>
    <li
        v-if="ftl"
        :title="$t('fleets.ship.engineering.ftl.true')"
        :aria-label="$t('fleets.ship.engineering.ftl.true')"
    >
        <icon name="tech-ftl" />
        {{ $t("fleets.active.location.status.ftl.short") }}
    </li>
    <li
        v-if="!ftl"
        class="no"
        :title="$t('fleets.ship.engineering.ftl.false')"
        :aria-label="$t('fleets.ship.engineering.ftl.false')"
    >
        <icon name="tech-ftl" />
        {{ $t("common.boolean.no") }}
    </li>
    <li
        :title="
            $t('fleets.ship.engineering.acceleration', { num: acceleration })
        "
        :aria-label="
            $t('fleets.ship.engineering.acceleration', { num: acceleration })
        "
    >
        <icon name="tech-engine" />
        {{ acceleration }}g
    </li>
    <li
        v-if="colony"
        :title="
            $t('fleets.ship.engineering.colony', {
                pop: colonyRules.costs.population,
            })
        "
        :aria-label="
            $t('fleets.ship.engineering.colony', {
                pop: colonyRules.costs.population,
            })
        "
    >
        <icon name="population" />
        {{ colonyRules.costs.population }}
    </li>
</template>

<style lang="scss" scoped>
.no {
    @include themed() {
        border-color: t("s-error");
    }
}
</style>
