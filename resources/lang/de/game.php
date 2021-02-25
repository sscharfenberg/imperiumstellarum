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
            'gameNotFound' => 'Dieses Spiel existiert nicht.',
            'noPlayer' => 'Sie haben sich in das Spiel nicht eingeschrieben.',
            'header' => [
                'alreadyBuilding' => 'Es ist bereits eine Verbesserung des Lagers im Bau.',
                'level' => 'Diese Verbesserung kann nicht installiert werden.'
            ],
            'deletion' => 'Löschen fehlgeschlagen, Datenbankfehler.'
        ],
        'storageUpgradeOrdered' => 'Sie haben begonnen eine Lager Erweiterung zu bauen. Sie wird in :num Runden bereit stehen.',
        'hulls' => [
            'ark' => 'Arche',
            'small' => 'Zerstörer',
            'medium' => 'Kreuzer',
            'large' => 'Schlachtschiff',
            'xlarge' => 'Großkampfschiff'
        ]
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
                'population' => 'Werften können nur auf Planeten mit Bevölkerung errichtet werden.',
                'noUpgrade' => 'Das ist keine gültige Aufrüstung der Werft.'
            ]
        ],
        'harvesterInstalled' => 'Ernter wird errichtet.',
        'shipyardInstalling' => 'Sie haben begonnen eine neue Werft zu bauen. Sie wird in :num Runden bereit stehen.',
        'shipyardUpgrading' => 'Sie haben begonnen eine Werft aufzurüsten. Sie wird in :num Runden bereit stehen.'
    ],

    'shipyards' => [
        'navTitle' => 'Werften',
        'errors' => [
            'blueprint' => [
                'bpMax' => 'Sie haben bereits die maximal mögliche Anzahl an Bauplänen.',
                'hullType' => 'Sie können keine Baupläne von diesem Schiffstypen erstellen.',
                'moduleMax' => 'Zu viele Module installiert.',
                'modulesInvalid' => 'Sie haben für diesen Schiffstyp ungültige Module installiert.',
                'className' => 'Der Name für den Schiffstyp ist nicht gültig.',
                'classNameUsed' => 'Der Name des Schiffstyps wird bereits verwendet.',
                'owner' => 'Sie sind nicht der Besitzer dieses Bauplanes.',
                'usedForContract' => 'Dieser Bauplan wird in einem Bauauftrag verwendet; Löschen fehlgeschlagen.'
            ],
            'constructionContract' => [
                'shipyardOwner' => 'Sie sind nicht der Besitzer dieser Werft.',
                'amount' => 'Die Anzahl von Schiffen ist ungültig.',
                'shipyard' => 'Diese Werft kann den gewählten Bauplan nicht bauen.',
                'shipyardConstructing' => 'Diese Werft arbeitet bereits an einem Bauauftrag.',
                'funds' => 'Sie haben nicht genügend Resourcen um das erste Schiff zu bauen.',
                'population' => 'Der Planet der Werft hat nicht genug Bevölkerung.',
                'owner' => 'Sie sind nicht der Besitzer dieses Bauauftrages.'
            ]
        ],
        'blueprintSaved' => 'Bauplan gespeichert.',
        'blueprintDeleted' => 'Bauplan gelöscht.',
        'blueprintRenamed' => 'Name des Bauplans geändert.',
        'constructionContractInstalled' => 'Bauauftrag erstellt. Das erste Schiff wird in :turns Runden ausgeliefert.',
        'constructionContractDeleted' => 'Bauauftrag gelöscht.'
    ],

    'fleets' => [
        'navTitle' => 'Flotten',
        'errors' => [
            'owner' => 'Sie sind nicht der Besitzer dieser Flotte.',
            'starOwner' => 'Sie sind nicht der Besitzer dieses Sternensystems.',
            'shipOwner' => 'Sie sind nicht der Besitzer dieses Schiffs.',
            'name' => 'Der Name der Flotte ist nicht gültig.',
            'shipName' => 'Der Name des Schiffs ist nicht gültig.',
            'nameUnique' => 'Der Name der Flotte darf nicht mehrfach verwendet werden.',
            'notEmpty' => 'Diese Flotte enthält Schiffe und kann nicht gelöscht werden.',
            'holderOwner' => 'Sie sind nicht der Besitzer der Flotten / Werften, oder die Flotten/Werften befinden sich nicht im gleichen System.',
            'shipsUnique' => 'Einer oder mehrere der Schiffe sind doppelt vorhanden.',
            'shipsOwner' => 'Sie sind nicht der Besitzer von einem oder mehreren der Schiffe.',
            'shipHolders' => 'Nicht alle Schiffe gehören zur Flotte oder Werft.',
            'findDestinationReqMissing' => 'Bitte füllen Sie alle Felder aus.',
            'coordsInvalid' => 'Die Koordinaten sind für dieses Spiel nicht gültig.',
            'moveSourceInvalid' => 'Der Standort der Flotte ist nicht gültig.',
            'moveDestinationInvalid' => 'Das Zielsystem existiert nicht.',
            'fleetAlreadyMoving' => 'Die Flotte ist bereits unterwegs.',
            'coordsStarNotFound' => 'Bei diesen Koordinaten gibt es kein Sternensystem.',
            'startEqualsEnd' => 'Die Flotte befindet sich bereits in diesem System.',
            'tickerInvalid' => 'Der Ticker ist nicht gültig.',
            'tickerNotFound' => 'Der Ticker existiert nicht.',
            'empireHasNoStars' => 'Das Imperium hat keine Sternensysteme.'
        ],
        'fleetCreated' => 'Flotte \':name\' ausgehoben.',
        'fleetNameChanged' => 'Flotte umbenannt.',
        'shipNameChanged' => 'Schiff umbenannt.',
        'deleted' => 'Flotte gelöscht.',
        'shipsTransfered' => 'Schiffe wurden transferiert.',
        'fleetMoving' => 'Die Flotte ist jetzt unterwegs und wird in :turns Runden ankommen.'
    ],

    'research' => [
        'navTitle' => 'Forschung',
        'errors' => [
            'priority' => [
                'invalid' => 'Forschungs Priorität ist ungültig.'
            ],
            'enqueue' => [
                'area' => 'Diese Technologie existiert nicht.',
                'alreadyResearched' => 'Diese Stufe ist bereits erforscht oder in der Warteschleife.',
                'bounds' => 'Diese Stufe ist nicht gültig.',
                'max' => 'Sie haben bereits die maximale Zahl an Forschungen in der Warteschleife.',
            ],
            'order' => [
                'owned' => 'Einer oder mehrere Forschungsaufträge gehört nicht Ihnen.',
                'order' => 'Die Reihenfolge in der Warteschleife ist ungültig. Sie müssen niedrigere Stufen zuerst erforschen.',
                'max' => 'Sie können nicht mehr als :num Forschungsaufträge in der Warteschleife haben.',
            ],
            'delete' => [
                'owned' => 'Dieser Forschungsauftrag gehört nicht Ihnen.',
                'order' => 'Sie haben einen Forschungsauftrag mit höherer Stufe in der Warteschleife.'
            ]
        ],
        'researchJobDeleted' => 'Forschungsauftrag gelöscht.',
        'jobQueued' => 'Forschungsauftrag erstellt.'
    ],

    'starchart' => [
        'navTitle' => 'Sternenkarte'
    ],

    'diplomacy' => [
        'navTitle' => 'Diplomatie',
        'errors' => [
            'recipientInvalid' => 'Das Imperium zu dem Sie die Diplomatische Beziehung setzen wollen ist nicht gültig.',
            'statusInvalid' => 'Der angegebene Status ist nicht gültig.',
            'relationChangePending' => 'Es existiert bereits eine laufende Änderung der diplomatischen Beziehung.'
        ],
        'relationChangeAccepted' => 'Die Beziehung zu diesem Imperium wird sich in :num Runden ändern.'
    ],

    'messages' => [
        'navTitle' => 'Nachrichten',
        'errors' => [
            'uuids' => 'Etwas stimmt nicht nicht den angegeben IDs.',
            'recipients' => 'Sie müssen zwischen :min und :max Empfänger wählen.',
            'players' => 'Einer oder mehrere der Empfänger scheinen nicht im Spiel eingeschrieben zu sein.',
            'subject' => 'Der Betreff muss zwischen :min und :max Zeichen enthalten.',
            'message' => 'Die Nachricht muss zwischen :min und :max Zeichen enthalten.',
            'messageOwner' => 'Sie sind nicht der Besitzer der Nachricht.',
            'bool' => 'Nachrichtenstatus muss boolescher operator sein. Hu?',
            'replyToId' => 'Sie können auf diese Nachricht nicht antworten.'
        ],
        'messageSent' => 'Nachricht an einen Empfänger verschickt.|Nachricht an :num Empfänger verschickt.',
        'sys' => [
            'greeting' => 'Furchtloser Anführer',
            'fleets' => [
                'arrival' => [
                    'subject' => 'Flotte hat Ziel erreicht',
                    'body' => 'Die Flotte \':name\' berichtet das Sie das Ziel :location erreicht hat.'
                ]
            ],
            'shipyards' => [
                'contractFinished' => [
                    'subject' => 'Bauauftrag abgeschlossen',
                    'body' => 'Die :type Werft auf Planet :name berichtet das der Bauauftrag für :construction Schiffe abgeschlossen wurde.
Die Werft steht für neue Bauaufträge jetzt wieder zur Verfügung.'
                ],
                'costsNotPaid' => [
                    'subject' => 'Fehlende Resourcen für Schiffsbau',
                    'body' => 'Die :type Werft auf Planet :name berichtet das ein Schiff der Klasse :shipclass nicht fertig gestellt werden konnte da uns die nötigen Resourcen und/oder Bevölkerung fehlen. Der Bauauftrag wird fortgeführt sobald die nötigen Resourcen zur Verfügung stehen.
Fehlende Kosten:
:missing
'
                ]
            ]
        ]
    ]

];
