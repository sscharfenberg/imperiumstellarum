<script>
/******************************************************************************
 * Component: RadialHitpoints
 *****************************************************************************/
export default {
    name: "RadialHitpoints",
    props: {
        hpStructureCurrent: Number,
        hpStructureMax: Number,
        hpArmourCurrent: Number,
        hpArmourMax: Number,
        hpShieldsCurrent: Number,
        hpShieldsMax: Number,
    },
    setup() {
        return {};
    },
};
</script>

<template>
    <section class="ship">
        HP Structure: {{ hpStructureCurrent }} / {{ hpStructureMax }}<br />
        HP Armour: {{ hpArmourCurrent }} / {{ hpArmourMax }}<br />
        HP Shields: {{ hpShieldsCurrent }} / {{ hpShieldsMax }}
    </section>
</template>
