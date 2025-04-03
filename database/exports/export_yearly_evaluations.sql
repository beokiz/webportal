WITH params AS (
    SELECT 2024 AS jahr
),

sprach_ampel AS (
    SELECT
        e.kita_id,
        e.is_daz,
        e.child_duration_in_kita,
        jt.color
    FROM
        beokiz_prod.evaluations e,
        JSON_TABLE(
            e.data, '$[*]'
            COLUMNS (
                abbreviation VARCHAR(10) PATH '$.abbreviation',
                color VARCHAR(10) PATH '$.color'
            )
        ) AS jt,
        params p
    WHERE
        YEAR(e.finished_at) = p.jahr
        AND e.age <= 4.5
        AND jt.abbreviation = 'S'
),

klassifiziert AS (
    SELECT
        kita_id,
        color AS farbe,
        CASE WHEN is_daz = 1 THEN 'deutsch' ELSE 'nicht_deutsch' END AS sprache,
        CASE
            WHEN child_duration_in_kita = 'upToOneYear' THEN '1-12'
            WHEN child_duration_in_kita = 'upToTwoYears' THEN '12-24'
            WHEN child_duration_in_kita = 'upToThreeYears' THEN '24-36'
            WHEN child_duration_in_kita = 'moreThanThreeYears' THEN 'mehr_als_36'
            ELSE 'unbekannt'
        END AS dauergruppe
    FROM sprach_ampel
),

ampel_aggregiert AS (
    SELECT
        kita_id,
        COUNT(CASE WHEN farbe = 'green' THEN 1 END) AS gruen_gesamt,
        COUNT(CASE WHEN farbe = 'yellow' THEN 1 END) AS gelb_gesamt,
        COUNT(CASE WHEN farbe = 'red' THEN 1 END) AS rot_gesamt,

        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' THEN 1 END) AS gruen_deutsch,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' THEN 1 END) AS gruen_nd,
        COUNT(CASE WHEN farbe = 'green' AND dauergruppe = '1-12' THEN 1 END) AS gruen_1_12,
        COUNT(CASE WHEN farbe = 'green' AND dauergruppe = '12-24' THEN 1 END) AS gruen_12_24,
        COUNT(CASE WHEN farbe = 'green' AND dauergruppe = '24-36' THEN 1 END) AS gruen_24_36,
        COUNT(CASE WHEN farbe = 'green' AND dauergruppe = 'mehr_als_36' THEN 1 END) AS gruen_mehr_36,

        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' THEN 1 END) AS gelb_deutsch,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' THEN 1 END) AS gelb_nd,
        COUNT(CASE WHEN farbe = 'yellow' AND dauergruppe = '1-12' THEN 1 END) AS gelb_1_12,
        COUNT(CASE WHEN farbe = 'yellow' AND dauergruppe = '12-24' THEN 1 END) AS gelb_12_24,
        COUNT(CASE WHEN farbe = 'yellow' AND dauergruppe = '24-36' THEN 1 END) AS gelb_24_36,
        COUNT(CASE WHEN farbe = 'yellow' AND dauergruppe = 'mehr_als_36' THEN 1 END) AS gelb_mehr_36,

        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' THEN 1 END) AS rot_deutsch,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' THEN 1 END) AS rot_nd,
        COUNT(CASE WHEN farbe = 'red' AND dauergruppe = '1-12' THEN 1 END) AS rot_1_12,
        COUNT(CASE WHEN farbe = 'red' AND dauergruppe = '12-24' THEN 1 END) AS rot_12_24,
        COUNT(CASE WHEN farbe = 'red' AND dauergruppe = '24-36' THEN 1 END) AS rot_24_36,
        COUNT(CASE WHEN farbe = 'red' AND dauergruppe = 'mehr_als_36' THEN 1 END) AS rot_mehr_36
    FROM klassifiziert
    GROUP BY kita_id
)

SELECT
    ye.`year` AS `Erhebungsjahr`,
    k.`name` AS `Name der Einrichtung`,

    -- Anschrift
    CASE
        WHEN (k.street IS NOT NULL AND k.street != '') AND (k.house_number IS NOT NULL AND k.house_number != '')
            THEN CONCAT(k.street, ' ', k.house_number)
        WHEN k.street IS NOT NULL AND k.street != '' THEN k.street
        WHEN k.house_number IS NOT NULL AND k.house_number != '' THEN k.house_number
        ELSE NULL
    END AS `Straße - Kita`,
    k.zip_code AS `PLZ - Kita`,
    k.`number` AS `Kitanummer`,

    -- Träger
    CASE
        WHEN (o.name IS NOT NULL AND o.name != '') AND (k.other_operator IS NOT NULL AND k.other_operator != '')
            THEN CONCAT(o.name, ', ', k.other_operator)
        WHEN o.name IS NOT NULL AND o.name != '' THEN o.name
        WHEN k.other_operator IS NOT NULL AND k.other_operator != '' THEN k.other_operator
        ELSE NULL
    END AS `Träger`,

    -- Statistik
    ye.children_4_born_per_year AS `Gesamtzahl der im Zeitraum geborenen Kinder 4,5`,
    ye.children_4_with_german_lang AS `Kinder mit deutscher Herkunftssprache`,
    ye.children_4_with_foreign_lang AS `Kinder mit nicht deutscher Herkunftssprache`,

    -- Fix für Evaluationen mit age <= 4.5
    (
        SELECT COUNT(e.id)
        FROM beokiz_prod.evaluations e, params p
        WHERE e.kita_id = ye.kita_id
          AND YEAR(e.finished_at) = p.jahr
          AND e.age <= 4.5
    ) AS `submitted Evaluation`,

    -- Ampelgrün
    COALESCE(aa.gruen_gesamt, 0) AS `4,5 Jährige Kinder mit grüner Ampel`,
    COALESCE(aa.gruen_deutsch, 0) AS `4,5, grün, deutsche Herkunftssprache`,
    COALESCE(aa.gruen_nd, 0) AS `4,5, grün, nicht deutsche Herkunftssprache`,
    COALESCE(aa.gruen_1_12, 0) AS `4,5, grün Ampel - 1-12 Monate`,
    COALESCE(aa.gruen_12_24, 0) AS `4,5, grün Ampel - 12-24 Monate`,
    COALESCE(aa.gruen_24_36, 0) AS `4,5, grün Ampel - 24-36 Monate`,
    COALESCE(aa.gruen_mehr_36, 0) AS `4,5, grün Ampel - mehr als 36 Monate`,

    -- Ampelgelb
    COALESCE(aa.gelb_gesamt, 0) AS `4,5 Jährige Kinder mit gelber Ampel`,
    COALESCE(aa.gelb_deutsch, 0) AS `4,5, gelb, deutsche Herkunftssprache`,
    COALESCE(aa.gelb_nd, 0) AS `4,5, gelb, nicht deutsche Herkunftssprache`,
    COALESCE(aa.gelb_1_12, 0) AS `4,5, gelbe Ampel - 1-12 Monate`,
    COALESCE(aa.gelb_12_24, 0) AS `4,5, gelbe Ampel - 12-24 Monate`,
    COALESCE(aa.gelb_24_36, 0) AS `4,5, gelbe Ampel - 24-36 Monate`,
    COALESCE(aa.gelb_mehr_36, 0) AS `4,5, gelbe Ampel - mehr als 36 Monate`,

    -- Ampelrot
    COALESCE(aa.rot_gesamt, 0) AS `4,5 Jährige Kinder mit roter Ampel`,
    COALESCE(aa.rot_deutsch, 0) AS `4,5, rote , deutsche Herkunftssprache`,
    COALESCE(aa.rot_nd, 0) AS `4,5, rot, nicht deutsche Herkunftssprache`,
    COALESCE(aa.rot_1_12, 0) AS `4,5, rot Ampel -  1-12 Monate`,
    COALESCE(aa.rot_12_24, 0) AS `4,5, rot Ampel - 12-24 Monate`,
    COALESCE(aa.rot_24_36, 0) AS `4,5, rot Ampel - 24-36 Monate`,
    COALESCE(aa.rot_mehr_36, 0) AS `4,5, rot  Ampel - mehr als 36 Monate`

FROM
    beokiz_prod.yearly_evaluations ye
JOIN
    beokiz_prod.kitas k ON ye.kita_id = k.id
LEFT JOIN
    beokiz.operators o ON k.operator_id = o.id
LEFT JOIN
    ampel_aggregiert aa ON aa.kita_id = k.id
JOIN
    params p ON TRUE
WHERE
    ye.`year` = p.jahr
ORDER BY
    k.name;
