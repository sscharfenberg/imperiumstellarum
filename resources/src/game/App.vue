<script>
/******************************************************************************
 * App entry component
 *****************************************************************************/
import { useI18n } from "vue-i18n";
import DrawerGameNavigation from "Components/Drawer/DrawerGameNavigation";
import DrawerGameData from "Components/Drawer/DrawerGameData";
export default {
    name: "App",
    components: { DrawerGameNavigation, DrawerGameData },
    setup() {
        return {
            ...useI18n(),
        };
    },
};
</script>

<template>
    <drawer-game-data />
    <drawer-game-navigation />
    <router-view />
</template>
