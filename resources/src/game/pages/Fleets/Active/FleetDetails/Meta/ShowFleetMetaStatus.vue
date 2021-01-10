<script>
/******************************************************************************
 * PageComponent: ShowFleetMetaStatus
 *****************************************************************************/
import { useStore } from "vuex";
import { computed } from "vue";
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "ShowFleetMetaStatus",
    props: {
        fleetId: String,
    },
    components: { Icon },
    setup(props) {
        const store = useStore();
        const i18n = useI18n();

        const fleet = computed(() =>
            store.getters["fleets/fleetById"](props.fleetId)
        );
        const ourStarIds = computed(() =>
            store.state.fleets.stars.map((s) => s.id)
        );

        // ftl
        const ftlLabel = computed(() => (fleet.value.ftl ? "Yes" : "no"));

        // location
        const stationary = computed(() => fleet.value.starId);
        const locationStar = computed(() => {
            return stationary.value
                ? store.getters["fleets/starById"](fleet.value.starId)
                : "transit"; // TODO
        });
        const systemLabel = computed(() => {
            const star = {
                name: locationStar.value.name,
                x: locationStar.value.x,
                y: locationStar.value.y,
            };
            return stationary.value
                ? i18n.t("fleets.active.location.status.inSystem.our", star)
                : i18n.t("fleets.active.location.transit", star);
        });
        return {
            fleet,
            ftlLabel,
            ourStarIds,
            systemLabel,
        };
    },
};
</script>

<template>
    <ul class="fleet-meta__status">
        <li
            :title="
                $t('fleets.active.location.status.transit', { x: 36, y: 16 })
            "
            :aria-label="
                $t('fleets.active.location.status.transit', { x: 36, y: 16 })
            "
        >
            <icon name="transit" />
            {{ 36 }}/{{ 16 }}
        </li>
        <li
            v-if="ourStarIds.includes(fleet.starId)"
            :aria-label="systemLabel"
            :title="systemLabel"
        >
            <icon name="location" />
            {{ systemLabel }}
        </li>
        <!-- TODO: in enemy|allied|neutral system -->
        <li
            v-if="fleet.ftl"
            :title="$t('fleets.active.location.status.ftl.label')"
            :aria-label="$t('fleets.active.location.status.ftl.label')"
        >
            <icon name="tech-ftl" />
            {{ $t("fleets.active.location.status.ftl.short") }}
        </li>
        <li
            v-if="!fleet.ftl"
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
