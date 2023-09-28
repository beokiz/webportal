<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

declare(strict_types = 1);

use DragonCode\LaravelActions\Action;

return new class () extends Action {
    /**
     * Run the actions.
     *
     * @return void
     */
    public function __invoke() : void
    {
        $createdAt = now();

        $commonOptions = [
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        \App\Models\Domain::insert([
            array_merge($commonOptions, [
                'name'                       => "Grobmotorik",
                'abbreviation'               => "GM",
                'order'                      => 1,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Feinmotorik",
                'abbreviation'               => "FM",
                'order'                      => 2,
                'age_2_red_threshold'        => 15,
                'age_2_red_threshold_daz'    => 15,
                'age_2_yellow_threshold'     => 25,
                'age_2_yellow_threshold_daz' => 25,
                'age_4_red_threshold'        => 20,
                'age_4_red_threshold_daz'    => 20,
                'age_4_yellow_threshold'     => 35,
                'age_4_yellow_threshold_daz' => 35,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Kognitive Grundfunktion",
                'abbreviation'               => "KG",
                'order'                      => 3,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Denken",
                'abbreviation'               => "D",
                'order'                      => 4,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Höhere kognitive Funktionen",
                'abbreviation'               => "HF",
                'order'                      => 5,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Schulische Vorläuferfähigkeiten",
                'abbreviation'               => "SV",
                'order'                      => 6,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Sprache 1",
                'abbreviation'               => "S1",
                'order'                      => 7,
                'age_2_red_threshold'        => 40,
                'age_2_red_threshold_daz'    => 40,
                'age_2_yellow_threshold'     => 60,
                'age_2_yellow_threshold_daz' => 60,
                'age_4_red_threshold'        => 12,
                'age_4_red_threshold_daz'    => 12,
                'age_4_yellow_threshold'     => 40,
                'age_4_yellow_threshold_daz' => 40,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Sprache 2",
                'abbreviation'               => "S2",
                'order'                      => 8,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Sprache 3",
                'abbreviation'               => "S3",
                'order'                      => 9,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Soziale Beziehungen",
                'abbreviation'               => "SB",
                'order'                      => 10,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Soziale Entwicklung",
                'abbreviation'               => "SE",
                'order'                      => 11,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Selbstregulation",
                'abbreviation'               => "SR",
                'order'                      => 12,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
            array_merge($commonOptions, [
                'name'                       => "Gefühle",
                'abbreviation'               => "G",
                'order'                      => 13,
                'age_2_red_threshold'        => 10,
                'age_2_red_threshold_daz'    => 10,
                'age_2_yellow_threshold'     => 20,
                'age_2_yellow_threshold_daz' => 20,
                'age_4_red_threshold'        => 15,
                'age_4_red_threshold_daz'    => 15,
                'age_4_yellow_threshold'     => 30,
                'age_4_yellow_threshold_daz' => 30,
            ]),
        ]);
    }
};
