/******************************************************************************
 * game routes
 *****************************************************************************/

export default [
    {
        path: "/empire",
        name: "Empire",
        component: () =>
            import(/* webpackChunkName: "empire" */ "Pages/Empire/Empire"),
    },
    {
        path: "/fleets",
        name: "Fleets",
        component: () =>
            import(/* webpackChunkName: "fleets" */ "Pages/Fleets/Fleets"),
    },
    {
        path: "/diplomacy",
        name: "Diplomacy",
        component: () =>
            import(
                /* webpackChunkName: "galnet" */ "Pages/Diplomacy/Diplomacy"
            ),
    },
    {
        path: "/research",
        name: "Research",
        component: () =>
            import(
                /* webpackChunkName: "research" */ "Pages/Research/Research"
            ),
    },
    {
        path: "/shipyards",
        name: "Shipyards",
        component: () =>
            import(
                /* webpackChunkName: "shipyards" */ "Pages/Shipyards/Shipyards"
            ),
    },
    {
        path: "/starchart",
        name: "Starchart",
        component: () =>
            import(
                /* webpackChunkName: "starmap" */ "Pages/Starchart/Starchart"
            ),
    },
    {
        path: "/messages",
        name: "Messages",
        component: () =>
            import(
                /* webpackChunkName: "messages" */ "Pages/Messages/Messages"
            ),
    },
    {
        path: "/encounters",
        name: "Encounters",
        component: () =>
            import(
                /* webpackChunkName: "encounters" */ "Pages/Encounters/Encounters"
            ),
    },
    {
        path: "/encounter/:encounterId/details",
        name: "EncounterDetails",
        component: () =>
            import(
                /* webpackChunkName: "encounters" */ "Pages/Encounters/EncounterDetails/EncounterDetails"
            ),
    },
];
