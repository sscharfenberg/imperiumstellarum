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
            'gameNotFound' => 'This game does not exist.',
            'noPlayer' => 'Your are not enlisted in this game.',
            'header' => [
                'alreadyBuilding' => 'There is already a storage upgrade of this type in construction.',
                'level' => 'This storage level can not be constructed.'
            ],
            'deletion' => 'Could not delete, database error.'
        ],
        'storageUpgradeOrdered' => 'You have begun construction of a storage upgrade. It will be ready in :num turns.',
        'hulls' => [
            'ark' => 'Ark',
            'small' => 'Destroyer',
            'medium' => 'Cruiser',
            'large' => 'Battleship',
            'xlarge' => 'Capital ship'
        ],
        'resourceTypes' => [
            'energy' => 'Energy',
            'minerals' => 'Minerals',
            'food' => 'Food',
            'research' => 'Research',
        ]
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
                'owner' => 'You do not own this blueprint.',
                'usedForContract' => 'This blueprint is being used for a construction contract, deletion failed.'
            ],
            'constructionContract' => [
                'shipyardOwner' => 'You do not own this shipyard.',
                'amount' => 'The amount of ships is invalid.',
                'shipyard' => 'This shipyard can not build this blueprint.',
                'shipyardConstructing' => 'This shipyard already has an active construction contract.',
                'funds' => 'You do not have sufficient resources to start building the first ship.',
                'population' => 'The shipyards planet does not have sufficient population.',
                'owner' => 'You do not own this construction contract.'
            ]
        ],
        'blueprintSaved' => 'Blueprint finalzed.',
        'blueprintDeleted' => 'Blueprint deleted.',
        'blueprintRenamed' => 'Blueprint renamed.',
        'constructionContractInstalled' => 'Construction contract created. The first ship will be ready in :turns turns.',
        'constructionContractDeleted' => 'Construction contract deleted.'
    ],

    'fleets' => [
        'navTitle' => 'Fleets',
        'errors' => [
            'owner' => 'You do not own this fleet.',
            'starOwner' => 'You do not own this star.',
            'shipOwner' => 'You do not own this ship.',
            'name' => 'The name of this fleet is not valid.',
            'shipName' => 'The name of this ship is not valid.',
            'nameUnique' => 'The fleet name needs to be unique.',
            'notEmpty' => 'This fleet contains ships and can not be deleted.',
            'holderOwner' => 'Not all fleets or shipyards are owned by you, or they do not share the same system.',
            'shipsUnique' => 'Some of the ships supplied are not unique.',
            'shipsOwner' => 'You do not own one or more of the ships.',
            'shipHolders' => 'Not all ships belong to the fleet or shipyard.',
            'findDestinationReqMissing' => 'Please fill in all the fields.',
            'coordsInvalid' => 'The supplied coordinates are not valid',
            'moveSourceInvalid' => 'The fleets current location is not valid.',
            'moveDestinationInvalid' => 'The target system does not exist.',
            'fleetAlreadyMoving' => 'The fleet is already moving.',
            'coordsStarNotFound' => 'There is no star at this location.',
            'startEqualsEnd' => 'The fleet is already at that location.',
            'tickerInvalid' => 'The ticker is not valid.',
            'tickerNotFound' => 'This ticker does not exist.',
            'empireHasNoStars' => 'The empire does not have any stars'
        ],
        'fleetCreated' => 'Fleet \':name\' created.',
        'fleetNameChanged' => 'Renamed fleet.',
        'shipNameChanged' => 'Renamed ship.',
        'deleted' => 'Fleet deleted.',
        'shipsTransfered' => 'Ships where transfered.',
        'fleetMoving' => 'The fleet is now moving to destination and will arrive in :turns turns.'
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
        ],
        'researchJobDeleted' => 'Research job deleted.',
        'jobQueued' => 'Research job queued.'
    ],

    'starchart' => [
        'navTitle' => 'Starmap'
    ],

    'diplomacy' => [
        'navTitle' => 'Diplomacy',
        'errors' => [
            'recipientInvalid' => 'The empire that you want to set the relation to is not valid.',
            'statusInvalid' => 'The supplied status is not valid.',
            'relationChangePending' => 'A change of diplomatic relations already exists.'
        ],
        'relationChangeAccepted' => 'The relation to this empire will change in :num turns.',
        'relations' => [
            '0' => 'Hostile',
            '1' => 'Neutral',
            '2' => 'Allied',
        ]
    ],

    'messages' => [
        'navTitle' => 'Messages',
        'errors' => [
            'uuids' => 'There is something wrong with the supplied recipient Ids.',
            'recipients' => 'You need to select between :min and :max recipients.',
            'players' => 'One or more recipients seems to not be enlisted this game.',
            'subject' => 'The subject needs to have between :min and :max characters',
            'message' => 'The message needs to have between :min and :max characters',
            'messageOwner' => 'This message does not belong to you.',
            'bool' => 'Message status must be boolean.',
            'replyToId' => 'You can\'t reply to this message.',
            'comment' => 'The comment needs to have between :min and :max characters',
            'alreadyReported' => 'You have already reported this message.'
        ],
        'messageSent' => 'Message sent to one recipient.|Message sent to :num recipients.',
        'messageDeleted' => 'Message deleted.|:num messages deleted.',
        'reportRecieved' => 'Report submitted. A member of our staff will look into this.',
        'sys' => [
            'storageUpgrade' => [
                'finished' => [
                    'subject' => 'Storage upgrade completed',
                    'body' => 'The Storage upgrade for :type has been completed and is now level :level.
New storage capacity: :capacity'
                ]
            ],
            'fleets' => [
                'arrival' => [
                    'subject' => 'Fleet has arrived at location',
                    'body' => 'The fleet :name reports that it has arrived at location :location.'
                ],
                'enroute' => [
                    'subject' => 'Fleet en route to our star',
                    'body' => 'Long range sensors report that a fleet has begun a hyper jump to our star :name. It is unknown when the fleet will arrive or which empire has sent it.'
                ]
            ],
            'shipyards' => [
                'contractFinished' => [
                    'subject' => 'Construction contract finished',
                    'body' => 'The :type shipyard on planet :name reports that the construction contract for :construction ships has been completed.
The shipyard is now available for further constructions.'
                ],
                'costsNotPaid' => [
                    'subject' => 'Insufficient resources for construction contract',
                    'body' => 'The :type shipyard on planet :name reports that the construction of the next :shipclass class ship could not be started since we do not have sufficient resources and/or population. The construction contract will be continued once we have sufficient resources.
Resources missing:
:missing
Minerals and Energy are always invested together into a new ship.'
                ],
                'shipyardCompleted' => [
                    'subject' => 'Shipyard completed',
                    'body' => 'The shipyard on planet :name reports that the shipyard construction is now completed. The largest ship that can be built in the shipyards is now a :type.
The shipyard is now available for ship construction.'
                ]
            ],
            'diplomacy' => [
                'relationChangeInitiator' => [
                    'subject' => 'Diplomatic relations changed',
                    'body' => 'We have changed the diplomatic relations to empire [:ticker] to :status (:statusString).
The effective relations to [:ticker] is now :effective (:effectiveString).'
                ],
                'relationChangeRecipient' => [
                    'subject' => 'Diplomatic relations changed',
                    'body' => 'Empire [:ticker] has changed their diplomatic relations to us to :status (:statusString).
The effective relations to [:ticker] is now :effective (:effectiveString).'
                ],
            ],
            'encounter' => [
                'subject' => 'Encounter involvement',
                'body' => 'Our empire was involved in an encounter - fleets have been fighting over :starName.
Please check the encounters page for details.'
            ]
        ]
    ],

    'encounters' => [
        'navTitle' => 'Encounters',
        'errors' => [
            'noEncounter' => 'This encounter does not exist or you are not involved.',
        ]
    ]

];
