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

        \App\Models\Subdomain::insert([
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Im Stehen',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Hüpfen',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Werfen/ Fangen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Hängen und Schaukeln',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Sprungarten',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Körperkoordination beim Turnen',
                'order'     => 6,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 1,
                'name'      => 'Zeitliche Steuerung von Bewegungen',
                'order'     => 7,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Gegenstände manipulieren',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Essen & Trinken',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Handgeschicklichkeit beim Essen und Trinken',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Mit Stiften umgehen',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'An- und Ausziehen',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Basteln und Werken',
                'order'     => 6,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Mit Nadel und Faden / Schnur umgehen',
                'order'     => 7,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Schrauben und Schließen',
                'order'     => 8,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 2,
                'name'      => 'Handgeschicklichkeit beim Umgang mit Spielmaterial',
                'order'     => 9,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Visuelle Aufmerksamkeit',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Akustische Aufmerksamkeit',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Konzentration',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Kategorisierung und geistige Flexibilität',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Visuelle Merkfähigkeit',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 3,
                'name'      => 'Akustische Merkfähigkeit',
                'order'     => 6,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 4,
                'name'      => 'Planen',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 4,
                'name'      => 'Darstellen und Symbolisieren',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 4,
                'name'      => 'Räumlich ordnen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 5,
                'name'      => 'Logik und Argumentation',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 5,
                'name'      => 'Planen und Problemlösen',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 5,
                'name'      => 'Experimentieren und Forschen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 6,
                'name'      => 'Worte',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 7,
                'name'      => 'Lesen',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 7,
                'name'      => 'Zahlenreihe bilden und abzählen',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 7,
                'name'      => 'Ordnen, Messen, Vergleichen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 8,
                'name'      => 'Besondere Wörter',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 8,
                'name'      => 'Sätze',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 9,
                'name'      => 'Wortschatz',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 9,
                'name'      => 'Grammatik auf Wortebene',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 9,
                'name'      => 'Grammatik auf Satzebene',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 9,
                'name'      => 'Pragmatik auf Sprachgebrauch',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 9,
                'name'      => 'Längere sprachliche Äußerungen verstehen und produzieren',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 10,
                'name'      => 'Fremde und vertraute Personen unterscheiden',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 10,
                'name'      => 'Kooperation im Alltag',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 10,
                'name'      => 'Gemeinsam Spielen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Theory of Mind',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Freundschaften bilden und halten',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Kooperation mit anderen Kindern',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Spielverhalten in der Gemeinschaft',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Umgang mit Erwachsenen (Eltern, Fachkräften)',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 11,
                'name'      => 'Sozialverhalten in Gruppen',
                'order'     => 6,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 12,
                'name'      => 'Impulse',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 12,
                'name'      => 'Ausscheidungen',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 12,
                'name'      => 'Umgang mit Zielfrustration',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 12,
                'name'      => 'Umgang mit sozialen Wartesituationen',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 12,
                'name'      => 'Selbstregulation eigener Gefühlszustände',
                'order'     => 5,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 13,
                'name'      => 'Komplexe Gefühle zeigen',
                'order'     => 1,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 13,
                'name'      => 'Über Gefühle reden',
                'order'     => 2,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 13,
                'name'      => 'Gefühle erkennen',
                'order'     => 3,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 13,
                'name'      => 'Gefühle unterscheiden',
                'order'     => 4,
            ]),
            array_merge($commonOptions, [
                'domain_id' => 13,
                'name'      => 'Austausch über Gefühle mit anderen Menschen',
                'order'     => 5,
            ]),
        ]);
    }
};
