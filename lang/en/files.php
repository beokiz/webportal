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
        'created_date_line' => "Created at: :date",
        'copyright'         => "&#169; All Rights Reserved",
    ],

    'pdf' => [
        // Common
        'page_info' => "Page :current of :total",
    ],

    'img' => [
//        '' => "",
    ],

    'excel' => [
        'id_label'                          => "ID",
        'finished_at_label'                 => "Finished at",
        'age_label'                         => "Age",
        'is_daz_label'                      => "Is DaZ",
        'uuid_label'                        => "UUID",
        'postal_label'                      => "Postal",
        'kita_label'                        => "Kita",
        'domain_sum_label'                  => "Sum_:domain",
        'domain_red_threshold_label'        => "Threshold_:domain _Red",
        'domain_yellow_threshold_label'     => "Threshold_:domain _Yellow",
        'domain_red_threshold_daz_label'    => "Threshold_:domain _Red_DaZ",
        'domain_yellow_threshold_daz_label' => "Threshold_:domain _Yellow_DaZ",
    ],

];
