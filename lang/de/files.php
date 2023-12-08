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
        'uuid_label'                        => "Einrichtung",
        'postal_label'                      => "Postleitzahl",
        'kita_label'                        => "Kita",
        'domain_sum_label'                  => "Summe_:domain",
        'domain_red_threshold_label'        => "Schwellenwert_:domain _Rot",
        'domain_yellow_threshold_label'     => "Schwellenwert_:domain _Gelb",
        'domain_red_threshold_daz_label'    => "Schwellenwert_:domain _Rot_DaZ",
        'domain_yellow_threshold_daz_label' => "Schwellenwert_:domain _Gelb_DaZ",
    ],

];
