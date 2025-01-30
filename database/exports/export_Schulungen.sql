SELECT
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
    CASE
        WHEN (t.street IS NOT NULL AND t.street != '') AND (t.house_number IS NOT NULL AND t.house_number != '') THEN CONCAT(t.street, ' ', t.house_number, ', ', t.zip_code, ' ', t.city)
        WHEN t.street IS NOT NULL AND t.street != '' THEN CONCAT(t.street, ', ', t.zip_code, ' ', t.city)
        WHEN t.house_number IS NOT NULL AND t.house_number != '' THEN CONCAT(t.house_number, ', ', t.zip_code, ' ', t.city)
        ELSE NULL
    END AS `Schulungsadresse`,
    t.participant_count AS `Teilnehmeranzahl - Schulung`,
    COALESCE(k.name, 'Keine Kita zugeordnet') AS `Kitaname`,
    COALESCE(k.number, 'Keine Nummer') AS `Kitannummer`,
    COALESCE(k.num_pedagogical_staff, 0) AS `Anzahl pFK - Kita`,
    CASE
        WHEN (k.street IS NOT NULL AND k.street != '') AND (k.house_number IS NOT NULL AND k.house_number != '') THEN CONCAT(k.street, ' ', k.house_number)
        WHEN k.street IS NOT NULL AND k.street != '' THEN k.street
        WHEN k.house_number IS NOT NULL AND k.house_number != '' THEN k.house_number
        ELSE 'Keine Adresse'
    END AS `Straße - Kita`,
    COALESCE(k.zip_code, 'Keine PLZ') AS `PLZ - Kita`,
    CASE
        WHEN (o.name IS NOT NULL AND o.name != '') AND (k.other_operator IS NOT NULL AND k.other_operator != '') THEN CONCAT(o.name, ', ', k.other_operator)
        WHEN o.name IS NOT NULL AND o.name != '' THEN o.name
        WHEN k.other_operator IS NOT NULL AND k.other_operator != '' THEN k.other_operator
        ELSE 'Kein Träger'
    END AS `Träger`,
    GROUP_CONCAT(DISTINCT CONCAT(COALESCE(multiplikator_user.first_name, ''), ' ', COALESCE(multiplikator_user.last_name, '')) SEPARATOR ', ') AS `Multiplikator:In`,
    GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Zugeordnete Manager der Einrichtung`,
    GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadresse der Manager`
FROM
    beokiz.trainings t
LEFT JOIN
    beokiz.kita_has_trainings kht ON t.id = kht.training_id
LEFT JOIN
    beokiz.kitas k ON kht.kita_id = k.id
LEFT JOIN
    beokiz.operators o ON k.operator_id = o.id
LEFT JOIN
    beokiz.kita_has_users khu ON k.id = khu.kita_id
LEFT JOIN
    beokiz.users kita_user ON khu.user_id = kita_user.id
LEFT JOIN
    beokiz.users multiplikator_user ON t.multi_id = multiplikator_user.id
LEFT JOIN
    beokiz.model_has_roles mhr ON kita_user.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
WHERE
    t.id NOT IN (1, 2, 3, 27) -- Exkludiere Schulungen mit diesen IDs
    AND (k.id IS NULL OR k.id NOT IN (1,2,21,109,110,111,134,199,220,221,245,249)) -- Berücksichtigt auch NULL-Werte für Schulungen ohne Kita
    AND (mhr.role_id = 5 OR mhr.role_id IS NULL) -- Falls keine Kita-Manager zugeordnet sind
GROUP BY
    t.id, k.id, o.name
ORDER BY t.first_date ASC;
