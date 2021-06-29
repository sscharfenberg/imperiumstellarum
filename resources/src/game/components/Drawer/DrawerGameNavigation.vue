<script>
/******************************************************************************
 * Navigation inside the drawer menu
 *****************************************************************************/
import { computed } from "vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import Icon from "Components/Icon/Icon";
export default {
    name: "DrawerGameNavigation",
    components: { Icon },
    setup() {
        const store = useStore();
        const route = useRoute();
        const unreadMessages = computed(() => store.state.unreadMessages);
        const unreadEncounters = computed(() => store.state.unreadEncounters);
        const withinEncounters = computed(() => !!route.params.encounterId);
        return {
            unreadMessages,
            unreadEncounters,
            withinEncounters,
        };
    },
};
</script>

<template>
    <teleport to="#drawerTeleportTarget">
        <ul class="drawer-list">
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Messages' }"
                    class="drawer-list__link"
                >
                    <icon name="messages" /> {{ $t("messages.navTitle") }}
                    <aside class="aside">
                        <span v-if="unreadMessages > 0" class="unread">{{
                            unreadMessages
                        }}</span>
                    </aside>
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link :to="{ name: 'Empire' }" class="drawer-list__link">
                    <icon name="empire" /> {{ $t("empire.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Starchart' }"
                    class="drawer-list__link"
                >
                    <icon name="starchart" /> {{ $t("starchart.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link :to="{ name: 'Fleets' }" class="drawer-list__link">
                    <icon name="fleets" /> {{ $t("fleets.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Shipyards' }"
                    class="drawer-list__link"
                >
                    <icon name="shipyards" /> {{ $t("shipyards.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Research' }"
                    class="drawer-list__link"
                >
                    <icon name="research" /> {{ $t("research.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Encounters' }"
                    class="drawer-list__link"
                    :class="{ active: withinEncounters }"
                >
                    <icon name="encounters" /> {{ $t("encounters.navTitle") }}
                    <aside class="aside">
                        <span v-if="unreadEncounters > 0" class="unread">{{
                            unreadEncounters
                        }}</span>
                    </aside>
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Diplomacy' }"
                    class="drawer-list__link"
                >
                    <icon name="diplomacy" /> {{ $t("diplomacy.navTitle") }}
                </router-link>
            </li>
        </ul>
    </teleport>
</template>

<style lang="scss" scoped>
.unread {
    padding: 2px 4px;
    margin: 0 4px 0 0;

    clip-path: polygon(
        5px 0,
        calc(100% - 5px) 0,
        100% 5px,
        100% calc(100% - 5px),
        calc(100% - 5px) 100%,
        5px 100%,
        0 calc(100% - 5px),
        0 5px
    );

    font-weight: 600;

    @include themed() {
        background-color: t("s-error");
        color: t("s-warning");
    }

    &:last-child {
        margin-right: 0;
    }
}

.aside {
    margin-left: auto;
}
</style>
