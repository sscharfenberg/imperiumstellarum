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
            'noFunds' => 'Sie haben nicht genügend Resourcen.',
            'processing' => 'Die Runde wird gerade berechnet, bitte warten Sie ein wenig.',
            'notStarted' => 'Das Spiel hat noch nicht begonnen.',
            'noPlayer' => 'Sie haben sich in das Spiel nicht eingeschrieben.',
            'header' => [
                'alreadyBuilding' => 'Es ist bereits eine Verbesserung des Lagers im Bau.',
                'level' => 'Diese Verbesserung kann nicht installiert werden.'
            ]
        ],
        'storageUpgradeOrdered' => 'Sie haben begonnen eine Lager Erweiterung zu bauen. Sie wird in :num Runden bereit stehen.'
    ],

    'empire' => [
        'navTitle' => 'Imperium',
        'title' =>  'Unser Imperium',
        'errors' => [
            'star' => [
                'owner' => 'Sie sind nicht der Besitzer dieses Sterns.',
                'required' => 'Der Name des Sterns darf nicht leer sein.',
                'between' => 'Der Name des Sterns muss zwischen :min und :max Zeichen haben.',
            ],
            'planet' => [
                'owner' => 'Sie sind nicht der Besitzer dieses Planeten.',
                'between' => 'Nahrungsverbrauch muss zwischen :min und :max liegen.',
                'noPopulation' => 'Dieser Planet hat keine Bevölkerung.'
            ],
            'harvester' => [
                'owner' => 'Sie sind nicht der Besitzer dieses Planeten.',
                'slot' => 'Es kann kein Ernter dieses Typs installiert werden.'
            ],
            'shipyard' => [
                'owner' => 'Sie sind nicht der Besitzer dieses Planeten.',
                'alreadyInstalled' => 'Auf diesem Planeten gibt es bereits eine Werft.',
            ]
        ],
        'shipyardInstalled' => 'Sie haben begonnen eine neue Werft zu bauen. Sie wird in :num Runden bereit stehen.'
    ],

    'shipyards' => [
        'navTitle' => 'Werften'
    ],

    'fleets' => [
        'navTitle' => 'Flotten'
    ],

    'research' => [
        'navTitle' => 'Forschung'
    ],

    'starmap' => [
        'navTitle' => 'Sternenkarte'
    ],

    'diplomacy' => [
        'navTitle' => 'Diplomatie'
    ],

];
