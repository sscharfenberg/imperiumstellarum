<?php

/**
|--------------------------------------------------------------------------
| Game Rules
|--------------------------------------------------------------------------
|
| This config file contains all game-related rules.
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
                        'minerals' => 250,
                        'energy' => 550,
                        'turns' => 24
                    ]
                ],
                '2' => [
                    'amount' => 1000,
                    'costs' => [
                        'minerals' => 400,
                        'energy' => 800,
                        'turns' => 32
                    ]
                ],
                '3' => [
                    'amount' => 1500,
                    'costs' => [
                        'minerals' => 750,
                        'energy' => 1250,
                        'turns' => 48
                    ]
                ],
                '4' => [
                    'amount' => 2500,
                    'costs' => [
                        'minerals' => 1500,
                        'energy' => 2500,
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
                        'energy' => 800,
                        'turns' => 16
                    ]
                ],
                '2' => [
                    'amount' => 2000,
                    'costs' => [
                        'energy' => 1200,
                        'turns' => 24
                    ]
                ],
                '3' => [
                    'amount' => 3000,
                    'costs' => [
                        'energy' => 2000,
                        'turns' => 32
                    ]
                ],
                '4' => [
                    'amount' => 5000,
                    'costs' => [
                        'energy' => 3000,
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
                        'chance' => 70,
                        'max' => 3,
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
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'food' => [
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'minerals' => [
                        'chance' => 15,
                        'max' => 1,
                        'potential' => [0.5, 1]
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
                        'chance' => 50,
                        'max' => 2,
                        'potential' => [0.7, 1.3]
                    ],
                    'research' => [
                        'chance' => 70,
                        'max' => 3,
                        'potential' => [1, 1.5]
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
        'hullTypes' => [
            'small' => [
                'costs' => [
                    'energy' => 400,
                    'minerals' => 400,
                    'research' => 200,
                    'turns' => 72
                ],
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
                ]
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
                ]
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
                ]
            ]
        ]
    ]

];
