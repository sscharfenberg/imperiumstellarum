<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 'App' Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in the main app - everything that
    | is not the game itself or the admin pages.
    |
    */

    'header' => [
        'drawerButton' => 'Main menu'
    ],

    'drawer' => [
        'localTime' => 'Local time',
        'currentTurn' => 'Current turn',
        'nextTurn' => 'Next turn',
        'forums' => 'Forums',
        'github' => 'Github',
        'discord' => 'Discord'
    ],

    'footer' => [
        'handcrafted' => 'handcrafted with',
        'name' => 'by Sven Scharfenberg'
    ],

    'notification' => [
        'warning' => 'Notice',
        'success' => 'Success',
        'error' => 'Error'
    ],

    'user' => [
        'suspended' => 'Your account is suspended until :until. Please check your mailbox.'
    ],

    // PAGES

    'home' => [
        'navTitle' => 'Home',
        'title' => 'Welcome to Imperium Stellarum',
        'description' => 'a turn-based multiplayer browser game of galactic conquest',
        'hero' => [
            'left' => 'Your',
            'middle' => 'empire',
            'right' => 'awaits',
        ],
        'features' => [
            '4x' => [
                'p1' => 'eXplore the galaxy. eXpand your empire. eXploit your neighbours. eXterminate your enemies.',
                'p2' => 'Inspired by classic games like',
                'or' => 'or'
            ],
            'oss' => [
                'p1' => 'Imperium Stellarum is free and open source software.',
                'p2' => 'The sourcecode is availble on github with a permissible license. If you want, read the sourcecode, fork it and use it - or parts of it - for your own game.',
            ],
            'free' => [
                'p1' => 'Imperium Stellarum is free to play.',
                'p2' => 'No ads. No micro-transactions. No loot boxes. No shop. No user-tracking. No way to spend money on the game.',
                'p3' => 'It will cost you time to play, though 🤔.',
            ],
            'map' => [
                'p1' => 'A new map is generated for each game. Map dimensions can be varied for smaller or bigger games.',
                'p2' => 'Stars and planets are randomly generated.',
                'p3' => 'No two games are the same.'
            ],
            'blueprints' => [
                'p1' => 'Customize your ship blueprints by deciding which modules to install.',
                'p2' => 'Fast ships with lots of weapons and no defense? Slow ships with long range weapons and heavy armour or shields? A mixture of both? You decide.'
            ],
            'privacy' => [
                'p1' => 'The only personal information that is needed to sign up is an e-mail address - which is never shown to other players.',
                'p2' => 'We encourage you to use a throwaway email.'
            ],
            'turn' => [
                'p1' => 'Imperium Stellarum is turn-based.',
                'p2' => 'The default setting is a turn is calculated every 15 minutes, though longer or shorter turns are possible.',
                'p3' => 'The game runs 24/7.'
            ],
            'diplomacy' => [
                'p1' => 'Diplomacy wins games - so, make friends. And enemies.',
                'p2' => 'Our community guidelines explicitly allow betrayal, lying, extortion or threats.',
                'p3' => 'Just remember, too many enemies will make it hard to win the game.'
            ]
        ],
        'stats' => [
            'active' => [
                'headline' => 'Active games',
                'game' => 'Game g:game',
                'turn' => 'Current turn is t:turn',
                'dimensions' => 'Map size is :dimensions x :dimensions',
                'players' => ':players enlisted players',
                'none' => 'There are currently no active games'
            ],
            'upcoming' => [
                'headline' => 'Upcoming games',
                'game' => 'Game g:game',
                'start' => 'Game starts at :start',
                'players' => ':players/:max enlisted players',
                'none' => 'No upcoming games yet.'
            ],
            'finished' => [
                'headline' => 'Last finished games',
                'game' => 'Game g:game',
                'end' => 'Game ended at :end',
                'dimensions' => 'Map size was :dimensions x :dimensions',
                'players' => ':players participating players',
                'winner' => 'Winner',
                'none' => 'No games have been finished yet.'
            ]
        ]
    ],

    'register' => [
        'navTitle' => 'Register',
        'title' => 'Account Registration',
        'description' => 'create an account.',
        'headline' => 'Glad to see you sign up!',
        'emailLabel' => 'Email',
        'emailPlaceholder' => 'Enter your email address',
        'emailDescription' => 'Your Email is used to log in, and to send account activation / password reset links. Your email is the only personal information about you that we store in our database. It is never shown to other users.',
        'passwordLabel' => 'Password',
        'passwordPlaceholder' => 'Choose your password',
        'passwordDescription' => 'Please use a safe password that is not used anywhere else. We calculate the password strength - the lock icon needs to be green for the password to be accepted.',
        'repeatPasswordLabel' => 'Repeat password',
        'repeatPasswordPlaceholder' => 'Retype your password',
        'repeatPasswordDescription' => 'Please retype your password to prevent typos.',
        'confirmedLabel' => 'I accept the privacy policy and the terms of use.',
        'errors' =>  [
            'email' =>  [
                'invalid' => 'This does not seem to be a valid email address.',
                'required' => 'The email address is required.',
                'unique' => 'This email has already been registered.'
             ],
            'password' =>  [
                'required' => 'The password is required.',
                'confirmed' => 'The repeated password below does not match the password field.',
                'length' => 'The password needs to have a minimum of 6 characters.',
                'strength' => 'Your password is not strong enough. Please use a stronger password by adding numbers, letters etc.'
             ],
            'confirmed' =>  [
                'accepted' => 'Please accept our privacy policy and the terms of use.'
             ]
         ],
        'success' =>  [
            'headline' => 'Your registration was successful!',
            'text' => 'You have successfully registered an account. We have sent an email to you with an account activation link. Please click on the link to activate your account.'
         ],
        'submit' => 'Submit registration'
    ],

    'verify' => [
        'navTitle' => 'Verify Email',
        'title' => 'Verify Email address',
        'text' => 'Your email is not yet verified. Please check your mailbox and click on the \'Verify Email Adress\' link.',
        'sent' => 'Verification link sent.',
        'resend' => 'Resend Email',
        'success' => 'Your email is now verified.'
    ],

    'login' => [
        'navTitle' => 'Login',
        'title' => 'Account Login',
        'description' => 'welcome back.',
        'headline' => 'Your empire is waiting.',
        'emailLabel' => 'Email',
        'emailPlaceholder' => 'Enter your email address',
        'passwordLabel' => 'Password',
        'passwordPlaceholder' => 'Enter your password',
        'rememberLabel' => 'Remember me',
        'submit' => 'Login',
    ],

    'forgot' => [
        'navTitle' => 'Forgot Password',
        'title' => 'Forgot your password?',
        'headline' => 'Request password reset',
        'description' => 'Request a password reset by providing your email address. We will send you a link to reset your password.',
        'submit' => 'Request password reset'
    ],

    'reset' => [
        'navTitle' => 'Reset Password',
        'title' => 'Reset Password',
        'headline' => 'Set your new password',
        'emailDescription' => 'Please provide your email address as an additional security precaution.',
        'submit' => 'Change password'
    ],

    'logout' => [
        'navTitle' => 'Logout'
    ],

    'dashboard' => [
        'navTitle' => 'Dashboard',
        'title' => 'Dashboard',
        'description' => 'Change your account information or join games.',
        'email' => [
            'sectionTitle' => 'Email',
            'sectionSubtitle' => 'Update your email address.',
            'emailLabel' => 'Email',
            'emailPlaceholder' => 'Enter your new email address',
            'description' => 'Please note that after changing your email address, you will be logged out and have to verify your email again.',
            'submit' => 'Change Email',
            'success' => 'Your email was changed. We have sent you a new verification link - please check your inbox.',
        ],
        'password' => [
            'sectionTitle' => 'Password',
            'sectionSubtitle' => 'Ensure your account is using a long, random password to stay secure.',
            'currentPasswordLabel' => 'Current Password',
            'currentPasswordPlaceholder' => 'Enter your current password',
            'newPasswordLabel' => 'New Password',
            'newPasswordPlaceholder' => 'Enter the new password',
            'repeatPasswordLabel' => 'Confirm Password',
            'repeatPasswordPlaceholder' => 'Repeat the new password',
            'submit' => 'Change Password',
            'success' => 'Your password was changed. Please update your password manager / browser settings.'
        ],
        'theme' => [
            'sectionTitle' => 'Theme',
            'sectionSubtitle' => 'Choose between dark and light theme',
            'label' => 'Choose your theme',
            'dark' => 'Dark theme',
            'light' => 'Light theme',
            'browser' => 'Browser / OS decides',
            'description' => 'Your theme settings are saved in your browser.'
        ],
        'notifications' => [
            'sectionTitle' => 'Notifications',
            'sectionSubtitle' => 'Opt in to game-related mail notifications.',
            'label' => 'Yes, send me mails with game-related notifications',
            'description' => 'If the checkbox is checked, you will recieve game-related mail notifications from us. Account-related Emails (Email verifications, password resets, suspensions etc) are not affected by this setting.',
            'submit' => 'Update Settings',
            'success' => 'Your notification settings have been updated.'
        ],
        'delete' => [
            'sectionTitle' => 'Delete Account',
            'sectionSubtitle' => 'Permanently delete your account.',
            'description' => 'Once your account is deleted, all of its resources and data will be permanently lost. You will lose access to any active games that you have joined. This action can not be undone.',
            'submit' => 'Delete Account',
            'warning' => 'Once your account is deleted, all of its resources and data will be permanently lost. Are you sure you want to delete the account?',
            'confirmSubmit' => 'Delete Account',
            'cancel' => 'Cancel',
            'success' => 'Your account has been deleted.'
        ],
        'session' => [
            'sectionTitle' => 'Sessions',
            'sectionSubtitle' => 'This shows your active sessions - devices that are currently logged in to this account.',
            'thisDevice' => 'This device',
            'lastActive' => 'Last active',
            'none' => 'No active sessions found.'
        ],
        'availableGames' => [
            'sectionTitle' => 'Available Games',
            'sectionSubtitle' => 'This shows games that you can join.',
            'players' => 'Players',
            'stars' => 'Stars',
            'start' => 'Starts',
            'enlist' => 'Join Game'
        ],
        'players' => [
            'sectionTitle' => 'My Games',
            'sectionSubtitle' => 'This shows games that you have joined.',
            'started' => 'Game started',
            'quit' => 'Quit Game',
            'quitTitle' => 'Quit Game g:game',
            'quitWarning' => 'Are you sure you want to quit the game? You might not be able to claim your slot again. If the game is already running, your empire will be deleted.',
            'quitSubmit' => 'Quit g:game'
        ]
    ],

    'enlist' => [
        'navTitle' => 'Join g:game',
        'title' => 'Join Game g:game',
        'headline' => 'Thanks For Joining!',
        'game' => 'Game',
        'turnDuration' => 'Turn Duration',
        'minutes' => 'minutes',
        'start' => 'Start Date',
        'players' => 'Current Players',
        'stars' => 'Stars',
        'dimensions' => 'Map dimensions',
        'nameLabel' => 'Empire Name',
        'namePlaceholder' => 'Please enter your empire name',
        'nameDescription' => 'Your empire name will be shown to other players and used in communications. Please use :min to :max characters.',
        'tickerLabel' => 'Empire Ticker',
        'tickerPlaceholder' => 'Please enter your ticker',
        'tickerDescription' => 'Your ticker is the short form of your empire name (:min - :max characters). It is used on the map, for shipnames and communication.',
        'colourLabel' => 'Empire Colour',
        'colourPlaceholder' => 'Please choose a colour',
        'colourDescription' => 'Your chosen colour will be used on the star map to mark stars you own.',
        'submit' => 'Join Game',
        'notEnlistable' => 'This game does not allow joining.',
        'started' => 'This game has already started.',
        'full' => 'This game is already full.',
        'alreadyEnlisted' => 'You have already joined.',
        'success' => 'You have successfully join game g:game'
    ],

    'quit' => [
        'notFound' => 'Could not find the specified player.',
        'success' => 'You have successfully quit game g:game.'
    ],

    'privacy' => [
        'navTitle' => 'Privacy policy',
        'title' => 'Our privacy policy',
        'description' => 'This privacy policy will explain how this website uses the personal data we collect from you when you use our website.',
        'whatData' => [
            'title' => 'What data do we collect?',
            'paragraph1' => 'We make sure that we collect as few personal data as possible. We only store your email address in our database for login purposes. No other personal data is collected.'
        ],
        'howData' => [
            'title' => 'How do we collect your data?',
            'paragraph1' => 'You directly provide this website with most of the data we collect. We collect and process data when you register a user account on this website.',
        ],
        'dataUse' => [
            'title' => 'How will we use your data?',
            'paragraph1' => 'We collect your Email address so that we can use it as a unique identifier for your account.',
            'paragraph2' => 'We will send you email notifications when your Email address needs to be verified or when you have forgotten your password. We will also send you game-related Emails when you have activated this feature in your dashboard.',
            'paragraph3' => 'We will not share your data with anyone unless forced to do so by german law enforcement authorities.'
        ],
        'dataStorage' => [
            'title' => 'How do we store your data?',
            'paragraph1' => 'We store your data in our database. The database is hosted on the same server that this website uses. It is not accessible from the internet, only from the website.'
        ],
        'dataProtection' => [
            'title' => 'What are your data protection rights?',
            'paragraph1' => 'We would like to make sure you are fully aware of all of your data protection rights. Every user is entitled to the following:',
            'bullet1' => [
                'title' => 'The right to access',
                'point' => 'You have the right to request copies of your personal data. We may charge you a small fee for this service.'
            ],
            'bullet2' => [
                'title' => 'The right to rectification',
                'point' => 'You have the right to request that we correct any information you believe is inaccurate. You also have the right to request us to complete the information you believe is incomplete.'
            ],
            'bullet3' => [
                'title' => 'The right to erasure',
                'point' => 'You have the right to request that we erase your personal data, under certain conditions. You can delete your account from your dashboard, or you can ask us to do this for you.'
            ],
            'bullet4' => [
                'title' => 'The right to restrict processing',
                'point' => 'You have the right to request that we restrict the processing of your personal data, under certain conditions. Please not that we don\'t process your data.',
            ],
            'bullet5' => [
                'title' => 'The right to object to processing',
                'point' => 'You have the right to object to our processing of your personal data, under certain conditions. Please not that we don\'t process your data.'
            ],
            'bullet6' => [
                'title' => 'The right to data portability',
                'point' => 'You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.'
            ],
            'paragraph2' => 'If you make a request, we have one month to respond to you. If you would like to exercise any of these rights, please contact us at our email:'
        ],
        'cookies'=> [
            'title' => 'Cookies',
            'paragraph1' => 'Cookies are text files placed on your computer to collect standard information and/or visitor behavior information. When you visit our websites, we may collect information from you automatically through cookies or similar technology.',
            'paragraph2' => 'For further information, visit allaboutcookies.org.'
        ],
        'cookiesHow'=> [
            'title' => 'How do we use cookies?',
            'paragraph1' => 'We use cookies in a range of ways to improve your experience on our website, including:',
            'bullet1' => 'Keeping you signed in.',
            'bullet2' => 'Remembering your login information in a secure format, if you choose to be remembered upon login.',
            'bullet3' => 'Protecting you against cross-site request forgery.'
        ],
        'cookiesTypes' => [
            'title' => 'What types of cookies do we use?',
            'paragraph1' => 'There are a number of different types of cookies, however, this website uses exclusively functional cookies.',
            'paragraph2' => 'We use these cookies so that we recognize you on our website and remember your previously selected preferences / keeping you logged in. We use exclusively first-party cookies, no third-party cookies are used.'
        ],
        'cookiesManage' => [
            'title' => 'How to manage cookies',
            'paragraph1' => 'You can set your browser not to accept cookies, or remove the cookies from your browser. However, some of our website features may not function as a result.'
        ],
        'cookiesOther' => [
            'title' => 'Privacy policies of other websites',
            'paragraph1' => 'This website contains links to other websites. Our privacy policy applies only to our website, so if you click on a link to another website, you should read their privacy policy.'
        ],
        'cookiesChanges' => [
            'title' => 'Changes to our privacy policy',
            'paragraph1' => 'We keep this privacy policy under regular review and place any updates on this page. This privacy policy was last updated on 6 October 2020.'
        ],
        'contact' => [
            'title' => 'How to contact us',
            'paragraph1' => 'If you have any questions about this website’s privacy policy, the data we hold on you, or you would like to exercise one of your data protection rights, please do not hesitate to contact us.',
            'paragraph2' => 'Email us at '
        ],
    ],

    'imprint' => [
        'navTitle' => 'Legal Notice',
        'title' => 'Legal Notice',
        'paragraph1' => 'Responsible for contents according to german § 5 TMG',
        'paragraph2' => 'Represented by',
        'contact' => 'Contact',
        'disclaimer' => 'Disclaimer',
        'attribution' => 'Attribution',
        'paragraph3' => 'All images and icons used that where not created by the author can be found on the github attribution page.',
        'headline4' => 'Liability for contents',
        'paragraph4' => 'The contents of our pages were created with great care. However, we cannot accept any liability for the correctness, completeness and actuality of the content. As a service provider, we are responsible for our own content on these pages in accordance with general law in accordance with Section 7, Paragraph 1 of the German Telemedia Act. According to §§ 8 to 10 TMG, as a service provider, we are not obliged to monitor transmitted or stored third-party information or to investigate circumstances that indicate illegal activity. Obligations to remove or block the use of information in accordance with general laws remain unaffected. Liability in this regard is only possible from the point in time at which we become aware of a specific legal violation. We will remove this content immediately upon becoming aware of such legal violations.',
        'headline5' => 'Liability for Links',
        'paragraph5' => 'Our offer contains links to external third party websites, over whose content we have no influence. Therefore we cannot accept any liability for this external content. The respective provider or operator of the pages is always responsible for the content of the linked pages. The linked pages were checked for possible legal violations at the time they were linked. No illegal content was found at the time the link was created. Permanent monitoring of the content of the linked pages is not reasonable without concrete evidence of a violation of the law. If we become aware of legal violations, we will remove such links immediately.',
        'generatedby' => 'Website legal notice by impressum-generator.de'
    ],

    'terms' => [
        'navTitle' => 'Terms of use',
        'title' => 'Terms of use',
        'intro' => 'Please read these terms of use carefully before registering an account.',
        'acceptance' => [
            'title' => 'Acceptance of the terms of use',
            'paragraph1' => 'Your access to and use of the Imperium Stellarum website is depending on your acceptance and compliance of these terms of use. ',
            'paragraph2' => 'Furthermore, it is also depending on acceptance und compliance with the Privacy Policy of the Imperium Stellarum website. Our Privacy Policy describes our policies and procedures on the collection, use and disclosure of your personal information when you use the Imperium Stellarum website and tells you about your privacy rights and how the law protects you. Please read our Privacy Policy carefully before using our Service.'
        ],
        'termination' => [
            'title' => 'Termination or suspension of your account',
            'paragraph' => 'Failure to adhere to our terms of use can result in a warning via ingame notification or private message on our forums, or in a suspension of your account for a specific amount of time, or in a termination of your account, depending on the violation at discretion of the Imperium Stellarum administration team.'
        ],
        'warranty' => [
            'title' => 'Liability and warranty',
            'paragraph1' => 'The Imperium Stellarum contributers, as well as the Imperium Stellarum administration and hosting team, can not be held responsible for any faults or errors that exist in the website and/or the game. Errors or faults can result in the loss of your data or loss of privacy information. As descibed in our Privacy Policy, we take great care to only require the minimum amount of personal information - your email address.',
            'contributors' => 'Imperium Stellarum contributors'
        ],
        'communityGuidelines' => [
            'title' => 'Community guidelines',
            'paragraph1' => 'Imperium Stellarum is a game that should, above all, be fun and challenging to play for everyone. We require all registered users of the website to differentiate strictly between the empires that are played in the game and the humans that play the empires.',
            'paragraph2' => 'This means, in the game, pretty much anything goes. Feel free to betray, extort, lie or threaten other empires. Feel free to make two other empires go to war with each other, then take their systems. Feel free to have smaller empires do your bidding. Feel free to backstab larger empires when they have you do their bidding.',
            'paragraph3' => 'However, there are limits to \'anything goes\', specifically when sending messages to other players. Do not bring real life into the game, do not address the user playing the empire directly. While roleplaying an evil empire is fine, do not overdo it - in short, don\'t be a dick to other players when sending messages.',
            'paragraph4' => 'Moreover - Racism, misogyny, discriminating other users based on heritage, social standing, body shape or others will under no circumstances be tolerated and will result in a termination or suspension of your account.',
            'paragraph5' => 'Every player has the option to report offending incoming messages to the administration team. The admin team will investigate all reports and deal with them accordingly.'
        ],
        'exploits' => [
            'title' => 'Security issues and game exploits',
            'paragraph1' => 'We ask all users to report any possible exploits or security issues directly to the admin team to make the game safe and fair for all players.',
            'paragraph2' => 'If you encounter an exploit or security issue, please contact the staff via private message in the forums - do not post these publicly.',
            'staff' => 'Imperium Stellarum staff',
            'paragraph3' => 'Exploiting bugs or faults in the game will result in a punishment at the discretion of the administration team.',
            'paragraph4' => 'Exploiting security issues in the website or the game - or manipulating or influencing the Imperium Stellarum servers or services - will always result in a lifetime ban, and possibly alerting of german law enforcement authorities (depending on severity).'
        ],
        'multiBoxing' => [
            'title' => 'Multiple accounts',
            'paragraph1' => 'You are only allowed to have exactly one account. If you create a second account - be it to have ingame advantages, or to evade an account suspension - the additional accounts will be terminated, and we will issue an appropriate punishment to the original account.',
            'paragraph2' => 'We encourage all players to inform the administration team of suspected multi-accounting.',
            'paragraph3' => 'Evading a ban by using a different email address will result in a suspension, possibly a lifetime ban - depending on motivation, severity and context',
            'paragraph4' => 'If you have problems accessing your account, please try to resolve this issue by yourself (forgot password). If this doesn\'t help, the admin staff will be happy to help.',
        ],
    ],

    'halloffame' => [
        'navTitle' => 'Hall of Fame',
        'title' => 'Hall of Fame',
        'description' => 'All of the games that where finished and the empires that won them.',
        'pagination' => [
            'num' => 'Show :games of :total games',
            'sort' => 'Sorted by \':sort\' :dir'
        ],
        'perPage' => [
            'showing' => 'Show',
            'results' => 'results per page'
        ],
        'sort' => [
            'asc' => 'ascending',
            'desc' => 'descending'
        ],
        'thead' => [
            'number' => 'Game',
            'turns' => 'Turns',
            'end_date' => 'End date',
            'dimensions' => 'Dimensions',
            'players' => 'Players',
            'winner' => 'Winner'
        ],
        'none' => 'No games yet.'
    ],

    'halloffameDetails' => [
        'navTitle' => 'Hall of Fame g:game',
        'title' => 'Hall of Fame g:game',
        'description' => 'All empires that participated in game g:game.',
        'headline' => 'The data of game g:game',
        'turns' => 'Turns',
        'dimensions' => 'Map',
        'players' => 'Players',
        'endDate' => 'End date',
        'startDate' => 'Start date',
        'winner' => 'Emperor',
        'pagination' => [
            'num' => 'Show :participants of :total players',
            'sort' => 'Sorted by \':sort\' :dir'
        ],
        'perPage' => [
            'showing' => 'Show',
            'results' => 'results per page'
        ],
        'thead' => [
            'ticker' => 'Ticker',
            'name' => 'Empire',
            'population' => 'Population',
            'stars' => 'Stars',
            'died' => 'Died',
            'ships' => 'Ships',
            'shipyards' => 'Shipyards',
        ]
    ]

];
