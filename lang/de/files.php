<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

return [

    /*
    |--------------------------------------------------------------------------
    | PDF & Image Reports Language Lines
    |--------------------------------------------------------------------------
    */

    'common' => [
        'created_date_line' => "Eingereicht am: :date",
        'copyright'         => "&#169; Alle Rechte vorbehalten",
    ],

    'pdf' => [
        // Common
        'page_info' => "Seite :current von :total",
    ],

    'img' => [
//        '' => "",
    ],

    'excel' => [
        'id_label'                          => "ID",
        'finished_at_label'                 => "Abgegeben am",
        'age_label'                         => "Altersgruppe",
        'is_daz_label'                      => "DaZ",
        'integration_status'                => "Integrationsstatus",
        'speech_therapy_status'             => "in logopädische Behandlung",
        'postal_label'                      => "Postleitzahl",
        'uuid_label'                        => "Einrichtung",
        'kita_label'                        => "KiTa",
        'child_duration_in_kita_label'      => "Zeitraum in der KiTa",
        'up_to_one_year_label'              => "1 - 12 Monate",
        'up_to_two_years_label'             => "12 - 24 Monate",
        'up_to_three_years_label'           => "24 - 36 Monate",
        'more_than_three_years_label'       => "Länger als 36 Monate",
        'domain_sum_label'                  => "Summe_:domain",
        'domain_red_threshold_label'        => "Schwellenwert_:domain _Rot",
        'domain_yellow_threshold_label'     => "Schwellenwert_:domain _Gelb",
        'domain_red_threshold_daz_label'    => "Schwellenwert_:domain _Rot_DaZ",
        'domain_yellow_threshold_daz_label' => "Schwellenwert_:domain _Gelb_DaZ",
    ],

];
