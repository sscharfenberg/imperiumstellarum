<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 'Game' Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the game itself
    |
    */

    'common' => [
        'errors' => [
            'noFunds' => 'You can\'t afford this.',
            'processing' => 'The turn is currently being processed, please wait a while.',
            'notStarted' => 'The game has not yet started.',
            'noPlayer' => 'Your are not enlisted in this game.',
            'header' => [
                'alreadyBuilding' => 'There is already a storage upgrade of this type in construction.',
                'level' => 'This storage level can not be constructed.'
            ]
        ],
        'storageUpgradeOrdered' => 'You have begun construction of a storage upgrade. It will be ready in :num turns.'
    ],

    'empire' => [
        'navTitle' => 'Empire',
        'title' =>  'Our Empire',
        'errors' => [
            'star' => [
                'owner' => 'You do not own this star.',
                'required' => 'The star name must not be empty',
                'between' => 'The star name must have between :min and :max characters.',
            ],
            'planet' => [
                'owner' => 'You do not own this planet.',
                'between' => 'Food consumption needs to be between :min and :max.',
                'noPopulation' => 'This planet does not have a population.'
            ],
            'harvester' => [
                'owner' => 'You do not own this planet.',
                'slot' => 'You can\'t install a harvester of this type.'
            ],
            'shipyard' => [
                'owner' => 'You do not own this planet.',
                'alreadyInstalled' => 'There is already a shipyard on this planet.',
                'population' => 'Shipyards can only be installed on a planet with population.',
                'noUpgrade' => 'This is not a valid shipyard upgrade.'
            ]
        ],
        'harvesterInstalled' => 'Harvester is being installed.',
        'shipyardInstalled' => 'You have started building a new shipyard. It will be ready in :num turns.',
        'shipyardUpgrading' => 'You have started upgrading a shipyard. It will be ready in :num turns.'
    ],

    'shipyards' => [
        'navTitle' => 'Shipyards',
        'errors' => [
            'blueprint' => [
                'bpMax' => 'You already have the maximum number of blueprints',
                'hullType' => 'You can\'t create blueprints with this hull type.',
                'moduleMax' => 'Too many modules installed.',
                'modulesInvalid' => 'You have installed invalid modules for this hull type.',
                'className' => 'The class name supplied is not valid.',
                'classNameUsed' => 'The class name has already been used.',
                'owner' => 'You do not own this blueprint.'
            ],
            'constructionContract' => [
                'shipyardOwner' => 'You do not own this shipyard.',
                'amount' => 'The amount of ships is invalid.',
                'shipyard' => 'This shipyard can not build this blueprint.',
                'funds' => 'You do not have sufficient resources to start building the first ship.'
            ]
        ],
        'blueprintSaved' => 'Blueprint finalzed.',
        'blueprintDeleted' => 'Blueprint deleted.',
        'blueprintRenamed' => 'Blueprint renamed.'
    ],

    'fleets' => [
        'navTitle' => 'Fleets'
    ],

    'research' => [
        'navTitle' => 'Research',
        'errors' => [
            'priority' => [
                'invalid' => 'Forschungs Priorität ist ungültig.'
            ],
            'enqueue' => [
                'area' => 'This area does not exist.',
                'alreadyResearched' => 'This level is already researched or enqueued.',
                'bounds' => 'This level is not valid.',
                'max' => 'Already researching the maximum number of jobs allowed.',
            ],
            'order' => [
                'owned' => 'Some of these research jobs are not owned by you.',
                'order' => 'Research job order is invalid. You need to research lower levels first.',
                'max' => 'You can\'t queue more than :num research jobs.',
            ],
            'delete' => [
                'owned' => 'You do not own this research job',
                'order' => 'You have a research job of higher level enqueued.'
            ]
        ]
    ],

    'starchart' => [
        'navTitle' => 'Starmap'
    ],

    'diplomacy' => [
        'navTitle' => 'Diplomacy'
    ],

];
