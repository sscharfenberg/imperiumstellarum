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
        'bugs' => 'Issues'
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
        'description' => 'a turn-based multiplayer browser game of galactic conquest'
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
        'title' => 'Terms of use'
    ],


];
