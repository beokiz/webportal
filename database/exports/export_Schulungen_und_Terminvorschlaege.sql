WITH RankedData AS (
    SELECT
        'Schulung' AS `Typ`,
        CONCAT('Schulung-', t.id) AS `Event-Nr`,
        CASE
            WHEN t.status = 'planned' THEN 'Geplant'
            WHEN t.status = 'confirmed' THEN 'Bestätigt'
            WHEN t.status = 'completed' THEN 'Abgeschlossen'
            WHEN t.status = 'cancelled' THEN 'Abgesagt'
            ELSE t.status
        END AS `Status`,
        CASE
            WHEN t.type = 'combined' THEN 'Zusammengelegt'
            WHEN t.type = 'in-house' THEN 'In-house'
            ELSE t.type
        END AS `Art der Schulung`,
        DATE_FORMAT(t.first_date, '%d.%m.%Y') AS `Erster Schulungstag`,
        DATE_FORMAT(t.second_date, '%d.%m.%Y') AS `Zweiter Schulungstag`,
        t.location AS `Schulungsort`,
        CASE
            WHEN (t.street IS NOT NULL AND t.street != '') AND (t.house_number IS NOT NULL AND t.house_number != '') THEN CONCAT(t.street, ' ', t.house_number, ', ', t.zip_code, ' ', t.city)
            WHEN t.street IS NOT NULL AND t.street != '' THEN CONCAT(t.street, ', ', t.zip_code, ' ', t.city)
            WHEN t.house_number IS NOT NULL AND t.house_number != '' THEN CONCAT(t.house_number, ', ', t.zip_code, ' ', t.city)
            ELSE NULL
        END AS `Schulungsadresse`,
        t.participant_count AS `Teilnehmeranzahl - Schulung`,
        k.name AS `Kitaname`,
        k.number AS `Kitannummer`,
        k.num_pedagogical_staff AS `Anzahl pFK - Kita`,
        CASE
            WHEN (k.street IS NOT NULL AND k.street != '') AND (k.house_number IS NOT NULL AND k.house_number != '') THEN CONCAT(k.street, ' ', k.house_number)
            WHEN k.street IS NOT NULL AND k.street != '' THEN k.street
            WHEN k.house_number IS NOT NULL AND k.house_number != '' THEN k.house_number
            ELSE NULL
        END AS `Straße - Kita`,
        k.zip_code AS `PLZ - Kita`,
        CASE
            WHEN (o.name IS NOT NULL AND o.name != '') AND (k.other_operator IS NOT NULL AND k.other_operator != '') THEN CONCAT(o.name, ', ', k.other_operator)
            WHEN o.name IS NOT NULL AND o.name != '' THEN o.name
            WHEN k.other_operator IS NOT NULL AND k.other_operator != '' THEN k.other_operator
            ELSE NULL
        END AS `Träger`,
        GROUP_CONCAT(DISTINCT CONCAT(multiplikator_user.first_name, ' ', multiplikator_user.last_name) SEPARATOR ', ') AS `Multiplikator:In`,
        GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Zugeordnete Manager der Einrichtung`,
        GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadresse der Manager`,
        ROW_NUMBER() OVER (PARTITION BY k.name ORDER BY t.first_date ASC) AS `Rank`,
        MIN(t.first_date) OVER (PARTITION BY k.name) AS `EarliestDate`
    FROM
        beokiz_prod.trainings t
    LEFT JOIN
        beokiz_prod.kita_has_trainings kht ON t.id = kht.training_id
    LEFT JOIN
        beokiz_prod.kitas k ON kht.kita_id = k.id
    LEFT JOIN
        beokiz_prod.operators o ON k.operator_id = o.id
    LEFT JOIN
        beokiz_prod.kita_has_users khu ON k.id = khu.kita_id
    LEFT JOIN
        beokiz_prod.users kita_user ON khu.user_id = kita_user.id
    LEFT JOIN
        beokiz_prod.users multiplikator_user ON t.multi_id = multiplikator_user.id
    WHERE
        k.id IS NOT NULL
        AND t.id NOT IN (1, 2, 3, 27)
        AND k.id NOT IN (1,2,21,109,110,111,134,199,220,221,245,249)
    GROUP BY
        t.id, k.id, o.name

    UNION ALL

    SELECT
        'Terminvorschlag' AS `Typ`,
        CONCAT('Terminvorschlag-', tp.id) AS `Event-Nr`,
        CASE
            WHEN tp.status = 'open' THEN 'Offen'
            WHEN tp.status = 'reserved' THEN 'Reserviert'
            WHEN tp.status = 'email_not_confirmed' THEN 'Email-Adresse nicht bestätigt'
            WHEN tp.status = 'confirmation_pending' THEN 'Bestätigung ausstehend'
            WHEN tp.status = 'obsolete' THEN 'Obsolet'
            WHEN tp.status = 'confirmed' THEN 'Bestätigt'
            ELSE tp.status
        END AS `Status`,
        'In-house' AS `Art der Schulung`,
        DATE_FORMAT(tp.first_date, '%d.%m.%Y') AS `Erster Schulungstag`,
        DATE_FORMAT(tp.second_date, '%d.%m.%Y') AS `Zweiter Schulungstag`,
        tp.location AS `Schulungsort`,
        CASE
            WHEN (tp.street IS NOT NULL AND tp.street != '') AND (tp.house_number IS NOT NULL AND tp.house_number != '') THEN CONCAT(tp.street, ' ', tp.house_number, ', ', tp.zip_code, ' ', tp.city)
            WHEN tp.street IS NOT NULL AND tp.street != '' THEN CONCAT(tp.street, ', ', tp.zip_code, ' ', tp.city)
            WHEN tp.house_number IS NOT NULL AND tp.house_number != '' THEN CONCAT(tp.house_number, ', ', tp.zip_code, ' ', tp.city)
            ELSE NULL
        END AS `Schulungsadresse`,
        tp.participant_count AS `Teilnehmeranzahl - Schulung`,
        k.name AS `Kitaname`,
        k.number AS `Kitannummer`,
        k.num_pedagogical_staff AS `Anzahl pFK - Kita`,
        CASE
            WHEN (k.street IS NOT NULL AND k.street != '') AND (k.house_number IS NOT NULL AND k.house_number != '') THEN CONCAT(k.street, ' ', k.house_number)
            WHEN k.street IS NOT NULL AND k.street != '' THEN k.street
            WHEN k.house_number IS NOT NULL AND k.house_number != '' THEN k.house_number
            ELSE NULL
        END AS `Straße - Kita`,
        k.zip_code AS `PLZ - Kita`,
        CASE
            WHEN (o.name IS NOT NULL AND o.name != '') AND (k.other_operator IS NOT NULL AND k.other_operator != '') THEN CONCAT(o.name, ', ', k.other_operator)
            WHEN o.name IS NOT NULL AND o.name != '' THEN o.name
            WHEN k.other_operator IS NOT NULL AND k.other_operator != '' THEN k.other_operator
            ELSE NULL
        END AS `Träger`,
        GROUP_CONCAT(DISTINCT CONCAT(multiplikator_user.first_name, ' ', multiplikator_user.last_name) SEPARATOR ', ') AS `Multiplikator:In`,
        GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Zugeordnete Manager der Einrichtung`,
        GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadresse der Manager`,
        ROW_NUMBER() OVER (PARTITION BY k.name ORDER BY tp.first_date ASC) AS `Rank`,
        MIN(tp.first_date) OVER (PARTITION BY k.name) AS `EarliestDate`
    FROM
        beokiz_prod.training_proposals tp
    LEFT JOIN
        beokiz_prod.kita_has_training_proposals kht ON tp.id = kht.training_proposal_id
    LEFT JOIN
        beokiz_prod.kitas k ON kht.kita_id = k.id
    LEFT JOIN
        beokiz_prod.operators o ON k.operator_id = o.id
    LEFT JOIN
        beokiz_prod.users multiplikator_user ON tp.multi_id = multiplikator_user.id
    LEFT JOIN
        beokiz_prod.kita_has_users khu ON k.id = khu.kita_id
    LEFT JOIN
        beokiz_prod.users kita_user ON khu.user_id = kita_user.id
    WHERE
        tp.status NOT IN ('obsolete', 'confirmed')
        AND tp.id NOT IN (234, 2, 3, 27)
    GROUP BY tp.id, k.id, o.name
)

SELECT
    `Typ`,
    `Event-Nr`,
    `Status`,
    `Art der Schulung`,
    `Erster Schulungstag`,
    `Zweiter Schulungstag`,
    `Schulungsort`,
    `Schulungsadresse`,
    `Teilnehmeranzahl - Schulung`,
    `Kitaname`,
    `Kitannummer`,
    `Anzahl pFK - Kita`,
    `Straße - Kita`,
    `PLZ - Kita`,
    `Träger`,
    `Multiplikator:In`,
    `Zugeordnete Manager der Einrichtung`,
    `Mailadresse der Manager`
FROM RankedData
ORDER BY
    `EarliestDate` ASC,
    `Kitaname` ASC,
    `Rank` ASC;
