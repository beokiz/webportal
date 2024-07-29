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
            "Baum", "Hocker", "Buch", "Bild", "Pflanze", "Tasse", "Tisch", "Pinsel", "Glas", "Ball", "Kissen", "Stift",
            "Tür", "Blatt", "Fenster", "Schale", "Teller", "Zaun", "Sand", "Holz",
        ];

        $colors = [
            "Grün", "Blau", "Orange", "Roten", "Gelb", "Violett", "Rosa", "Türkis", "Lavendelfarbenes", "Gold", "Silber",
            "Bronze", "Koralle", "Smaragd", "Sphir", "Rubin", "Bernstein", "Violett", "Magenta", "Mintgrün", "Marineblau",
            "Ocker", "Sepia", "Indigo",
        ];

        $nouns = [
            "Ananas", "Apfel",  "Artischocke", "Avocado", "Birne", "Banane", "Brokkoli", "Knoblauch", "Lauch", "Möhre",
            "Olive", "Paprika", "Pilz", "Rhabarber", "Salat", "Sellerie", "Spinat", "Tomate", "Traube", "Zucchini",
        ];

        $adjectiveIndex = array_rand($adjectives);
        $colorIndex     = array_rand($colors);
        $nounIndex      = array_rand($nouns);
        $nonceNumber    = rand(1, 100);

        return "{$adjectives[$adjectiveIndex]}_{$colors[$colorIndex]}_{$nouns[$nounIndex]}_{$nonceNumber}";
    }
}
