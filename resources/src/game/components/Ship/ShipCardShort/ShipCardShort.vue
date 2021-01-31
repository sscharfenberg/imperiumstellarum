<script>
/******************************************************************************
 * PageComponent: ShipCardShort
 *****************************************************************************/
import { ref } from "vue";
import GameButton from "Components/Button/GameButton";
import Icon from "Components/Icon/Icon";
import ShipCardHpRadial from "../ShipCard/ShipCardHpRadial";
import ShipCardRenameModal from "../ShipCard/ShipCardRenameModal";
export default {
    name: "ShipCardShort",
    components: { Icon, GameButton, ShipCardHpRadial, ShipCardRenameModal },
    props: {
        shipId: String,
        name: String,
        hullType: String,
        shieldsCurrent: Number,
        shieldsMax: Number,
        armourCurrent: Number,
        armourMax: Number,
        structureCurrent: Number,
        structureMax: Number,
    },
    setup() {
        const showModal = ref(false);
        return { showModal };
    },
};
</script>

<template>
    <div class="ship">
        <ship-card-hp-radial
            class="ship__radial"
            :pct-shields="(shieldsCurrent / shieldsMax) * 100"
            :pct-armour="(armourCurrent / armourMax) * 100"
            :pct-structure="(structureCurrent / structureMax) * 100"
        />
        <icon :name="`hull-${hullType}`" />
        <span class="ship__name">{{ name }}</span>
        <game-button icon-name="edit" :size="0" @click="showModal = true" />
        <ship-card-rename-modal
            v-if="showModal"
            :ship-id="shipId"
            @close="showModal = false"
        />
    </div>
</template>

<style lang="scss" scoped>
.ship {
    display: flex;
    align-items: center;

    padding: 4px;
    border: 2px solid transparent;

    @include themed() {
        background-color: t("g-bunker");
        border-color: t("g-ebony");
    }

    @include respond-to("medium") {
        padding: 4px 8px;
    }

    .icon {
        margin-right: 8px;
        flex: 0 0 24px;
    }

    &__name {
        overflow: hidden;

        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &__radial {
        width: 26px;
        height: 26px;
        margin-right: 8px;

        flex: 0 0 26px;
    }

    .btn {
        margin-left: auto;
    }
}
</style>
