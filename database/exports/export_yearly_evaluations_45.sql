WITH params AS (
    SELECT
        2024 AS jahr,
        4.5 AS altersgrenze
),

sprach_ampel AS (
    SELECT
        e.kita_id,
        e.is_daz,
        e.integration_status,
        e.speech_therapy_status,
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
        AND e.age <= p.altersgrenze
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
        END AS dauergruppe,
        CASE WHEN integration_status = 1 THEN 1 ELSE 0 END AS hat_integration,
        CASE WHEN speech_therapy_status = 1 THEN 1 ELSE 0 END AS hat_logopaedie
    FROM sprach_ampel
),

ampel_aggregiert AS (
    SELECT
        kita_id,
        COUNT(CASE WHEN farbe = 'green' THEN 1 END) AS green_gesamt,
        COUNT(CASE WHEN farbe = 'yellow' THEN 1 END) AS yellow_gesamt,
        COUNT(CASE WHEN farbe = 'red' THEN 1 END) AS red_gesamt,

        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS green_deutsch_1_12_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS green_deutsch_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS green_deutsch_12_24_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS green_deutsch_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS green_deutsch_24_36_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS green_deutsch_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS green_deutsch_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS green_deutsch_mehr_36_logopaedie,

        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS green_nd_1_12_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS green_nd_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS green_nd_12_24_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS green_nd_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS green_nd_24_36_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS green_nd_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS green_nd_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS green_nd_mehr_36_logopaedie,

        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS green_deutsch_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS green_deutsch_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS green_deutsch_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS green_deutsch_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS green_deutsch_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS green_deutsch_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS green_deutsch_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS green_deutsch_mehr_36_nicht_logopaedie,

        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS green_nd_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS green_nd_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS green_nd_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS green_nd_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS green_nd_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS green_nd_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS green_nd_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'green' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS green_nd_mehr_36_nicht_logopaedie,

        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS yellow_deutsch_1_12_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS yellow_deutsch_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS yellow_deutsch_12_24_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS yellow_deutsch_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS yellow_deutsch_24_36_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS yellow_deutsch_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS yellow_deutsch_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS yellow_deutsch_mehr_36_logopaedie,

        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS yellow_nd_1_12_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS yellow_nd_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS yellow_nd_12_24_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS yellow_nd_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS yellow_nd_24_36_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS yellow_nd_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS yellow_nd_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS yellow_nd_mehr_36_logopaedie,

        -- GELB – DEUTSCH – NICHT-Integration & NICHT-Logopädie
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS yellow_deutsch_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS yellow_deutsch_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS yellow_deutsch_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS yellow_deutsch_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS yellow_deutsch_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS yellow_deutsch_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS yellow_deutsch_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS yellow_deutsch_mehr_36_nicht_logopaedie,

        -- GELB – NICHT_DEUTSCH – NICHT-Integration & NICHT-Logopädie
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS yellow_nd_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS yellow_nd_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS yellow_nd_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS yellow_nd_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS yellow_nd_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS yellow_nd_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS yellow_nd_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'yellow' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS yellow_nd_mehr_36_nicht_logopaedie,

        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS red_deutsch_1_12_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS red_deutsch_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS red_deutsch_12_24_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS red_deutsch_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS red_deutsch_24_36_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS red_deutsch_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS red_deutsch_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS red_deutsch_mehr_36_logopaedie,

        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 1 THEN 1 END) AS red_nd_1_12_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 1 THEN 1 END) AS red_nd_1_12_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 1 THEN 1 END) AS red_nd_12_24_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 1 THEN 1 END) AS red_nd_12_24_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 1 THEN 1 END) AS red_nd_24_36_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 1 THEN 1 END) AS red_nd_24_36_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 1 THEN 1 END) AS red_nd_mehr_36_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 1 THEN 1 END) AS red_nd_mehr_36_logopaedie,

        -- ROT – DEUTSCH – NICHT-Integration & NICHT-Logopädie
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS red_deutsch_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS red_deutsch_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS red_deutsch_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS red_deutsch_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS red_deutsch_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS red_deutsch_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS red_deutsch_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS red_deutsch_mehr_36_nicht_logopaedie,

        -- ROT – NICHT_DEUTSCH – NICHT-Integration & NICHT-Logopädie
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_integration = 0 THEN 1 END) AS red_nd_1_12_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '1-12' AND hat_logopaedie = 0 THEN 1 END) AS red_nd_1_12_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_integration = 0 THEN 1 END) AS red_nd_12_24_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '12-24' AND hat_logopaedie = 0 THEN 1 END) AS red_nd_12_24_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_integration = 0 THEN 1 END) AS red_nd_24_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = '24-36' AND hat_logopaedie = 0 THEN 1 END) AS red_nd_24_36_nicht_logopaedie,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_integration = 0 THEN 1 END) AS red_nd_mehr_36_nicht_integration,
        COUNT(CASE WHEN farbe = 'red' AND sprache = 'nicht_deutsch' AND dauergruppe = 'mehr_als_36' AND hat_logopaedie = 0 THEN 1 END) AS red_nd_mehr_36_nicht_logopaedie

    FROM klassifiziert
    GROUP BY kita_id
)

SELECT
    ye.`year` AS `Erhebungsjahr`,
    p.altersgrenze AS `Altersgruppe (max)`,
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
    ye.children_4_born_per_year AS `Gesamtzahl der im Zeitraum geborenen Kinder`,
    ye.children_4_with_german_lang AS `Kinder mit deutscher Herkunftssprache`,
    ye.children_4_with_foreign_lang AS `Kinder mit nicht deutscher Herkunftssprache`,

    (
        SELECT COUNT(e.id)
        FROM beokiz_prod.evaluations e
        WHERE e.kita_id = ye.kita_id
          AND YEAR(e.finished_at) = p.jahr
          AND e.age <= p.altersgrenze
    ) AS `submitted Evaluation`,

    -- Ampelgrün
    COALESCE(aa.green_gesamt, 0) AS `grün gesamt`,

    COALESCE(aa.green_deutsch_1_12_nicht_integration, 0) AS `grün, deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.green_deutsch_1_12_nicht_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.green_deutsch_12_24_nicht_integration, 0) AS `grün, deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.green_deutsch_12_24_nicht_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.green_deutsch_24_36_nicht_integration, 0) AS `grün, deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.green_deutsch_24_36_nicht_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.green_deutsch_mehr_36_nicht_logopaedie, 0) AS `grün, deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.green_deutsch_mehr_36_nicht_logopaedie, 0) AS `grün, deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`,
    COALESCE(aa.green_nd_1_12_nicht_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.green_nd_1_12_nicht_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.green_nd_12_24_nicht_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.green_nd_12_24_nicht_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.green_nd_24_36_nicht_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.green_nd_24_36_nicht_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.green_nd_mehr_36_nicht_integration, 0) AS `grün, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.green_nd_mehr_36_nicht_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`,

    COALESCE(aa.green_deutsch_1_12_integration, 0) AS `grün, deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.green_deutsch_1_12_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.green_deutsch_12_24_integration, 0) AS `grün, deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.green_deutsch_12_24_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.green_deutsch_24_36_integration, 0) AS `grün, deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.green_deutsch_24_36_logopaedie, 0) AS `grün, deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.green_deutsch_mehr_36_integration, 0) AS `grün, deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.green_deutsch_mehr_36_logopaedie, 0) AS `grün, deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,

    COALESCE(aa.green_nd_1_12_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.green_nd_1_12_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.green_nd_12_24_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.green_nd_12_24_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.green_nd_24_36_integration, 0) AS `grün, nicht deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.green_nd_24_36_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.green_nd_mehr_36_integration, 0) AS `grün, nicht deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.green_nd_mehr_36_logopaedie, 0) AS `grün, nicht deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,


    -- Ampelyellow
    COALESCE(aa.yellow_gesamt, 0) AS `gelb gesamt`,
    COALESCE(aa.yellow_deutsch_1_12_integration, 0) AS `gelb, deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.yellow_deutsch_1_12_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.yellow_deutsch_12_24_integration, 0) AS `gelb, deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.yellow_deutsch_12_24_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.yellow_deutsch_24_36_integration, 0) AS `gelb, deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.yellow_deutsch_24_36_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.yellow_deutsch_mehr_36_integration, 0) AS `gelb, deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.yellow_deutsch_mehr_36_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,

    COALESCE(aa.yellow_nd_1_12_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.yellow_nd_1_12_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.yellow_nd_12_24_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.yellow_nd_12_24_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.yellow_nd_24_36_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.yellow_nd_24_36_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.yellow_nd_mehr_36_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.yellow_nd_mehr_36_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,

    COALESCE(aa.yellow_deutsch_1_12_nicht_integration, 0) AS `gelb, deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.yellow_deutsch_1_12_nicht_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.yellow_deutsch_12_24_nicht_integration, 0) AS `gelb, deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.yellow_deutsch_12_24_nicht_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.yellow_deutsch_24_36_nicht_integration, 0) AS `gelb, deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.yellow_deutsch_24_36_nicht_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.yellow_deutsch_mehr_36_nicht_integration, 0) AS `gelb, deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.yellow_deutsch_mehr_36_nicht_logopaedie, 0) AS `gelb, deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`,

    COALESCE(aa.yellow_nd_1_12_nicht_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.yellow_nd_1_12_nicht_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.yellow_nd_12_24_nicht_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.yellow_nd_12_24_nicht_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.yellow_nd_24_36_nicht_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.yellow_nd_24_36_nicht_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.yellow_nd_mehr_36_nicht_integration, 0) AS `gelb, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.yellow_nd_mehr_36_nicht_logopaedie, 0) AS `gelb, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`,

    -- Ampelred
    COALESCE(aa.red_gesamt, 0) AS `rot gesamt`,
    COALESCE(aa.red_deutsch_1_12_integration, 0) AS `rot, deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.red_deutsch_1_12_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.red_deutsch_12_24_integration, 0) AS `rot, deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.red_deutsch_12_24_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.red_deutsch_24_36_integration, 0) AS `rot, deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.red_deutsch_24_36_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.red_deutsch_mehr_36_integration, 0) AS `rot, deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.red_deutsch_mehr_36_logopaedie, 0) AS `rot, deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,

    COALESCE(aa.red_nd_1_12_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 1-12, Integration`,
    COALESCE(aa.red_nd_1_12_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 1-12, Logopädie`,
    COALESCE(aa.red_nd_12_24_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 12-24, Integration`,
    COALESCE(aa.red_nd_12_24_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 12-24, Logopädie`,
    COALESCE(aa.red_nd_24_36_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 24-36, Integration`,
    COALESCE(aa.red_nd_24_36_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 24-36, Logopädie`,
    COALESCE(aa.red_nd_mehr_36_integration, 0) AS `rot, nicht deutsche Herkunftssprache, mehr als 36 Monate, Integration`,
    COALESCE(aa.red_nd_mehr_36_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, mehr als 36 Monate, Logopädie`,

    COALESCE(aa.red_deutsch_1_12_nicht_integration, 0) AS `rot, deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.red_deutsch_1_12_nicht_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.red_deutsch_12_24_nicht_integration, 0) AS `rot, deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.red_deutsch_12_24_nicht_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.red_deutsch_24_36_nicht_integration, 0) AS `rot, deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.red_deutsch_24_36_nicht_logopaedie, 0) AS `rot, deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.red_deutsch_mehr_36_nicht_integration, 0) AS `rot, deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.red_deutsch_mehr_36_nicht_logopaedie, 0) AS `rot, deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`,

    COALESCE(aa.red_nd_1_12_nicht_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 1-12, keine Integration`,
    COALESCE(aa.red_nd_1_12_nicht_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 1-12, keine Logopädie`,
    COALESCE(aa.red_nd_12_24_nicht_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 12-24, keine Integration`,
    COALESCE(aa.red_nd_12_24_nicht_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 12-24, keine Logopädie`,
    COALESCE(aa.red_nd_24_36_nicht_integration, 0) AS `rot, nicht deutsche Herkunftssprache, 24-36, keine Integration`,
    COALESCE(aa.red_nd_24_36_nicht_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, 24-36, keine Logopädie`,
    COALESCE(aa.red_nd_mehr_36_nicht_integration, 0) AS `rot, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Integration`,
    COALESCE(aa.red_nd_mehr_36_nicht_logopaedie, 0) AS `rot, nicht deutsche Herkunftssprache, mehr als 36 Monate, keine Logopädie`
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
