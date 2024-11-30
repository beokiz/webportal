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
    t.location AS `Schulungsort`,
	CONCAT(t.street, ' ', t.house_number, ', ', t.zip_code, ' ',t.city)  AS `Schulungsadresse`,
    t.participant_count AS `Teilnehmeranzahl - Schulung`,
    k.name AS `Kitaname`,
    k.number AS `Kitannummer`,
    k.num_pedagogical_staff AS `Anzahl pFK - Kita`,
    k.street AS `Straße - Kita`,
    k.zip_code AS `PLZ - Kita`,
    o.name AS `Trägername`,
    k.other_operator AS `sonstiger Träger`,
    GROUP_CONCAT(DISTINCT CONCAT(multiplikator_user.first_name, ' ', multiplikator_user.last_name) SEPARATOR ', ') AS `Multiplikator:In`,
    GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Zugeordnete Manager der Einrichtung`,
    GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadresse der Manager`
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
	tp.location AS `Schulungsort`,
	CONCAT(tp.street, ' ', tp.house_number, ', ', tp.zip_code, ' ',tp.city)  AS `Schulungsadresse`,
    tp.participant_count AS `Teilnehmeranzahl - Schulung`,
    k.name AS `Kitaname`,
    k.number AS `Kitannummer`,
    k.num_pedagogical_staff AS `Anzahl pFK - Kita`,
    k.street AS `Straße - Kita`,
    k.zip_code AS `PLZ - Kita`,
    o.name AS `Trägername`,
    k.other_operator AS `sonstiger Träger`,
    GROUP_CONCAT(DISTINCT CONCAT(multiplikator_user.first_name, ' ', multiplikator_user.last_name) SEPARATOR ', ') AS `Multiplikator:In`,
    GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Zugeordnete Manager der Einrichtung`,
    GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadresse der Manager`
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
GROUP BY tp.id, k.id, o.name
ORDER BY
    `Erster Schulungstag` ASC, `Typ` ASC, `Kitaname` ASC;
