<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'home' => [
        'navTitle' => 'Admin',
        'title' => 'Administration',
        'description' => 'Welcome to the administration area.',
        'users' => [
            'sectionTitle' => 'Users',
            'sectionSubtitle' => 'Manage user accounts.',
            'total' => 'Total Users',
            'unverified' => 'Users without verified email',
            'suspended' => 'Users with active suspensions'
        ],
        'games' => [
            'sectionTitle' => 'Games',
            'sectionSubtitle' => 'Manage games.',
            'active' => 'Active Games',
            'notstarted' => 'Games waiting for start',
            'canEnlist' => 'Enlistable games',
            'finished' => 'Finished Games'
        ],
        'reports' => [
            'sectionTitle' => 'Reports',
            'sectionSubtitle' => 'Manage and view reported messages.',
            'unresolved' => 'Unresolved reports',
            'resolved' => 'Resolved reports',
            'total' => 'Total reports',
        ]
    ],

    'users' => [
        'navTitle' => 'Users',
        'title' => 'Manage Users',
        'thead' => [
            'id' => 'ID',
            'email' => 'Email',
            'isSuspended' => 'Suspended',
            'email_verified_at' => 'Email verified',
            'locale' => 'Locale',
            'role' => 'Role',
            'game_mail_optin' => 'OptIn',
            'created_at' => 'Created'
        ],
        'pagination' => [
            'num' => 'showing :users of :total users',
            'sort' => 'sorted by ":sort" :dir'
        ],
        'perPage' => [
            'showing' => 'Showing',
            'results' => 'results per page.'
        ],
        'sort' => [
            'asc' => 'ascending',
            'desc' => 'descending'
        ],
        'filter' => [
            'sectionTitle' => 'Filter Settings',
            'sectionSubtitle' => 'Filter the user list below.',
            'idLabel' => 'Filter by UserId',
            'emailLabel' => 'Filter by Email',
            'selectEmptyOption' => 'Please select',
            'role' => 'Filter by role',
            'submit' => 'Apply Filters'
        ]
    ],

    'user' => [
        'navTitle' => 'User #:id',
        'title' => 'Manage Single User (ID: :id)',
        'data' => [
            'sectionTitle' => 'User data',
            'sectionSubtitle' => 'View or change the user\'s data.',
            'emailLabel' => 'User Email',
            'emailPlaceholder' => 'User Email',
            'verifiedAt' => 'Email verified at',
            'roleLabel' => 'User Role',
            'createdAt' => 'Created at',
            'updatedAt' => 'Updated at',
            'submitChanges' => 'Update user data',
            'success' => 'User was successfully updated',
            'noChange' => 'No changes detected.'
        ],
        'session' => [
            'sectionTitle' => 'User Sessions',
            'thisDevice' => 'This device',
            'lastActive' => 'Last active',
            'none' => 'No active sessions found.'
        ],
        'suspensions' => [
            'sectionTitle' => 'User Suspensions',
            'intro' => 'Red background means the suspension is currently active (\'Until\' is in the future).',
            'issued' => 'Issued',
            'until' => 'Until',
            'issuedBy' => 'Issued by (UserId)',
            'reason' => 'Reason',
            'delete' => 'Delete',
            'deleteModalTitle' => 'Delete Suspension',
            'deleteModalText' => 'Are you sure you want to delete this suspension? The user will be informed via Email.',
            'deleteModalCancel' => 'Cancel',
            'deleteModalSubmit' => 'Delete Suspension',
            'deleteSuspensionError' => 'Suspension or User not found.',
            'deleteSuspensionSuccess' => 'Suspension deleted.',
            'newTitle' => 'Suspend User',
            'newSubtitle' => 'Issue a new suspension for this user. The user will be logged out and will be unable to login for the period specified. The user will be informed via mail of the reason for this suspension.',
            'durationLabel' => 'Suspension Duration',
            'selectEmptyOption' => 'Please select',
            'duration1' => '24 hours',
            'duration3' => '3 days',
            'duration7' => '1 week',
            'duration14' => '2 weeks',
            'duration30' => '30 days',
            'duration99' => 'lifetime',
            'reasonLabel' => 'Reason',
            'reasonPlaceholder' => 'You must enter a reason for the suspension.',
            'submit' => 'Create Suspension',
            'success' => 'User was suspended'
        ],
        'suspensionNotification' => [
            'subject' => 'Account Suspended',
            'introduction' => 'We are sorry to inform you that your account for Imperium Stellarum has been suspended for :days days, effective immediately.',
            'introductionForever' => 'We are sorry to inform you that your account for Imperium Stellarum has been suspended forever, effective immediately.',
            'reason' => 'The reason for the suspension is: \':reason\'.',
            'appeal' => 'If you think this suspension happened in error, you are free to appeal this ban on our forums - please contact an admin. Please make sure to check our terms of use before doing so.',
            'action' => 'Imperium Stellarum Staff',
            'until' => 'The suspension will last until :until. Until this date, you will not be able to login into the game. The discussion forums are not affected by this suspension.',
        ],
        'supensionLiftedNotification' => [
            'subject' => 'Suspension Lifted',
            'introduction' => 'The suspension until :until on your account has been lifted.',
            'reason' => 'The original reason for the suspension was \':reason\'',
            'result' => 'You are now free to login into the game again. Please note that the record of the suspension on your account was deleted.'
        ]
    ],

    'games' => [
        'navTitle' => 'Games',
        'title' => 'Manage Games',
        'thead' => [
            'number' => 'Number',
            'active' => 'Active?',
            'processing' => 'Processing?',
            'can_enlist' => 'Can Enlist?',
            'players' => 'Players',
            'turn' => 'Turn',
            'created_at' => 'Created',
            'start_date' => 'Start',
            'turn_due' => 'Turn Due',
            'seeded' => 'Seeded?'
        ],
        'tbody' => [
            'notStarted' => 'N/A'
        ],
        'pagination' => [
            'num' => 'showing :games of :total games',
            'sort' => 'sorted by ":sort" :dir'
        ],
        'perPage' => [
            'showing' => 'Showing',
            'results' => 'results per page.'
        ],
        'sort' => [
            'asc' => 'ascending',
            'desc' => 'descending'
        ],
    ],

    'game' => [
        'notfound' => 'This game does not exist.',
        'create' => [
            'navTitle' => 'New Game',
            'title' => 'Create a new game',
            'headline' => 'Define Game Meta Data',
            'dimensionsLabel' => 'Map dimensions',
            'dimensionsPlaceholder' => 'specify dimensions',
            'dimensionsDescription' => 'The specified dimensions are for x and y axis. Bigger maps allow more players.',
            'startDateLabel' => 'Start Date',
            'startDatePlaceholder' => 'Choose the start date/time',
            'startDateDescription' => 'The Date and Time that you enter uses your timezone settings. We convert the start date to servertime (UTC). The start date can\'t be in the past.',
            'turnDurationLabel' => 'Turn Duration (in minutes)',
            'turnDurationPlaceholder' => 'enter the turn duration in minutes',
            'submit' => 'Create Game',
            'success' => 'Game successfully created. Please note that it needs to be seeded with stars and planets before it can start.'
        ],
        'edit' => [
            'navTitle' => 'Game g:game',
            'title' => 'Game g:game',
            'headline' => 'Meta data of g:game',
            'intro' => 'All dates (created, updated, turn due and start date) are in servertime (UTC). They will not be automatically converted to your time. Unckecking \'processing\' does not abort turn calculation - this should only be used if the turn calculation is finished, but \'processing\' wasn\'t set to false.',
            'createdLabel' => 'Created at',
            'updatedLabel' => 'Updated at',
            'startDateLabel' => 'Start Date',
            'seededLabel' => 'Seeded?',
            'canEnlistLabel' => 'Can players enlist?',
            'canEnlistDescription' => 'Games set to \'Can Enlist\' appear in the dashboard of players and are joinable.',
            'activeLabel' => 'Game is active?',
            'activeDescription' => 'Games set to \'Active\' will be checked every minute if a turn needs to be processed. Games that are not yet started must not be set to \'active\'',
            'processingLabel' => 'Game is processing?',
            'processingDescription' => 'Games set to \'Processing\' are currently calculated by the server. They are \'locked\' for players - no new orders can be placed while the game is processing.',
            'turnDueLabel' => 'Turn Due',
            'currentTurnLabel' => 'Current Turn',
            'gameNotStarted' => 'Game has not started yet.',
            'playersLabel' => 'Players',
            'submit' => 'Save Changes',
            'seed' => 'Create Preview Map',
            'success' => 'Game successfully updated.'
        ],
        'seed' => [
            'navTitle' => 'g:game Map',
            'title' => 'Generate map for g:game',
            'headline' => 'Generate Map Preview',
            'intro' => 'Generate a map preview. Once you are satisfied with the resulting map, save the results and let the server create stars and planets.',
            'dimensionsLabel' => 'Map Dimensions',
            'dimensionsPlaceholder' => 'specify dimensions',
            'npcDistanceLabel' => 'Distance between NPC Stars',
            'npcDistanceDescription' => 'Select minimum and maximum distance of NPC star systems. The map is filled with stars while enforcing the min/max distance. With this setting you can influence the total number of systems in the map.',
            'playerDistanceLabel' => 'Distance between Player Stars',
            'playerDistanceDescription' => 'Select minimum and maximum distance of player stars. This determines the maximum number of players. Shorter distance between players means a more aggressive early game.',
            'preview' => 'Generate Preview',
            'totals' => 'Totals',
            'npcStars' => 'NPC stars',
            'playerStars' => 'Player stars',
            'mapTitle' => 'Generated Map',
            'submit' => 'Save &amp; Seed Game',
            'success' => 'Game was successfully seed with stars and planets.'
        ],
        'players' => [
            'button' => 'View players',
            'navTitle' => 'g:game Players',
            'title' => 'Players enlisted to g:game',
            'thead' => [
                'id' => 'UserId',
                'email' => 'User Email',
                'isSuspended' => 'User Suspended',
                'locale' => 'User Locale',
                'created_at' => 'Player Joined',
                'ticker' => 'Ticker',
                'name' => 'Empire Name',
            ],
            'pagination' => [
                'num' => 'showing :players of :total players',
                'sort' => 'sorted by ":sort" :dir'
            ],
        ]
    ],

    'reports' => [
        'navTitle' => 'Reports',
        'title' => 'Manage reports',
        'thead' => [
            'created_at' => 'Created',
            'game' => 'Game',
            'reporter' => 'Reporting Player',
            'reportee' => 'Reported Player',
            'comment' => 'Comment',
            'resolved' => 'Resolved',
        ],
        'pagination' => [
            'num' => 'showing :reports of :total reports',
            'sort' => 'sorted by ":sort" :dir'
        ],
        'perPage' => [
            'showing' => 'Showing',
            'results' => 'results per page.'
        ],
        'sort' => [
            'asc' => 'ascending',
            'desc' => 'descending'
        ],
    ],

    'report' => [
        'navTitle' => 'Reported Message',
        'title' => 'Reported Message from [:ticker]',
        'notfound' => 'This report does not exist.',
        'data' => [
            'headline' => 'Report Data',
            'game' => 'Game',
            'gameDetails' => 'Details for Game g:game',
            'reporter' => 'Reporting by (victim)',
            'reporterComment' => 'Comment',
            'reportee' => 'Reported Player (offender)',
            'reporteeUser' => 'User of reported player',
            'reporteeUserDetails' => 'User Details',
            'reportedAt' => 'Reported at',
            'resolved' => 'Report resolved',
            'resolvedBy' => 'Resolved von'
        ],
        'message' => [
            'headline' => 'Details for reported message',
            'sentAt' => 'Sent at',
            'recipients' => 'Recipients',
            'subject' => 'Subject',
            'body' => 'Message body'
        ],
        'resolve' => [
            'headline' => 'Resolve report',
            'explanation' => 'Please analyze the message carefully whether there are violations of our terms of use. You can suspend the user or issue warnings - please enter a message to the reporter and the reportee. This message appears in the \'system notifications\'.',
            'suspension' => 'Suspend offender [:ticker] (optional!)',
            'suspensionExplanation' => 'Only suspend [:ticker] (the offender) if there is a clear violation of our terms of use.',
            'reporteeMsgLabel' => 'Message to [:ticker] (offender) (optional)',
            'reporteeMsgExplanation' => 'This is optional and sent as a system notification to [:ticker] (offender). If you suspend the user, it is also sent to the user\'s email.',
            'reporterMsgLabel' => 'Message to [:ticker] (victim)',
            'reporterMsgExplanation' => 'This is sent to [:ticker] (victim) as a system notification.',
            'msgLength' => 'At least :min characters, max :max characters.',
            'submit' => 'Resolve Report',
            'success' => 'You have resolved the report. Thanks!'
        ],
        'reporteeMsg' => [
            'subject' => 'A message by you was reported.',
        ],
        'reporterMsg' => [
            'subject' => 'Your report has been resolved.'
        ]
    ]

];
