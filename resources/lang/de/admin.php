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
        'description' => 'Willkommen im Administrations-Bereich.',
        'users' => [
            'sectionTitle' => 'Benutzer',
            'sectionSubtitle' => 'Benutzerkonten verwalten.',
            'total' => 'Gesamte Benutzerkonten',
            'unverified' => 'Benutzerkonten ohne bestätigte E-Mail-Adresse',
            'suspended' => 'Benutzer mit aktiven Sperren'
        ],
        'games' => [
            'sectionTitle' => 'Spiele',
            'sectionSubtitle' => 'Spiele verwalten.',
            'active' => 'Aktive Spiele',
            'notstarted' => 'Spiele die auf den Start warten',
            'canEnlist' => 'Beitretbare Spiele',
            'finished' => 'Abgeschlossene Spiele'
        ]
    ],

    'users' => [
        'navTitle' => 'Benutzer',
        'title' => 'Benutzerkonten Verwalten',
        'thead' => [
            'id' => 'ID',
            'email' => 'E-Mail',
            'isSuspended' => 'Sperre',
            'email_verified_at' => 'E-Mail Bestätigt',
            'locale' => 'Sprache',
            'role' => 'Rolle',
            'game_mail_optin' => 'OptIn',
            'created_at' => 'Erstellt'
        ],
        'pagination' => [
            'num' => 'Zeige :users von :total Benutzern',
            'sort' => 'sortiert nach ":sort" :dir'
        ],
        'perPage' => [
            'showing' => 'Zeige',
            'results' => 'Ergebnisse pro Seite.'
        ],
        'sort' => [
            'asc' => 'Aufsteigend',
            'desc' => 'Absteigend'
        ],
        'filter' => [
            'sectionTitle' => 'Filter Einstellungen',
            'sectionSubtitle' => 'Benutzerliste filtern.',
            'idLabel' => 'Filtern nach UserId',
            'emailLabel' => 'Filtern nach E-Mail',
            'selectEmptyOption' => 'Bitte wählen',
            'role' => 'Filtern nach Rolle',
            'submit' => 'Filter Anwenden'
        ]
    ],

    'user' => [
        'navTitle' => 'Benutzer #:id',
        'title' => 'Benutzer (ID: #:id) Verwalten',
        'data' => [
            'sectionTitle' => 'Benutzerdaten',
            'sectionSubtitle' => 'Benutzerdaten zeigen oder ändern.',
            'emailLabel' => 'Benutzer E-Mail',
            'emailPlaceholder' => 'Benutzer E-Mail',
            'verifiedAt' => 'E-Mail-Adresse bestätigt am',
            'roleLabel' => 'Benutzer Rolle',
            'createdAt' => 'Erstellt am',
            'updatedAt' => 'Aktualisiert am',
            'submitChanges' => 'Änderungen Übernehmen',
            'success' => 'Benutzer wurde erfolgreich aktualisiert',
            'noChange' => 'Keine Änderungen erkannt.'
        ],
        'session' => [
            'sectionTitle' => 'Benutzer Sitzungen',
            'thisDevice' => 'Dieses Gerät',
            'lastActive' => 'Zuletzt aktiv',
            'none' => 'Keine aktiven Sitzungen gefunden.'
        ],
        'suspensions' => [
            'sectionTitle' => 'Benutzer Sperren',
            'intro' => 'Ein roter Hintergrund bedeutet die Sperre ist aktiv (\'Bis\' liegt in der Zukunft).',
            'issued' => 'Erteilt am',
            'until' => 'Bis',
            'issuedBy' => 'Erteilt von (UserId)',
            'reason' => 'Grund',
            'delete' => 'Löschen',
            'deleteModalTitle' => 'Sperre Löschen',
            'deleteModalText' => 'Sind Sie sicher das Sie diese Sperre Löschen wollen? Der User wird per E-Mail informiert.',
            'deleteModalCancel' => 'Abbrechen',
            'deleteModalSubmit' => 'Sperre Löschen',
            'deleteSuspensionError' => 'Sperre oder Benutzer nicht gefunden.',
            'deleteSuspensionSuccess' => 'Sperre gelöscht.',
            'newTitle' => 'Benutzer Sperren',
            'newSubtitle' => 'Erteile eine neue Sperre für dieses Benutzerkonto. Der Benutzer wird ausgeloggt und wird für die angegebene Dauer nicht mehr einloggen können. Der User wird per E-Mail über den Grund für diese Sperre informiert.',
            'durationLabel' => 'Dauer der Sperre',
            'selectEmptyOption' => 'Bitte wählen',
            'duration1' => '24 Stunden',
            'duration3' => '3 Tage',
            'duration7' => '1 Woche',
            'duration14' => '2 Wochen',
            'duration30' => '30 Tage',
            'duration99' => 'Lebenszeit',
            'reasonLabel' => 'Grund',
            'reasonPlaceholder' => 'Sie müssen einen Grund für die Sperre angeben.',
            'submit' => 'Sperre Erstellen.',
            'success' => 'Benutzer wurde gesperrt.'
        ],
        'suspensionNotification' => [
            'subject' => 'Benutzerkonto gesperrt',
            'introduction' => 'Wir bedauern Ihnen mitteilen zu müssen das Ihr Benutzerkonto für Imperium Stellarum für :days Tage gesperrt worden ist, mit sofortiger Wirkung.',
            'introductionForever' => 'Wir bedauern Ihnen mitteilen zu müssen das Ihr Benutzerkonto für Imperium Stellarum mit sofortiger Wirkung für immer gesperrt wurde.',
            'reason' => 'Der Grund für diese Sperre lautet: \':reason\'.',
            'appeal' => 'Falls Sie denken das diese Sperre irrtümlich ausgesprochen wurde steht es Ihnen frei diese Sperre im Forum zu hinterfragen - bitte kontaktieren Sie einen Administrator. Bitte stellen Sie sicher dass Sie sich unserer Nutzungsbedingungen bewusst sind.',
            'action' => 'Imperium Stellarum Stab',
            'until' => 'Die Sperre wird bis :until anhalten. Bis zu diesem Datum werden Sie nicht in der Lage sein sich in die Website / das Spiel einzuloggen. Das Diskussionsforum ist von dieser Sperre nicht betroffen.',
        ],
        'supensionLiftedNotification' => [
            'subject' => 'Sperre aufgehoben',
            'introduction' => 'Die Sperre Ihres Benutzerkontos bis :until wurde entfernt.',
            'reason' => 'Der ursprüngliche Grund für die Sperre war \':reason\'',
            'result' => 'Es steht Ihnen jetzt frei sich wieder in Website/Spiel einzuloggen. Bitte beachten Sie das die Sperre von Ihrem Benutzerkonto gelöscht wurde, es verbleibt kein Hinweis auf diese Sperre.'
        ]
    ],

    'games' => [
        'navTitle' => 'Spiele',
        'title' => 'Spiele Verwalten',
        'thead' => [
            'number' => 'Nummer',
            'active' => 'Aktiv?',
            'processing' => 'In Arbeit?',
            'can_enlist' => 'Beitretbar?',
            'players' => 'Spieler',
            'turn' => 'Runde',
            'created_at' => 'Erstellt',
            'start_date' => 'Start',
            'turn_due' => 'Nächste Runde',
            'seeded' => 'Karte erstellt?'
        ],
        'tbody' => [
            'notStarted' => 'N/A'
        ],
        'pagination' => [
            'num' => 'Zeige :games von :total Spielen',
            'sort' => 'Sortiert nach ":sort" :dir'
        ],
        'perPage' => [
            'showing' => 'Zeige',
            'results' => 'Ergebnisse pro Seite.'
        ],
        'sort' => [
            'asc' => 'Aufsteigend',
            'desc' => 'Absteigend'
        ],
    ],

    'game' => [
        'notfound' => 'Dieses Spiel existiert nicht.',
        'create' => [
            'navTitle' => 'Neues Spiel',
            'title' => 'Neues Spiel Erstellen',
            'headline' => 'Definiere Spiel Metadaten',
            'dimensionsLabel' => 'Kartengröße',
            'dimensionsPlaceholder' => 'Kartengröße angeben',
            'dimensionsDescription' => 'Die angegebene Kartengröße ist für x und y Achse. Größere Karten erlauben mehr Spieler.',
            'startDateLabel' => 'Start Datum',
            'startDatePlaceholder' => 'Wählen Sie das Startdatum',
            'startDateDescription' => 'Datum und Uhrzeit die Sie eingeben vernwenden Ihre aktuelle Zeitzone. Wir konvertieren das Startdatum in Serverzeit (UTC). Das Startdatum darf nicht in der Vergangenheit liegen.',
            'turnDurationLabel' => 'Rundendauer (in Minuten)',
            'turnDurationPlaceholder' => 'geben Sie die Rundendauer an',
            'submit' => 'Spiel Erstellen',
            'success' => 'Spiel erfolgreich erstellt. Bitte beachten Sie das die Karte mit Sternen und Planeten erstellt werden muss bevor das Spiel beitretbar ist oder starten kann .'
        ],
        'edit' => [
            'navTitle' => 'Spiel g:game',
            'title' => 'Spiel g:game',
            'headline' => 'Metadaten von g:game',
            'intro' => 'Alle Datumsangaben (Erstellt, Aktualisiert, Nächste Runde und Start) sind in Serverzeit (UTC). Sie werden nicht in Ihre Zeitzone konvertiert. Das Häkchen bei \'In Arbeit\' zu entfernen bricht nicht die Berechnung der Runde ab - des sollte nur benutzt werden falls die Rundenberechnung abgeschlossen ist, aber \'In Arbeit\' wurde nicht zurückgesetzt.',
            'createdLabel' => 'Erstellt am',
            'updatedLabel' => 'Aktualisiert am',
            'startDateLabel' => 'Start Datum',
            'seededLabel' => 'Karte erstellt?',
            'canEnlistLabel' => 'Beitretbar?',
            'canEnlistDescription' => 'Spiele die auf \'Beitretbar\' stehen werden in der Zentrale von Benutzern aufgelistet und können beigetreten werden.',
            'activeLabel' => 'Spiel ist aktiv?',
            'activeDescription' => 'Spiele die auf \'Aktiv\' stehen werden einmal pro Minute vom Server überprüft ob Sie gestartet werden müssen, Runden berechnet etc.',
            'processingLabel' => 'Spiel/Runde in Arbeit?',
            'processingDescription' => 'Spiele die auf \'In Arbeit\' stehen werden aktuell vom Server bearbeitet. Sie sind für Spieler gesperrt - es können keine neuen Befehle erteilt werden während die Runde berechnet wird.',
            'turnDueLabel' => 'Nächste Runde am',
            'currentTurnLabel' => 'Aktuelle Runde',
            'gameNotStarted' => 'Spiel wurde noch nicht gestartet.',
            'playersLabel' => 'Spieler',
            'submit' => 'Änderungen Übernehmen',
            'seed' => 'Vorschaukarte Erstellen',
            'success' => 'Spiele wurde erfolgreich aktualisiert.'
        ],
        'seed' => [
            'navTitle' => 'g:game Karte',
            'title' => 'Erzeuge Karte für g:game',
            'headline' => 'Kartenvorschau erzeugen',
            'intro' => 'Erzeuge eine Vorschaukarte. Sobald Sie mit dem Ergebnis zufrieden sind, speichern Sie die Ergebnisse lassen den Server Sterne und Planeten erzeugen.',
            'dimensionsLabel' => 'Kartengröße',
            'dimensionsPlaceholder' => 'Kartengröße angeben',
            'npcDistanceLabel' => 'Abstand zwischen NPC Sternen',
            'npcDistanceDescription' => 'Wählen Sie einen Minimum und Maximum Abstand zwischen den NPC Sternen. Die Karte wird mit Sternen gefüllt während min/max Abstände beachtet werden. Mit dieser Einstellung können Sie die Gesamtzahl an Sternen beeinflussen.',
            'playerDistanceLabel' => 'Abstand zwischen Spieler Sternen',
            'playerDistanceDescription' => 'Wählen Sie Minimum und Maximum Abstand zwischen den Spieler Sternen. Diese Einstellung bestimmt die maximale Anzahl an Spielern. Kürzere Abstände zwischen Spielern bedeutet früher ein agressiveres Spiel.',
            'preview' => 'Vorschau erzeugen',
            'totals' => 'Sterne gesamt',
            'npcStars' => 'NPC Sterne',
            'playerStars' => 'Spieler Sterne',
            'mapTitle' => 'Erzeugte Vorschaukarte',
            'submit' => 'Speichern &amp; Stern/Planeten erzeugen',
            'success' => 'Sterne und Planeten wurden erfolgreich erzeugt.'
        ],
        'players' => [
            'button' => 'Eingeschriebene Spieler',
            'navTitle' => 'g:game Spieler',
            'title' => 'Spieler die für g:game eingeschrieben sind',
            'thead' => [
                'id' => 'Benutzer Id',
                'email' => 'Benutzer E-Mail',
                'isSuspended' => 'Benutzer gesperrt',
                'locale' => 'Benutzer Sprache',
                'created_at' => 'Beigetreten am',
                'ticker' => 'Ticker',
                'name' => 'Imperiums Name',
            ],
            'pagination' => [
                'num' => 'Zeige :players von :total Spielern',
                'sort' => 'sortiert nach ":sort" :dir'
            ],
        ]
    ],

];
