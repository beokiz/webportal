<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

/**
 * Project helper functions
 */

if (!function_exists('generate_custom_unique_id')) {
    /**
     * @return string
     */
    function generate_custom_unique_id() : string
    {
        $adjectives = [
            "glückliches", "gemütliches", "fröhliches", "tapferes", "lebhaftes", "freudiges", "liebes", "sanftes",
            "helles", "freundliches", "ruhiges", "gnädiges", "strahlendes", "funkelndes", "sprudelndes", "heiteres",
            "entzückendes", "charmantes", "friedliches", "sonniges", "lebendiges", "witziges", "verspieltes", "weises",
            "nachdenkliches", "großzügiges", "fürsorgliches", "süßes",
        ];

        $colors = [
            "grünes", "blaues", "oranges", "rotes", "gelbes", "violettes", "rosanes", "türkises", "lavendelfarbenes",
            "goldenes", "silbernes", "bronzenes", "korallenrotes", "smaragdgrünes", "saphirblaues", "rubinrotes",
            "bernsteinfarbenes", "violettes", "magenta", "türkises", "pfirsichfarbenes", "mintgrünes", "marineblaues",
        ];

        $nouns = [
            "Eichhörnchen", "Häschen", "Schaf", "Küken", "Reh", "Fohlen", "Kälbchen", "Seepferdchen", "Lämmchen",
            "Schweinchen", "Kätzchen", "Entchen", "Pony", "Kamel", "Schmetterling", "Meerschweinchen", "Chamäleon",
            "Kaninchen", "Murmeltier", "Nilpferd", "Zebra", "Seelöwenbaby", "Delfinbaby", "Nashornbaby",
        ];

        $adjectiveIndex = array_rand($adjectives);
        $colorIndex     = array_rand($colors);
        $nounIndex      = array_rand($nouns);
        $nonceNumber    = rand(1, 100);

        return "{$adjectives[$adjectiveIndex]}_{$colors[$colorIndex]}_{$nouns[$nounIndex]}_{$nonceNumber}";
    }
}
