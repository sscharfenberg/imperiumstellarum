<script>
/******************************************************************************
 * PageComponent: EncountersListTurn
 *****************************************************************************/
import Icon from "Components/Icon/Icon";
export default {
    name: "EncountersListTurn",
    props: {
        number: Number,
    },
    components: { Icon },
};
</script>

<template>
    <span
        class="encounter-item turn"
        :title="$t('encounters.list.turn', { turn: number })"
        :aria-label="$t('encounters.list.turn', { turn: number })"
    >
        <icon name="turn" />
        {{ number }}
    </span>
</template>

<style lang="scss" scoped>
.turn {
    display: flex;
    align-items: center;

    .icon {
        margin-right: 4px;

        @include themed() {
            color: t("t-subdued");
        }

        @include respond-to("medium") {
            margin-right: 8px;
        }
    }
}
</style>
