/******************************************************************************
 * game routes
 *****************************************************************************/

export default [
    {
        path: "/empire",
        name: "Empire",
        component: () =>
            import(
                /* webpackChunkName: "empire" */ "@/game/pages/Empire/Empire"
            ),
    },
    {
        path: "/fleets",
        name: "Fleets",
        component: () =>
            import(
                /* webpackChunkName: "fleets" */ "@/game/pages/Fleets/Fleets"
            ),
    },
    {
        path: "/diplomacy",
        name: "Diplomacy",
        component: () =>
            import(
                /* webpackChunkName: "galnet" */ "@/game/pages/Diplomacy/Diplomacy"
            ),
    },
    {
        path: "/research",
        name: "Research",
        component: () =>
            import(
                /* webpackChunkName: "research" */ "@/game/pages/Research/Research"
            ),
    },
    {
        path: "/shipyards",
        name: "Shipyards",
        component: () =>
            import(
                /* webpackChunkName: "shipyards" */ "@/game/pages/Shipyards/Shipyards"
            ),
    },
    {
        path: "/starmap",
        name: "Starmap",
        component: () =>
            import(
                /* webpackChunkName: "starmap" */ "@/game/pages/Starmap/Starmap"
            ),
    },
];
