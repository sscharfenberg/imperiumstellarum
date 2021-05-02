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
        'drawerButton' => 'Hauptmenü'
    ],

    'drawer' => [
        'localTime' => 'Lokale Zeit',
        'currentTurn' => 'Aktuelle Runde',
        'nextTurn' => 'Nächste Runde',
        'forums' => 'Foren',
        'github' => 'Github',
        'discord' => 'Discord'
    ],

    'footer' => [
        'handcrafted' => 'Handgemacht mit',
        'name' => 'von Sven Scharfenberg'
    ],

    'notification' => [
        'warning' => 'Hinweis',
        'success' => 'Erfolg',
        'error' => 'Fehler'
    ],

    'user' => [
        'suspended' => 'Ihr Benutzerkonto ist bis :until gesperrt. Bitte überprüfen Sie Ihre Mailbox.'
    ],

    // PAGES

    'home' => [
        'navTitle' => 'Home',
        'title' => 'Willkommen bei Imperium Stellarum',
        'description' => 'ein rundenbasiertes Mehrspieler Browser Spiel der galaktischen Eroberung.',
        'hero' => [
            'left' => 'Ihr',
            'middle' => 'Imperium',
            'right' => 'wartet',
        ]
    ],

    'register' => [
        'navTitle' => 'Registrieren',
        'title' => 'Benutzerkonto erstellen',
        'description' => 'Erstellen Sie ein Benutzerkonto.',
        'headline' => 'Schön das Sie sich registrieren!',
        'emailLabel' => 'E-Mail',
        'emailPlaceholder' => 'Geben Sie Ihre E-Mail-Adresse ein',
        'emailDescription' => 'Ihre E-Mail wird zum einloggen verwendet, und wir schicken Ihnen Mails zur Aktivierung des Benutzerkontos und zum Zurücksetzen des Passworts. Ihre E-Mail-Adresse ist die einzige persönliche Information die wir von Ihnen in unserer Datenbank speichern. Sie wird niemals anderen Benutzern angezeigt.',
        'passwordLabel' => 'Passwort',
        'passwordPlaceholder' => 'Wählen Sie Ihr Passwort',
        'passwordDescription' => 'Bitte wählen Sie ein sicheres Passwort das nirgendwo anders verwendet wird. Wir berechnen die Stärke des Passwortes - das \'Schloß\' Icon muss muss grün werden damit das Passwort akzeptiert wird.',
        'repeatPasswordLabel' => 'Passwort wiederholen',
        'repeatPasswordPlaceholder' => 'Geben Sie das Passwort erneut ein',
        'repeatPasswordDescription' => 'Bitte geben Sie das Passwort erneut ein um Tippfehler zu verindern.',
        'confirmedLabel' => 'Ich akzeptiere die Hinweise zum Datenschutz und die Nutzungsbedingungen.',
        'errors' =>  [
            'email' =>  [
                'invalid' => 'Das scheint keine gültige E-Mail-Adresse zu sein.',
                'required' => 'Die E-Mail-Adresse ist erforderlich.',
                'unique' => 'Diese E-Mail-Adresse ist bereits registriert.'
             ],
            'password' =>  [
                'required' => 'Das Passwort ist erforderlich.',
                'confirmed' => 'Das wiederholte Passwort unterhalb ist nicht identisch mit dem eingegebenen Passwort oberhalb.',
                'length' => 'Das Passwort muss mindestens 6 Zeichen enthalten.',
                'strength' => 'Ihr Passwort ist nicht sicher genug. Bitte verwenden Sie ein stärkeres Passwort, zum Beispiel in dem Sie Zahlen/Buchstaben/Sonderzeichen hinzufügen.'
             ],
            'confirmed' =>  [
                'accepted' => 'Bitte akzeptieren Sie unsere Hinweise zum Datenschutz und die Nutzungsbedingungen.'
             ]
         ],
        'success' =>  [
            'headline' => 'Die Registrierung war erfolgreich.',
            'text' => 'Sie haben erfolgreich ein Benutzerkonto registriert. Wir haben Ihnen eine E-Mail geschickt die einen Link zum aktivieren des Benutzerkontos enthält. Bitte klicken Sie auf den Link um Ihr Benutzerkonto zu aktivieren.'
         ],
        'submit' => 'Registrierung abschicken'
    ],

    'verify' => [
        'navTitle' => 'E-Mail Bestätigen',
        'title' => 'E-Mail-Adresse Bestätigen',
        'text' => 'Ihre E-Mail ist nicht bestätigt. Bitte überprüfen Sie Ihre Mailbox und klicken Sie auf den \'E-Mail-Adresse bestätigen\' Button.',
        'sent' => 'Bestätigungs Link verschickt.',
        'resend' => 'E-Mail erneut versenden',
        'success' => 'Ihre E-Mail ist jetzt bestätigt.'
    ],

    'login' => [
        'navTitle' => 'Login',
        'title' => 'Einloggen ins Benutzerkonto',
        'description' => 'Wilkommen zurück.',
        'headline' => 'Ihr Imperium wartet.',
        'emailLabel' => 'E-Mail',
        'emailPlaceholder' => 'Geben Sie Ihre E-Mail-Adresse ein',
        'passwordLabel' => 'Passwort',
        'passwordPlaceholder' => 'Geben Sie Ihr Passwort ein',
        'rememberLabel' => 'Zugangsdaten auf dem Rechner merken',
        'submit' => 'Login',
    ],

    'forgot' => [
        'navTitle' => 'Password vergessen',
        'title' => 'Haben Sie Ihr Passwort vergessen?',
        'headline' => 'Zurücksetzen des Passwortes beantragen',
        'description' => 'Beantragen Sie ein Zurücksetzen des Passwortes indem Sie Ihre E-Mail-Adresse angeben. Wir schicken Ihnen dann einen Link mit dem Sie Ihr Passwort ändern können.',
        'submit' => 'Passwort Zurücksetzen Beantragen'
    ],

    'reset' => [
        'navTitle' => 'Passwort zurücksetzen',
        'title' => 'Passwort zurücksetzen',
        'headline' => 'Neues Passwort angeben.',
        'emailDescription' => 'Bitte geben Sie auch Ihre E-Mail-Adresse als zusätzliche Sicherheit ein.',
        'submit' => 'Passwort ändern'
    ],

    'logout' => [
        'navTitle' => 'Ausloggen'
    ],

    'dashboard' => [
        'navTitle' => 'Zentrale',
        'title' => 'Meine Zentrale',
        'description' => 'Ändern Sie die Daten Ihres Benutzerkontos oder treten Sie Spielen bei.',
        'email' => [
            'sectionTitle' => 'E-Mail',
            'sectionSubtitle' => 'Aktualisieren Sie Ihre E-Mail-Adresse.',
            'emailLabel' => 'E-Mail',
            'emailPlaceholder' => 'Neue E-Mail Adresse eingeben',
            'description' => 'Bitte beachten Sie das durch das Ändern Ihrer E-Mail-Adresse die neue E-Mail-Adresse wieder bestätigt werden muss. Sie werden ausgeloggt und erhalten eine Benachrichtung per Mail von uns.',
            'submit' => 'E-Mail ändern',
            'success' => 'Ihre E-Mail wurde geändert. Wir haben Ihnen einen neuen Bestätigungslink geschickt, bitte prüfen Sie Ihre Mailbox.',
        ],
        'password' => [
            'sectionTitle' => 'Passwort',
            'sectionSubtitle' => 'Bitte stellen Sie sicher das Sie ein starkes Passwort verwenden.',
            'currentPasswordLabel' => 'Aktuelles Passwort',
            'currentPasswordPlaceholder' => 'Aktuelles Passwort eingeben',
            'newPasswordLabel' => 'Neues Passwort',
            'newPasswordPlaceholder' => 'Geben Sie das neue Passwort ein',
            'repeatPasswordLabel' => 'Passwort Bestätigen',
            'repeatPasswordPlaceholder' => 'Wiederholen Sie das neue Passwort.',
            'submit' => 'Passwort Ändern',
            'success' => 'Ihr Passwort wurde geändert. Bitte aktualisieren Sie Ihren Passwort Manager bzw. die Browser Einstellungen.'
        ],
        'theme' => [
            'sectionTitle' => 'Motiv',
            'sectionSubtitle' => 'Wählen Sie zwischen einem dunklen und einem hellen Motiv',
            'label' => 'Wählen Sie Ihr Motiv.',
            'dark' => 'Dunkles Motiv',
            'light' => 'Helles Motiv',
            'browser' => 'Browser / Betriebssystem entscheidet',
            'description' => 'Die Einstellungen für das Motiv werden in Ihrem Browser gespeichert.'
        ],
        'notifications' => [
            'sectionTitle' => 'Benachrichtigungen',
            'sectionSubtitle' => 'Wählen Sie ob Sie Benachrichtigungen zu Spielen erhalten möchten.',
            'label' => 'Ja, schicken Sie mir E-Mails mit Benachrichtigungen zu Spielen.',
            'description' => 'Wenn der Haken gesetzt ist werden Sie E-Mail Benachrichtigungen zu Spielen von uns erhalten. E-Mails die das Benutzerkonto betreffen (E-Mail Bestätigungen, Passwort Zurücksetzen etc) sind davon nicht betroffen.',
            'submit' => 'Einstellungen Ändern',
            'success' => 'Ihre Benachrichtigungs-Einstellungen wurden geändert.'
        ],
        'delete' => [
            'sectionTitle' => 'Benutzerkonto Löschen',
            'sectionSubtitle' => 'Benutzerkonto unwiderruflich löschen.',
            'description' => 'Wenn Ihr Benutzerkonto gelöscht wird, sind alle Ressourcen und Daten permanent verloren. Sie verlieren dann auch Zugang zu aktiven Spielen denen Sie beigetreten sind. Diese Aktion kann nicht rückgängig gemacht werden.',
            'submit' => 'Benutzerkonto Löschen',
            'warning' => 'Sobald Ihr Benutzerkonto gelöscht wurde, sind alle Ressourcen und alle Daten permament verloren. Sind Sie sicher das Sie das Benutzerkonto löschen wollen?',
            'confirmSubmit' => 'Konto Löschen',
            'cancel' => 'Abbrechen',
            'success' => 'Ihr Benutzerkonto wurde gelöscht.'
        ],
        'session' => [
            'sectionTitle' => 'Sitzungen',
            'sectionSubtitle' => 'Hier zeigen wir Ihre aktiven Sitzungen - Geräte die aktuellen in Ihr Benutzerkonto eingeloggt sind.',
            'thisDevice' => 'Dieses Gerät',
            'lastActive' => 'Zuletzt aktiv',
            'none' => 'Keine aktive Sitzung gefunden.'
        ],
        'availableGames' => [
            'sectionTitle' => 'Verfügbare Spiele',
            'sectionSubtitle' => 'Hier listen wir Spiele auf denen Sie beitreten können.',
            'players' => 'Spieler',
            'stars' => 'Sterne',
            'start' => 'Start',
            'enlist' => 'Beitreten'
        ],
        'players' => [
            'sectionTitle' => 'Meine Spiele',
            'sectionSubtitle' => 'Hier listen wir Spiele auf denen Sie beigetreten sind.',
            'quit' => 'Verlassen',
            'quitTitle' => 'Spiel g:game Verlassen',
            'quitWarning' => 'Sind Sie sicher das Sie das Spiel verlassen wollen? Sie verlieren möglicherweise Ihren Platz für das Spiel. Falls das Spiel bereits gestartet ist, wird Ihr Imperium gelöscht.',
            'quitSubmit' => 'g:game Verlassen'
        ]
    ],

    'enlist' => [
        'navTitle' => 'g:game Beitreten',
        'title' => 'Spiel g:game Beitreten',
        'headline' => 'Danke fürs Beitreten!',
        'game' => 'Spiel',
        'turnDuration' => 'Rundendauer',
        'minutes' => 'Minuten',
        'start' => 'Start Datum',
        'players' => 'Aktuelle Spieler',
        'stars' => 'Sterne',
        'dimensions' => 'Kartengröße',
        'nameLabel' => 'Name Des Imperiums',
        'namePlaceholder' => 'Bitte Name des Imperiums eingeben',
        'nameDescription' => 'Der Name Ihres Imperiums wird anderen Spielern angezeigt und in Nachrichten verwendet. Bitte nutzene Sie :min bis :max Zeichen.',
        'tickerLabel' => 'Ticker Des Imperiums',
        'tickerPlaceholder' => 'Bitte geben Sie den Ticker ein',
        'tickerDescription' => 'Ihr Ticker ist die Abkürzung des Namens Ihres Imperiums(:min - :max Zeichen). Er wird auf der Karte angezeigt und in Schiffsnamen und Nachrichten verwendet.',
        'colourLabel' => 'Farbe des Imperiums',
        'colourPlaceholder' => 'Bitte eine Farbe wählen',
        'colourDescription' => 'Ihre Farbe wird auf der Sternenkarte verwendet um Ihre Systeme zu markieren.',
        'submit' => 'Spiel Beitreten',
        'notEnlistable' => 'Dieses Spiel erlaubt keine Beitritte.',
        'started' => 'Das Spiel wurde bereits gestartet.',
        'full' => 'Dieses Spiel ist leider schon voll.',
        'alreadyEnlisted' => 'Sie sind bereits beigetreten.',
        'success' => 'Sie sind erfolgreich Spiel g:game beigetreten.'
    ],

    'quit' => [
        'notFound' => 'Konnte Spiel / Spieler nicht finden.',
        'success' => 'Sie haben Spiel g:game erfolgreich verlassen.'
    ],

    'privacy' => [
        'navTitle' => 'Datenschutz',
        'title' => 'Hinweise zum Datenschutz',
        'description' => 'Diese Hinweise zum Datenschutz zeigt auf wie die Website Ihre persönlichen Daten verwendet.',
        'whatData' => [
            'title' => 'Welche Daten sammeln wir?',
            'paragraph1' => 'Wir stellen sicher so wenig wie möglich persönliche Daten von Ihnen zu sammeln. Wir speichern lediglich Ihre E-Mail-Adresse in unserer Datenbank. Darüberhinaus werden keine weiteren persönlichen Daten gesammelt oder gespeichert.'
        ],
        'howData' => [
            'title' => 'Wie sammeln wir Ihre Daten?',
            'paragraph1' => 'Sie geben Ihre Daten direkt an wenn Sie sich registrieren.',
        ],
        'dataUse' => [
            'title' => 'Wie verwenden wir Ihre persönlichen Daten?',
            'paragraph1' => 'Wir nutzen Ihre E-Mail-Adresse um Sie als eindeutiges Identifikations-Merkmal zu verwenden.',
            'paragraph2' => 'Wir schicken Ihnen E-Mails an Ihre E-Mail-Adresse wenn Ihre E-Mail-Adresse bestätigt werden muss oder wenn Sie Ihr Passwort vergessen haben. Wir schicken Ihnen ebenfalls spielbezogene E-Mails wenn Sie diese Funktion in Ihrer Zentrale aktiviert haben.',
            'paragraph3' => 'Wir teilen Ihre Daten nicht mit Dritten, es sei denn wir werden von deutschen Strafverfolgungsbehörden dazu verpflichtet.'
        ],
        'dataStorage' => [
            'title' => 'Wie speichern wir Ihre Daten?',
            'paragraph1' => 'Wir speichern Ihre Daten in unserer Datenbank. Die Datenbank ist auf dem gleichen Server eingerichtet den auch diese Website verwendet. Die Datenbank akzeptiert keine Verbindungen aus dem Internet, nur von der Website selbst.'
        ],
        'dataProtection' => [
            'title' => 'Was sind Ihre Datenschutz Rechte?',
            'paragraph1' => 'Wir möchten gerne sicherstellen das Sie sich aller Datenschutzrechte bewusst sind. Jeder Nutzer der Website hat die folgenden Rechte:',
            'bullet1' => [
                'title' => 'Das Recht auf Zugriff auf die Daten.',
                'point' => 'Sie haben das Recht Kopieen der gespeicherten Daten zu verlangen. Wir dürfen dafür eine Bearbeitungsgebühr verlangen.'
            ],
            'bullet2' => [
                'title' => 'Das Recht zur Richtigstellung.',
                'point' => 'Sie haben das Recht die Korrektur aller Daten zu verlangen von deren Inkorrektheit Sie überzeugt sind. Sie haben ebenfalls das Recht zu verlangen das wir Daten von Ihnen vervollständigen.'
            ],
            'bullet3' => [
                'title' => 'Das Recht auf Löschung',
                'point' => 'Sie haben das Recht zu verlangen das wir Ihre persönlichen Daten löschen, unter bestimmten Vorraussetzungen. Sie können Ihr Benutzerkonto in Ihrer Zentrale selbst löschen, oder Sie können uns bitten das durchzuführen.'
            ],
            'bullet4' => [
                'title' => 'Das Recht die Datenverarbeitung einzuschränken',
                'point' => 'Sie haben das Recht zu verlangen das wir die Verarbeitung Ihrer Daten einschränken, unter bestimmten Vorraussetzungen. Bitte beachten Sie das wir Ihre Daten nicht weiter verarbeiten.',
            ],
            'bullet5' => [
                'title' => 'Das Recht der Datenverarbeitung zu widersprechen',
                'point' => 'Sie haben das Recht der Verarbeitung Ihrer Daten zu widersprechen, unter bestimmten Vorraussetzungen. Bitte beachten Sie das wir Ihre Daten nicht weiter verarbeiten.'
            ],
            'bullet6' => [
                'title' => 'Das Recht der Datenweitergabe',
                'point' => 'Sie haben unter bestimmten Vorraussetzungen das Recht zu verlangen das wir die Daten die wir von Ihnen gesammelt haben weitergeben an eine andere Organisation, oder direkt an Sie.'
            ],
            'paragraph2' => 'Wenn Sie eine Anfrage an uns stellen haben wir einen Monat Zeit um darauf zu antworten. Wenn Sie eines dieser Recht ausüben möchten kontaktieren Sie uns unter:'
        ],
        'cookies'=> [
            'title' => 'Cookies',
            'paragraph1' => 'Cookies sind Textdateien die auf Ihrem Computer gespeichert werden um Informationen zu sammeln und/oder um Funktionen bereit zu stellen. Wenn Sie diese Website verwenden dürfen wir Informationen von Ihnen durch Cookies oder ähnliche Techniken erfassen.',
            'paragraph2' => 'Für mehr Informationen über Cookies, besuchen Sie bitte allaboutcookies.org.'
        ],
        'cookiesHow'=> [
            'title' => 'Wie verwenden wir Cookies?',
            'paragraph1' => 'Wir nutzen Cookies für verschiedene Einsatzzwecke um die Nutzererfahrung dieser Website zu verbessern:',
            'bullet1' => 'Damit Sie nach Login eingeloggt bleiben.',
            'bullet2' => 'Das Speichern Ihrer Login Informationen in einem sicheren Format, falls Sie beim Einloggen diese Option verwenden.',
            'bullet3' => 'Um Sie gegen Cross-Site Request Forgery zu schützen.'
        ],
        'cookiesTypes' => [
            'title' => 'Welche Arten von Cookies nutzen wir?',
            'paragraph1' => 'Es gibt verschiedene Arten von Cookies - wir verwenden allerdings ausschließlich funktional notwendige Cookies.',
            'paragraph2' => 'Wir nutgzen diese Cookies um Sie auf der Website zu erkennen und Ihre gewählten Einstellungen zu verwenden bzw. um Sie einloggt zu halten. Wir nutzen ausschließlich first-party cookies, es werden keine Cookies von Dritten genutzt.'
        ],
        'cookiesManage' => [
            'title' => 'Wie Sie Cookies verwalten können',
            'paragraph1' => 'Sie können Ihren Browser anweisen keine Cookies zu akzeptieren, oder gezielt einzelne Cookies löschen. Dadurch funktionieren allerdings einige der Funktionen der Website nicht mehr korrekt.'
        ],
        'cookiesOther' => [
            'title' => 'Datenschutzhinweise von anderen Websites',
            'paragraph1' => 'Diese Website enthält Links auf andere Websites. Unsere Datenschutzhinweise betreffen nur unsere Website - wenn Sie also einen Link verwenden der Sie zu einer anderen Website bringt sollten Sie deren Datenschutzhinweise lesen.'
        ],
        'cookiesChanges' => [
            'title' => 'Änderungen zu unseren Datenschutzhinweisen',
            'paragraph1' => 'Wir überprüfen unsere Datenschutzhinweise regelmäßige und stellen alle Änderungen auf diese Seite. Diese Datenschutzhinweise wurden zuletzt am 18. Oktober 2020 aktualisiert.'
        ],
        'contact' => [
            'title' => 'Wie Sie uns kontaktieren',
            'paragraph1' => 'Wenn Sie Fragen zu unseren Datenschutzhinweisen haben, oder zu den Daten die wir über Sie speichern, oder wenn Sie eines Ihrer Datenschutzrechte ausüben möchten, zögern Sie bitte nicht uns zu kontaktieren.',
            'paragraph2' => 'Schreiben Sie uns eine E-Mail unter '
        ],
    ],

    'imprint' => [
        'navTitle' => 'Impressum',
        'title' => 'Impressum',
        'paragraph1' => 'Verwantwortlich für Inhalte nach § 5 TMG',
        'paragraph2' => 'Vertreten durch',
        'contact' => 'Kontakt',
        'disclaimer' => 'Haftungsausschluß',
        'attribution' => 'Zuschreibung',
        'paragraph3' => 'Alle Bilder und Icons die nicht vom Author erstellt worden sind, werden auf der \'Github Attribution\' Seite aufgelistet.',
        'headline4' => 'Haftung für Inhalte',
        'paragraph4' => 'Die Inhalte unserer Seiten wurden mit größter Sorgfalt erstellt. Für die Richtigkeit, Vollständigkeit und Aktualität der Inhalte können wir jedoch keine Gewähr übernehmen. Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.',
        'headline5' => 'Haftung für Links',
        'paragraph5' => 'Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.',
        'generatedby' => 'Website Impressum von impressum-generator.de'
    ],

    'terms' => [
        'navTitle' => 'Nutzungsbedingungen',
        'title' => 'Nutzungsbedingungen',
        'intro' => 'Bitte lesen Sie sich diese Nutzungsbedingungen sorgfältig durch bevor Sie ein Benutzerkonto erstellen.',
        'acceptance' => [
            'title' => 'Akzeptieren der Nutzungsbedingungen',
            'paragraph1' => 'Ihr Zugriff auf die Imperium Stellarum website hängt von Ihrer Zustimmung zu und Einhaltung dieser Nutzungsbedingungen ab.',
            'paragraph2' => 'Der Zugriff hängt außerdem von der Zustimmung zu den Datenschutzbedingungen der Imperium Stellarum website ab. Unsere Datenschutzbedingungen beschreben welche persönlichen Daten wir von Ihnen sammeln und wie diese verwendet werden, und führt Ihre Datenschutzrechte auf. Bitte lesen Sie sich unsere Datenschutz Hinweise ebenfalls sorgfältig durch.'
        ],
        'termination' => [
            'title' => 'Beendigung oder Sperre Ihres Benutzerkontos',
            'paragraph' => 'Verstöße gegen unsere Nutzungsbedingungen kann in einer Warnung per ingame Benachrichtigung oder Privatnachricht in den Foren resultieren, oder in der Sperre Ihres Benutzerkontos für eine bestimmte Zeit, oder aber in der Sperre Ihres Benutzerkontos, abhängig von dem Verstoß nach Ermessen des Imperium Stellarum Administrations Teams.'
        ],
        'warranty' => [
            'title' => 'Haftungsausschluß',
            'paragraph1' => 'Die Imperium Stellarum Entwickler, als auch das Imperium Stellarum Administrations und Hosting Team, können nicht haftbar gemacht werden für jegliche Fehler in der Website und/oder dem Spiel. Fehler können dazu führen das Ihre Daten verloren gehen oder private Informationen bekannt werden. Wie in unseren Hinweisen zum Datenschutz aufgeführt, stellen wir sicher so wenig wie mögliche poersönliche Daten von Ihnen zu sammeln - wir sammeln und speichern ausschließlich Ihre E-Mail-Adresse.',
            'contributors' => 'Imperium Stellarum Entwickler'
        ],
        'communityGuidelines' => [
            'title' => 'Gemeinschafts Richtlinien',
            'paragraph1' => 'Imperium Stellarum ist ein Spiel das vor allem Spaß machen soll und eine Herausforderung darstellen für alle Spieler. Wir erwarten von allen registrierten Benutzern das Sie genau unterscheiden zwischen den Imperien die im Spiel gespielt werden und den Menschen die diese Imperien steuern.',
            'paragraph2' => 'Das bedeutet das an Spielmechaniken praktisch alles zugelassen ist. Sie dürfen andere Imperien betrügen, ausnehmen, erpresssen, belügen oder bedrohen. Sie dürfen zwei andere Imperien gegeneinander aufhetzen, um dann Ihre System zu übernehmen. Sie dürfen kleinere Imperien Befehle erteilen. Sie dürfen größere Imperien betrügen wenn Ihnen Befehle erteilt werden.',
            'paragraph3' => 'Allerdings gibt es Grenzen, ganz besonders wenn Nachrichten an andere Spieler verschickt werden. Lassen Sie das reallife außen vor, sprechen Sie nicht den Menschen an der das Imperium steuert. Es ist in Ordnung die Rolle eines bösen Imperium zu spielen, aber übertreiben Sie es nicht. Kurz gesagt - seien Sie kein Arsch wenn Sie anderen Spielern Nachrichten schicken.',
            'paragraph4' => 'Außerdem - Rassismus, Frauenfeindlichkeit, Diskriminierung andere Benutzer aufgrund von sozialem Status, Herkunft oder anderem wird unter keinen Umständen toleriert und resultiert in der Beendigung oder Sperre des Benutzerkontos.',
            'paragraph5' => 'Jeder Spieler hat die Option beleidigende oder verletzende Nachricht an das Admin Team zu melden. Das Admin Team wird nachforschen und entsprechende Maßnahmen ergreifen.'
        ],
        'exploits' => [
            'title' => 'Sicherheitsprobleme und das Ausnutzen von Spiel Unschärfen/Fehlern/Exploits',
            'paragraph1' => 'Wir bitten alle Benutzer jegliche Exploits oder Sicherheitsprobleme direkt dem Administrationsteam zu melden um das Spiel sicher und fair für alle Spieler zu machen.',
            'paragraph2' => 'Wenn Sie Kentniss von einem Exploit oder einem Sicherheitsproblem erlangen, kontaktieren Sie bitte das Administrationsteam per Direktnachricht im Forum - posten Sie Exploits und Sicherheitsprobleme nicht öffentlich.',
            'staff' => 'Imperium Stellarum Administrationsteam',
            'paragraph3' => 'Das Ausnutzen von Fehlern im Spiel resultiert in einer Strafe nach Ermessen des Administrationsteams.',
            'paragraph4' => 'Das Ausnutzen von Sicherheitsproblemen in der Website oder im Spiel - oder das Manipulieren oder Beeinflussen der Imperium Stellarum Server und Dienste - wird immer in einer Sperre auf Lebenszeit resultieren, und möglicherweise bei deutschen Strafverfolgungsbehörden zur Anzeige gebracht, abhängig von der Schwere des Vorfalls.'
        ],
        'multiBoxing' => [
            'title' => 'Mehrfache Benutzerkonten',
            'paragraph1' => 'Sie dürfen nur maximal ein Benutzerkonto haben. Sollten Sie ein zweites Benutzerkonto erstellen - Sei es um Vorteile im Spiel zu erlangen, oder um eine Sperre des Benutzerkontos zu umgehen - wird das zusätzliche Benutzerkonto gesperrt, und wird werden entsprechende Strafen gegen das ursprüngliche Benutzerkonto verhängen.',
            'paragraph2' => 'Wir fordern alle Spieler auf dem Administrationsteam vermutete mehrfache Benutzerkonto zu melden.',
            'paragraph3' => 'Eine Sperre des Benutzerkontos durch ein weiteres Benutzerkonto zu umgehen resultiert immer in einer Sperre oder Beendigung des Benutzerkontos, abhängig von Motivation, Schwere und Kontext.',
            'paragraph4' => 'Wenn Sie Probleme haben auf Ihr Benutzerkonto zuzugreifen, versuchen Sie bitte als erstes dies Probleme selbst zu lösen (Passwort vergessen). Sollte das nicht helfen wird das Administrationsteam Ihnen gerne behilflich sein.',
        ],
    ],


];
