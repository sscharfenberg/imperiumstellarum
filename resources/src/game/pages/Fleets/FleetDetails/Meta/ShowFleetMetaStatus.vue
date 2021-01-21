<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaStatus
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowFleetMetaStatus",
    props: {
        holderId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const holder = computed(() => {
            const fleet = store.getters["fleets/fleetById"](props.holderId);
            const shipyard = store.getters["fleets/shipyardById"](
                props.holderId
            );
            return fleet && fleet.id ? fleet : shipyard;
        });
        // ftl
        const ftlLabel = computed(() => (holder.value.ftl ? "Yes" : "no"));
        return {
            holder,
            ftlLabel,
        };
    },
};
</script>

<template>
    <ul class="fleet-meta__status">
        <li
            v-if="holder.ftl && !holder.planetName"
            :title="$t('fleets.active.location.status.ftl.label')"
            :aria-label="$t('fleets.active.location.status.ftl.label')"
        >
            <icon name="tech-ftl" />
            {{ $t("fleets.active.location.status.ftl.short") }}
        </li>
        <li
            v-if="!holder.ftl && !holder.planetName"
            :title="$t('fleets.active.location.status.noFtl')"
            :aria-label="$t('fleets.active.location.status.noFtl')"
        >
            <icon name="warning" />
            {{ $t("fleets.active.location.status.ftl.short") }}
        </li>
    </ul>
</template>

<style lang="scss" scoped>
.fleet-meta__status {
    display: flex;
    flex-wrap: wrap;

    padding: 0;
    margin: 0;

    list-style: none;

    @include respond-to("medium") {
        margin-left: auto;
    }

    > li {
        display: flex;
        align-items: center;

        padding: 4px;
        margin: 0 4px 4px 0;
        clip-path: polygon(
            0 0,
            calc(100% - 5px) 0,
            100% 5px,
            100% 100%,
            5px 100%,
            0 calc(100% - 5px)
        );

        @include respond-to("medium") {
            padding: 8px;
            margin: 0 8px 8px 0;
            clip-path: polygon(
                0 0,
                calc(100% - 10px) 0,
                100% 10px,
                100% 100%,
                10px 100%,
                0 calc(100% - 10px)
            );
        }

        &:last-child {
            margin-right: 0;
        }

        @include themed() {
            background-color: t("g-deep");
        }

        .icon {
            margin-right: 4px;

            @include respond-to("medium") {
                margin-right: 8px;
            }
        }
    }
}
</style>
