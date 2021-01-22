<script>
/******************************************************************************
 * PageComponent: ShowShipyardStatus
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowShipyardStatus",
    props: {
        shipyardId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const shipyard = computed(() =>
            store.getters["fleets/shipyardById"](props.shipyardId)
        );
        return { shipyard };
    },
};
</script>

<template>
    <ul class="shipyard-status">
        <li
            v-if="shipyard.untilComplete"
            class="shipyard-status__construction"
            :title="
                $t('fleets.active.shipyard.construction', {
                    turns: shipyard.untilComplete,
                })
            "
            :aria-label="
                $t('fleets.active.shipyard.construction', {
                    turns: shipyard.untilComplete,
                })
            "
        >
            <icon name="res-turns" />
            {{ shipyard.untilComplete }}
        </li>
        <li
            v-if="!shipyard.untilComplete"
            class="shipyard-status__ready"
            :title="$t('fleets.active.shipyard.ready')"
            :aria-label="$t('fleets.active.shipyard.ready')"
        >
            <icon name="build" />
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.shipyard-status {
    display: flex;
    align-items: center;

    padding: 0;
    margin: 0 8px 0 0;

    list-style: none;

    &__construction {
        display: flex;
        align-items: center;

        padding: 4px;
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-bunker");
            color: t("t-subdued");
            border-color: t("s-building");
        }

        .icon {
            margin-right: 4px;

            @include themed() {
                color: darken(t("t-subdued"), 25%);
            }
        }
    }

    &__ready {
        padding: 4px;
        border: 1px solid transparent;

        @include themed() {
            background-color: t("g-bunker");
            color: t("s-active");
            border-color: t("s-active");
        }
    }
}
</style>
