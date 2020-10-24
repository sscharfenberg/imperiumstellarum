<script>
/******************************************************************************
 * Navigation inside the drawer menu
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import Icon from "Components/Icon/Icon";
export default {
    name: "DrawerGameNavigation",
    components: { Icon },
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <teleport to="#drawerTeleportTarget">
        <ul class="drawer-list">
            <li class="drawer-list__item">
                <router-link :to="{ name: 'Empire' }" class="drawer-list__link">
                    <icon name="empire" /> {{ t("empire.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Shipyards' }"
                    class="drawer-list__link"
                >
                    <icon name="shipyards" /> {{ t("shipyards.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link :to="{ name: 'Fleets' }" class="drawer-list__link">
                    <icon name="fleets" /> {{ t("fleets.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Research' }"
                    class="drawer-list__link"
                >
                    <icon name="research" /> {{ t("research.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Starmap' }"
                    class="drawer-list__link"
                >
                    <icon name="starmap" /> {{ t("starmap.navTitle") }}
                </router-link>
            </li>
            <li class="drawer-list__item">
                <router-link
                    :to="{ name: 'Diplomacy' }"
                    class="drawer-list__link"
                >
                    <icon name="diplomacy" /> {{ t("diplomacy.navTitle") }}
                </router-link>
            </li>
        </ul>
    </teleport>
</template>
