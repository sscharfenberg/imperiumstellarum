<script>
/******************************************************************************
 * App drawer game data
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { format, addSeconds } from "date-fns";
import Icon from "Components/Icon/Icon";
export default {
    name: "AppDrawerGameData",
    components: { Icon },
    setup() {
        const store = useStore();
        const gameTurn = computed(() => store.state.gameTurn);
        const dueDate = computed(() =>
            addSeconds(new Date(), store.state.turnDue)
        );
        const dueShort = computed(() => format(dueDate.value, "HH:mm"));
        const dueLong = computed(() =>
            format(dueDate.value, "dd.MM.uuuu HH:mm:ss")
        );
        const duePast = computed(() => store.state.turnDue <= 0);
        return {
            duePast,
            dueShort,
            dueLong,
            gameTurn,
        };
    },
};
</script>

<template>
    <teleport to="#gameDataTeleportTarget">
        <aside class="game-data">
            <ul>
                <li>{{ $t("common.drawer.currentTurn") }}</li>
                <li class="turn">
                    <icon name="turn" />
                    {{ gameTurn }}
                </li>
            </ul>
            <ul>
                <li>{{ $t("common.drawer.nextTurn") }}<br /></li>
                <li v-if="!duePast">
                    <time :datetime="dueLong" :title="dueLong">{{
                        dueShort
                    }}</time>
                </li>
                <li v-if="duePast">{{ $t("common.drawer.now") }}</li>
            </ul>
        </aside>
    </teleport>
</template>

<style lang="scss" scoped>
.game-data {
    padding: 10px 15px;
    border-bottom: 2px solid transparent;

    @include respond-to("small") {
        padding: 15px 20px;
    }

    @include themed() {
        border-color: t("g-ebony");
    }

    > ul {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 0 0 10px 0;
        margin: 0;

        list-style: none;

        font-size: 15px;

        &:last-child {
            padding-bottom: 0;
        }

        @include themed() {
            color: t("t-tint");
        }

        > li {
            display: flex;
            align-items: center;
        }
    }

    .turn {
        padding: 2px 5px;

        @include themed() {
            background: t("g-raven");
            color: t("b-viking");
        }

        > .icon {
            margin-right: 5px;

            @include themed() {
                color: t("t-light");
            }
        }
    }
}
</style>
