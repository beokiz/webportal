SELECT
    k.name AS `Einrichtungsname`,
    k.number AS `Einrichtungsnummer`,
    CASE
        WHEN (k.street IS NOT NULL AND k.street != '') AND (k.house_number IS NOT NULL AND k.house_number != '') THEN CONCAT(k.street, ' ', k.house_number)
        WHEN k.street IS NOT NULL AND k.street != '' THEN k.street
        WHEN k.house_number IS NOT NULL AND k.house_number != '' THEN k.house_number
        ELSE NULL
    END AS `Straße`,
    k.zip_code AS `PLZ`,
    k.city AS `Stadt`,
    CASE
        WHEN (o.name IS NOT NULL AND o.name != '') AND (k.other_operator IS NOT NULL AND k.other_operator != '') THEN CONCAT(o.name, ', ', k.other_operator)
        WHEN o.name IS NOT NULL AND o.name != '' THEN o.name
        WHEN k.other_operator IS NOT NULL AND k.other_operator != '' THEN k.other_operator
        ELSE NULL
    END AS `Träger/Sonstiger Träger`,
    GROUP_CONCAT(DISTINCT CONCAT(kita_user.first_name, ' ', kita_user.last_name) SEPARATOR ', ') AS `Manager der Einrichtung`,
    GROUP_CONCAT(DISTINCT kita_user.email SEPARATOR ', ') AS `Mailadressen der Manager`
FROM
    beokiz.kitas k
LEFT JOIN
    beokiz.operators o ON k.operator_id = o.id
LEFT JOIN
    beokiz.kita_has_users khu ON k.id = khu.kita_id
LEFT JOIN
    beokiz.users kita_user ON khu.user_id = kita_user.id
LEFT JOIN
    beokiz.model_has_roles mhr ON kita_user.id = mhr.model_id AND mhr.model_type = 'App\\Models\\User'
WHERE
    k.approved = 1 -- Nur zugelassene Einrichtungen
    AND mhr.role_id = 5 -- Nur Manager mit role_id = 5
GROUP BY
    k.id, o.name
ORDER BY
    k.name ASC;
