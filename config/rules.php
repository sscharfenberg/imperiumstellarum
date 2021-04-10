<?php

/**
|--------------------------------------------------------------------------
| Game Rules
|--------------------------------------------------------------------------
|
| This config file contains all game-related rules.
| These rules are available to the client as a global JS object 'window.rules'
|
*/

return [

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Players
    |--------------------------------------------------------------------------
    |
    */

    'player' => [
        'name' => [
            'min' => 6,
            'max' => 32
        ],
        'ticker' => [
            'min' => 2,
            'max' => 5
        ],
        'colour' => 6,

        /*
        |--------------------------------------------------------------------------
        | Resource Types
        |--------------------------------------------------------------------------
        |
        | These values determine how many of a certain resource can be stored
        | and how much it costs to upgrade the storage level
        |
        */

        'resourceTypes' => [

            // ENERGY ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            'energy' => [
                '0' => [
                    'amount' => 1000,
                ],
                '1' => [
                    'amount' => 1500,
                    'costs' => [
                        'minerals' => 400,
                        'energy' => 400,
                        'turns' => 16
                    ]
                ],
                '2' => [
                    'amount' => 2000,
                    'costs' => [
                        'minerals' => 600,
                        'energy' => 600,
                        'turns' => 24
                    ]
                ],
                '3' => [
                    'amount' => 3000,
                    'costs' => [
                        'minerals' => 1000,
                        'energy' => 1000,
                        'turns' => 32
                    ]
                ],
                '4' => [
                    'amount' => 5000,
                    'costs' => [
                        'minerals' => 2000,
                        'energy' => 2000,
                        'turns' => 64
                    ]
                ]
            ],

            // FOOD ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            'food' => [
                '0' => [
                    'amount' => 500,
                ],
                '1' => [
                    'amount' => 750,
                    'costs' => [
                        'minerals' => 550,
                        'energy' => 250,
                        'turns' => 24
                    ]
                ],
                '2' => [
                    'amount' => 1000,
                    'costs' => [
                        'minerals' => 800,
                        'energy' => 400,
                        'turns' => 32
                    ]
                ],
                '3' => [
                    'amount' => 1500,
                    'costs' => [
                        'minerals' => 1250,
                        'energy' => 750,
                        'turns' => 48
                    ]
                ],
                '4' => [
                    'amount' => 2500,
                    'costs' => [
                        'minerals' => 2500,
                        'energy' => 1500,
                        'turns' => 96
                    ]
                ]
            ],

            // MINERALS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            'minerals' => [
                '0' => [
                    'amount' => 1000,
                ],
                '1' => [
                    'amount' => 1500,
                    'costs' => [
                        'minerals' => 250,
                        'energy' => 550,
                        'turns' => 16
                    ]
                ],
                '2' => [
                    'amount' => 2000,
                    'costs' => [
                        'minerals' => 400,
                        'energy' => 800,
                        'turns' => 24
                    ]
                ],
                '3' => [
                    'amount' => 3000,
                    'costs' => [
                        'minerals' => 750,
                        'energy' => 1250,
                        'turns' => 32
                    ]
                ],
                '4' => [
                    'amount' => 5000,
                    'costs' => [
                        'minerals' => 1500,
                        'energy' => 2500,
                        'turns' => 64
                    ]
                ]
            ],

            // RESEARCH ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            'research' => [
                '0' => [
                    'amount' => 500,
                ],
                '1' => [
                    'amount' => 750,
                    'costs' => [
                        'food' => 250,
                        'energy' => 250,
                        'minerals' => 250,
                        'turns' => 24
                    ]
                ],
                '2' => [
                    'amount' => 1000,
                    'costs' => [
                        'food' => 400,
                        'energy' => 400,
                        'minerals' => 400,
                        'turns' => 32
                    ]
                ],
                '3' => [
                    'amount' => 1500,
                    'costs' => [
                        'food' => 750,
                        'energy' => 750,
                        'minerals' => 750,
                        'turns' => 48
                    ]
                ],
                '4' => [
                    'amount' => 2500,
                    'costs' => [
                        'food' => 1500,
                        'energy' => 1500,
                        'minerals' => 1500,
                        'turns' => 96
                    ]
                ]
            ]
        ]
    ],


    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Stars
    |--------------------------------------------------------------------------
    |
    */

    'stars' => [

        /*
        |--------------------------------------------------------------------------
        | Spectral types
        |--------------------------------------------------------------------------
        |
        | These value determine the likelyhood of a specific star having the spectral
        | type ('chance' for NPC stars, 'chanceHome' for player stars)
        | and the modificator for the number of planets ('planetsMod').
        | It is totally unrealistic that O/B/A/Y Stars have less planets, but this
        | helps gameplay.
        |
        */

        'types' => [
            'O' => [
                'chance' => 5,
                'chanceHome' => 0,
                'planetsMod' => -3
            ],
            'B' => [
                'chance' => 5,
                'chanceHome' => 0,
                'planetsMod' => -2
            ],
            'A' => [
                'chance' => 5,
                'chanceHome' => 0,
                'planetsMod' => -2
            ],
            'F' => [
                'chance' => 10,
                'chanceHome' => 20,
                'planetsMod' => 0
            ],
            'G' => [
                'chance' => 20,
                'chanceHome' => 40,
                'planetsMod' => 2
            ],
            'K' => [
                'chance' => 20,
                'chanceHome' => 40,
                'planetsMod' => 1
            ],
            'M' => [
                'chance' => 30,
                'chanceHome' => 0,
                'planetsMod' => 0
            ],
            'Y' => [
                'chance' => 5,
                'chanceHome' => 0,
                'planetsMod' => -2
            ]
        ],

        'name' => [
            'min' => 4,
            'max' => 40
        ],

        'minSlotsHome' => [
            'energy' => 2,
            'minerals' => 2,
            'research' => 2,
            'food' => 2
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Planets
    |--------------------------------------------------------------------------
    |
    */

    'planets' => [

        /*
        |--------------------------------------------------------------------------
        | Number of planets
        |--------------------------------------------------------------------------
        |
        | These value determine the min/max values for the number of planets,
        | affected by 'planetsMod' of a certain star spectral type
        |
        */

        'num' => [
            'npc' => [
                'min' => 4,
                'max' => 6
            ],
            'player' => [
                'min' => 6,
                'max' => 9
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | Planet types
        |--------------------------------------------------------------------------
        |
        | chance and chanceHome are added each and a random number between min and max is chosen.
        | they do not necessarily add up to 100.
        | the resourceSlot chances, however, are percentage chances. they are also cumulative:
        | if a 30 is rolled and the chance was 70, there is a new chance with 40% for a resource slot.
        | potential is the modifier for resource extraction.
        |
        */

        'types' => [
            'terrestrial' => [
                'chance' => 20,
                'chanceHome' => 37,
                'resourceSlots' => [
                    'food' => [
                        'chance' => 70,
                        'max' => 2,
                        'potential' => [1, 1.5]
                    ],
                    'energy' => [
                        'chance' => 50,
                        'max' => 1,
                        'potential' => [0.7, 1.3]
                    ],
                    'minerals' => [
                        'chance' => 50,
                        'max' => 1,
                        'potential' => [0.7, 1.3]
                    ],
                ]
            ],
            'gas' => [
                'chance' => 17,
                'chanceHome' => 13,
                'resourceSlots' => [
                    'research' => [
                        'chance' => 70,
                        'max' => 3,
                        'potential' => [1, 1.5]
                    ],
                    'energy' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ]
                ]
            ],
            'ice' => [
                'chance' => 16,
                'chanceHome' => 10,
                'resourceSlots' => [
                    'minerals' => [
                        'chance' => 30,
                        'max' => 1,
                        'potential' => [0.6, 1.2]
                    ],
                    'food' => [
                        'chance' => 70,
                        'max' => 2,
                        'potential' => [1, 1.5]
                    ],
                    'research' => [
                        'chance' => 15,
                        'max' => 1,
                        'potential' => [0.5, 1]
                    ]
                ]
            ],
            'iron' => [
                'chance' => 20,
                'chanceHome' => 10,
                'resourceSlots' => [
                    'minerals' => [
                        'chance' => 100,
                        'max' => 2,
                        'potential' => [1, 1.5]
                    ],
                    'research' => [
                        'chance' => 30,
                        'max' => 1,
                        'potential' => [0.6, 1.2]
                    ],
                    'food' => [
                        'chance' => 15,
                        'max' => 1,
                        'potential' => [0.5, 1]
                    ]
                ]
            ],
            'desert' => [
                'chance' => 19,
                'chanceHome' => 10,
                'resourceSlots' => [
                    'energy' => [
                        'chance' => 70,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'minerals' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ]
                ]
            ],
            'toxic' => [
                'chance' => 10,
                'chanceHome' => 13,
                'resourceSlots' => [
                    'minerals' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'energy' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'research' => [
                        'chance' => 15,
                        'max' => 1,
                        'potential' => [0.5, 1]
                    ]
                ]
            ],
            'carbon' => [
                'chance' => 7,
                'chanceHome' => 7,
                'resourceSlots' => [
                    'minerals' => [
                        'chance' => 70,
                        'max' => 2,
                        'potential' => [1, 1.5]
                    ],
                    'research' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ]
                ]
            ],
            'tomb' => [
                'chance' => 7,
                'chanceHome' => 7,
                'resourceSlots' => [
                    'energy' => [
                        'chance' => 30,
                        'max' => 1,
                        'potential' => [0.6, 1.2]
                    ],
                    'minerals' => [
                        'chance' => 30,
                        'max' => 1,
                        'potential' => [0.6, 1.2]
                    ],
                    'food' => [
                        'chance' => 30,
                        'max' => 1,
                        'potential' => [0.6, 1.2]
                    ],
                    'research' => [
                        'chance' => 100,
                        'max' => 3,
                        'potential' => [1.2, 2.4]
                    ]
                ]
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | population values
        |--------------------------------------------------------------------------
        |
        | 'startingColony' is the population on player starting colonies
        |
        */

        'population' => [
            'bounds' => [
                'min' => 0,
                'max' => 20
            ],
            'startingColony' => 10
        ],

        /*
        |--------------------------------------------------------------------------
        | food settings
        |--------------------------------------------------------------------------
        */

        'food' => [
            'min' => 0,
            'max' => 3,
            'default' => 1
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for TechLevels / Research
    |--------------------------------------------------------------------------
    |
    */

    'tech' => [
        'bounds' => [
            'min' => 0,
            'max' => 10
        ],
        'queue' => 5, // max queue length
        'researchPriority' => [
            'min' => 0.5,
            'max' => 10,
            'default' => 1
        ],
        'tlFactor' => 10, // percentage that a techLevel > 0 increases damage / hitpoints

        /*
        |--------------------------------------------------------------------------
        | Tech Areas: Costs and damage multipliers
        |--------------------------------------------------------------------------
        */

        'areas' => [
            'plasma' => [
                'costs' => [1000, 1200, 1440, 1720, 2048, 2480, 3200, 4480, 6720, 11000],
                'dmgMultipliers' => [
                    'armour' => 1.5,
                    'shields' => 0.5
                ]
            ],
            'railgun' => [
                'costs' => [1000, 1200, 1440, 1720, 2048, 2480, 3200, 4480, 6720, 11000],
                'dmgMultipliers' => [
                    'armour' => 0.5,
                    'shields' => 1.5
                ]
            ],
            'missile' => [
                'costs' => [1000, 1200, 1440, 1720, 2048, 2480, 3200, 4480, 6720, 11000],
                'dmgMultipliers' => [
                    'armour' => 0.7,
                    'shields' => 1.3
                ]
            ],
            'laser' => [
                'costs' => [1000, 1200, 1440, 1720, 2048, 2480, 3200, 4480, 6720, 11000],
                'dmgMultipliers' => [
                    'armour' => 1.3,
                    'shields' => 0.7
                ]
            ],
            'shields' => [
                'costs' => [1500, 1800, 2160, 2512, 3072, 3720, 4800, 6720, 10080, 16500]
            ],
            'armour' => [
                'costs' => [1500, 1800, 2160, 2512, 3072, 3720, 4800, 6720, 10080, 16500]
            ]
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Harvesters
    |--------------------------------------------------------------------------
    |
    */

    'harvesters' => [
        'energy' => [
            'costs' => [
                'minerals' => 100,
                'energy' => 50,
                'turns' => 8
            ],
            'baseProduction' => 20
        ],
        'food' => [
            'costs' => [
                'minerals' => 100,
                'energy' => 100,
                'turns' => 6
            ],
            'baseProduction' => 10
        ],
        'minerals' => [
            'costs' => [
                'minerals' => 50,
                'energy' => 100,
                'turns' => 8
            ],
            'baseProduction' => 20
        ],
        'research' => [
            'costs' => [
                'minerals' => 100,
                'energy' => 100,
                'turns' => 12
            ],
            'baseProduction' => 10
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Shipyards
    |--------------------------------------------------------------------------
    |
    */

    'shipyards' => [

        /*
        |--------------------------------------------------------------------------
        | Shipyard types: build/upgrade costs, constructable hulls
        |--------------------------------------------------------------------------
        */

        'hullTypes' => [
            'small' => [
                'costs' => [
                    'energy' => 400,
                    'minerals' => 400,
                    'research' => 200,
                    'turns' => 72
                ],
                'construct' => ['ark', 'small']
            ],
            'medium' => [
                'costs' => [
                    'energy' => 800,
                    'minerals' => 800,
                    'research' => 400,
                    'turns' => 144
                ],
                'upgradeCosts' => [
                    'energy' => 400,
                    'minerals' => 400,
                    'research' => 200,
                    'turns' => 72
                ],
                'construct' => ['ark', 'small', 'medium']
            ],
            'large' => [
                'costs' => [
                    'energy' => 1600,
                    'minerals' => 1600,
                    'research' => 800,
                    'turns' => 288
                ],
                'upgradeCosts' => [
                    'energy' => 800,
                    'minerals' => 800,
                    'research' => 400,
                    'turns' => 144
                ],
                'construct' => ['ark', 'small', 'medium', 'large']
            ],
            'xlarge' => [
                'costs' => [
                    'energy' => 3200,
                    'minerals' => 3200,
                    'research' => 1600,
                    'turns' => 576
                ],
                'upgradeCosts' => [
                    'energy' => 1600,
                    'minerals' => 1600,
                    'research' => 800,
                    'turns' => 288
                ],
                'construct' => ['ark', 'small', 'medium', 'large', 'xlarge']
            ]
        ],

        /*
        |--------------------------------------------------------------------------
        | Contract rules
        |--------------------------------------------------------------------------
        */

        'contracts' => [
            'amount' => [
                'min' => 1,
                'max' => 25,
            ]
        ]
    ],


    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Ships
    |--------------------------------------------------------------------------
    |
    */

    'ships' => [
        'hullTypes' => [
            'ark' => [
                'slots' => 2,
                'baseAcceleration' => 1,
                'baseHp' => [
                    'structure' => 50,
                    'armour' => 50,
                    'shields' => 50
                ],
                'costs' => [
                    'energy' => 100,
                    'minerals' => 100,
                    'turns' => 16
                ]
            ],
            'small' => [
                'slots' => 5,
                'baseAcceleration' => 4,
                'baseHp' => [
                    'structure' => 100,
                    'armour' => 100,
                    'shields' => 100
                ],
                'costs' => [
                    'energy' => 50,
                    'minerals' => 50,
                    'turns' => 8
                ]
            ],
            'medium' => [
                'slots' => 6,
                'baseAcceleration' => 3,
                'baseHp' => [
                    'structure' => 150,
                    'armour' => 150,
                    'shields' => 150
                ],
                'costs' => [
                    'energy' => 100,
                    'minerals' => 100,
                    'turns' => 16
                ]
            ],
            'large' => [
                'slots' => 7,
                'baseAcceleration' => 2,
                'baseHp' => [
                    'structure' => 225,
                    'armour' => 225,
                    'shields' => 225
                ],
                'costs' => [
                    'energy' => 200,
                    'minerals' => 200,
                    'turns' => 32
                ]
            ],
            'xlarge' => [
                'slots' => 8,
                'baseAcceleration' => 1,
                'baseHp' => [
                    'structure' => 337,
                    'armour' => 337,
                    'shields' => 337
                ],
                'costs' => [
                    'energy' => 400,
                    'minerals' => 400,
                    'turns' => 64
                ]
            ]
        ],
        'name' => [
            'min' => 4,
            'max' => 80
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Ship Blueprints
    |--------------------------------------------------------------------------
    |
    */
    'blueprints' => [
        'className' => [
            'min' => 3,
            'max' => 40
        ],
        'num' => [
            'max' => 20, // hardcap maximum
            'factor' => 0.7 // totalPopulation * factor is the max of a player
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Ship Modules
    |--------------------------------------------------------------------------
    |
    */

    'modules' => [

        /*
         * ark modules
         */
        [
            'hullType' => 'ark',
            'moduleType' => 'engineering',
            'techType' => 'ftl',
            'costs' => [
                'energy' => 37,
                'minerals' => 37,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'ark',
            'moduleType' => 'engineering',
            'techType' => 'colony',
            'costs' => [
                'energy' => 50,
                'minerals' => 50,
                'population' => 0.5,
                'turns' => 2,
            ]
        ],

        /*
         * small modules
         */
        [
            'hullType' => 'small',
            'moduleType' => 'offensive',
            'techType' => 'plasma',
            'baseDmg' => 10,
            'range' => 1,
            'costs' => [
                'energy' => 35,
                'minerals' => 15,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'offensive',
            'techType' => 'missile',
            'baseDmg' => 8,
            'range' => 2,
            'costs' => [
                'energy' => 25,
                'minerals' => 25,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'offensive',
            'techType' => 'railgun',
            'baseDmg' => 7,
            'range' => 3,
            'costs' => [
                'energy' => 15,
                'minerals' => 35,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'offensive',
            'techType' => 'laser',
            'baseDmg' => 6,
            'range' => 5,
            'costs' => [
                'energy' => 25,
                'minerals' => 25,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'defensive',
            'techType' => 'shields',
            'baseHp' => 100,
            'costs' => [
                'energy' => 30,
                'minerals' => 20,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'defensive',
            'techType' => 'armour',
            'baseHp' => 100,
            'costs' => [
                'energy' => 20,
                'minerals' => 30,
                'turns' => 1,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'engineering',
            'techType' => 'ftl',
            'costs' => [
                'energy' => 25,
                'minerals' => 25,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'small',
            'moduleType' => 'engineering',
            'techType' => 'engine',
            'acceleration' => 2,
            'costs' => [
                'energy' => 25,
                'minerals' => 25,
                'turns' => 1,
            ]
        ],

        /*
         * medium modules
         */
        [
            'hullType' => 'medium',
            'moduleType' => 'offensive',
            'techType' => 'plasma',
            'baseDmg' => 15,
            'range' => 2,
            'costs' => [
                'energy' => 52,
                'minerals' => 22,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'offensive',
            'techType' => 'missile',
            'baseDmg' => 12,
            'range' => 3,
            'costs' => [
                'energy' => 37,
                'minerals' => 37,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'offensive',
            'techType' => 'railgun',
            'baseDmg' => 10.5,
            'range' => 4,
            'costs' => [
                'energy' => 22,
                'minerals' => 52,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'offensive',
            'techType' => 'laser',
            'baseDmg' => 9,
            'range' => 6,
            'costs' => [
                'energy' => 37,
                'minerals' => 37,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'defensive',
            'techType' => 'shields',
            'baseHp' => 150,
            'costs' => [
                'energy' => 44,
                'minerals' => 30,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'defensive',
            'techType' => 'armour',
            'baseHp' => 150,
            'costs' => [
                'energy' => 30,
                'minerals' => 44,
                'turns' => 2,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'engineering',
            'techType' => 'ftl',
            'costs' => [
                'energy' => 37,
                'minerals' => 37,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'medium',
            'moduleType' => 'engineering',
            'techType' => 'engine',
            'acceleration' => 2,
            'costs' => [
                'energy' => 37,
                'minerals' => 37,
                'turns' => 2,
            ]
        ],

        /*
         * large modules
         */
        [
            'hullType' => 'large',
            'moduleType' => 'offensive',
            'techType' => 'plasma',
            'baseDmg' => 22.5,
            'range' => 3,
            'costs' => [
                'energy' => 78,
                'minerals' => 34,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'offensive',
            'techType' => 'missile',
            'baseDmg' => 18,
            'range' => 4,
            'costs' => [
                'energy' => 56,
                'minerals' => 56,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'offensive',
            'techType' => 'railgun',
            'baseDmg' => 15.75,
            'range' => 5,
            'costs' => [
                'energy' => 34,
                'minerals' => 78,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'offensive',
            'techType' => 'laser',
            'baseDmg' => 13.5,
            'range' => 7,
            'costs' => [
                'energy' => 56,
                'minerals' => 56,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'defensive',
            'techType' => 'shields',
            'baseHp' => 225,
            'costs' => [
                'energy' => 67,
                'minerals' => 45,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'defensive',
            'techType' => 'armour',
            'baseHp' => 225,
            'costs' => [
                'energy' => 45,
                'minerals' => 67,
                'turns' => 3,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'engineering',
            'techType' => 'ftl',
            'costs' => [
                'energy' => 56,
                'minerals' => 56,
                'turns' => 4,
            ]
        ],
        [
            'hullType' => 'large',
            'moduleType' => 'engineering',
            'techType' => 'engine',
            'acceleration' => 1,
            'costs' => [
                'energy' => 56,
                'minerals' => 56,
                'turns' => 3,
            ]
        ],

        /*
         * xlarge modules
         */
        [
            'hullType' => 'xlarge',
            'moduleType' => 'offensive',
            'techType' => 'plasma',
            'baseDmg' => 33.75,
            'range' => 4,
            'costs' => [
                'energy' => 117,
                'minerals' => 51,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'offensive',
            'techType' => 'missile',
            'baseDmg' => 27,
            'range' => 5,
            'costs' => [
                'energy' => 84,
                'minerals' => 84,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'offensive',
            'techType' => 'railgun',
            'baseDmg' => 23.63,
            'range' => 6,
            'costs' => [
                'energy' => 51,
                'minerals' => 117,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'offensive',
            'techType' => 'laser',
            'baseDmg' => 20.25,
            'range' => 8,
            'costs' => [
                'energy' => 84,
                'minerals' => 84,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'defensive',
            'techType' => 'shields',
            'baseHp' => 337,
            'costs' => [
                'energy' => 100,
                'minerals' => 68,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'defensive',
            'techType' => 'armour',
            'baseHp' => 337,
            'costs' => [
                'energy' => 68,
                'minerals' => 100,
                'turns' => 5,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'engineering',
            'techType' => 'ftl',
            'costs' => [
                'energy' => 84,
                'minerals' => 84,
                'turns' => 7,
            ]
        ],
        [
            'hullType' => 'xlarge',
            'moduleType' => 'engineering',
            'techType' => 'engine',
            'acceleration' => 1,
            'costs' => [
                'energy' => 84,
                'minerals' => 84,
                'turns' => 5,
            ]
        ],
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Fleets
    |--------------------------------------------------------------------------
    |
    */

    'fleets' => [
        'name' => [
            'min' => 4,
            'max' => 40
        ],
        'num' => [
            'max' => 12, // hardcap maximum
            'factor' => 0.6 // totalPopulation * factor is the max of a player
        ],
        'movement' => [
            'turnsPerDistance' => 2.5
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Diplomatic Relations
    |--------------------------------------------------------------------------
    |
    */

    'diplomacy' => [
        'turnsUntilEffective' => 4,
        'status' => [
            '0' => [
                'validChange' => [ 1 ]
            ],
            '1' => [
                'validChange' => [ 0, 2 ]
            ],
            '2' => [
                'validChange' => [ 1 ]
            ]
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Messages
    |--------------------------------------------------------------------------
    |
    */

    'messages' => [
        'subject' => [
            'min' => 3,
            'max' => 80
        ],
        'body' => [
            'min' => 3,
            'max' => 1000,
            'overviewMax' => 80 // max number of chars that are shown in overview
        ],
        'recipients' => [
            'min' => 1,
            'max' => 10
        ],
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for Messages
    |--------------------------------------------------------------------------
    |
    */
    'reports' => [
        'comment' => [
            'min' => 5,
            'max' => 200
        ],
        'reportMessage' => [
            'min' => 20,
            'max' => 1000
        ]
    ],

    /**
    |
    |--------------------------------------------------------------------------
    | Rules for encounters (fleet combat)
    |--------------------------------------------------------------------------
    |
    */
    'encounters' => [
        // modifies acceleration for fleet movement turns.
        // 0.4 = acc is multiplied by 0.8 to 1.2
        'accDeviation' => 0.4,
        'maxTurns' => 10,
        'falloff' => [
            'rangeMultiplier' => 1.5, // range * multiplier > distance => falloff
            'damageMultiplier' => 0.5 // multiplier for damage if distance is within falloff.
        ]
    ]

];
