<script>
/******************************************************************************
 * Component: ShipCardDamage
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "ShipCardDamage",
    props: {
        laser: Number,
        missile: Number,
        railgun: Number,
        plasma: Number,
        hullType: String,
    },
    components: { Icon },
    setup(props) {
        const range = (area) =>
            window.rules.modules.find(
                (m) => m.hullType === props.hullType && m.techType === area
            ).range;
        return { range };
    },
};
</script>

<template>
    <li
        v-if="plasma"
        :title="
            $t('fleets.ship.dmg.plasma', {
                dmg: plasma,
                range: range('plasma'),
            })
        "
        :aria-label="
            $t('fleets.ship.dmg.plasma', {
                dmg: plasma,
                range: range('plasma'),
            })
        "
    >
        <icon name="tech-plasma" />
        {{ plasma }}@{{ range("plasma") }}
    </li>
    <li
        v-if="missile"
        :title="
            $t('fleets.ship.dmg.missile', {
                dmg: missile,
                range: range('missile'),
            })
        "
        :aria-label="
            $t('fleets.ship.dmg.missile', {
                dmg: missile,
                range: range('missile'),
            })
        "
    >
        <icon name="tech-missile" />
        {{ missile }}@{{ range("missile") }}
    </li>
    <li
        v-if="railgun"
        :title="
            $t('fleets.ship.dmg.railgun', {
                dmg: railgun,
                range: range('railgun'),
            })
        "
        :aria-label="
            $t('fleets.ship.dmg.railgun', {
                dmg: railgun,
                range: range('railgun'),
            })
        "
    >
        <icon name="tech-railgun" />
        {{ railgun }}@{{ range("railgun") }}
    </li>
    <li
        v-if="laser"
        :title="
            $t('fleets.ship.dmg.laser', {
                dmg: laser,
                range: range('laser'),
            })
        "
        :aria-label="
            $t('fleets.ship.dmg.laser', {
                dmg: laser,
                range: range('laser'),
            })
        "
    >
        <icon name="tech-laser" />
        {{ laser }}@{{ range("laser") }}
    </li>
</template>
