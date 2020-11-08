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
            ]
        ],
        'shipyardInstalled' => 'You have started building a new shipyard. It will be ready in :num turns.'
    ],

    'shipyards' => [
        'navTitle' => 'Shipyards'
    ],

    'fleets' => [
        'navTitle' => 'Fleets'
    ],

    'research' => [
        'navTitle' => 'Research'
    ],

    'starmap' => [
        'navTitle' => 'Starmap'
    ],

    'diplomacy' => [
        'navTitle' => 'Diplomacy'
    ],

];
