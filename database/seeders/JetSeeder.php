<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jet;

class JetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jets = [
            [
                'nom' => 'Gulfstream G650ER',
                'modele' => 'G650ER',
                'capacite' => 16,
                'image' => 'jets/J4tsEtwztjl7pi2V5ElGen5DY2fKLQASP0Ax9GP4.webp',
                'description' => 'Le nec plus ultra de l\'aviation d\'affaires, offrant une vitesse et une autonomie exceptionnelles avec un confort de cabine inégalé.',
                'prix' => 6500.00,
                'disponible' => true,
                'localisation' => 'Paris, France',
                'autonomie_km' => 13890,
                'categorie' => 'Ultra Long Range',
                'images' => json_encode([
                    'jets/jPfWum41et7QbFbHzbvsPN48NACfJMT2eajMheAw.jpg',
                    'jets/kEKhGWzAxTFFCrU6aZcZoBeDfMi2MA2TQlP2PgOg.jpg'
                ])
            ],
            [
                'nom' => 'Bombardier Global 7500',
                'modele' => 'Global 7500',
                'capacite' => 19,
                'image' => 'jets/jPfWum41et7QbFbHzbvsPN48NACfJMT2eajMheAw.jpg',
                'description' => 'Le plus grand et le plus long jet d\'affaires au monde, avec quatre zones de vie distinctes et une suite parentale avec douche.',
                'prix' => 7200.00,
                'disponible' => true,
                'localisation' => 'Nice, France',
                'autonomie_km' => 14260,
                'categorie' => 'Ultra Long Range',
                'images' => json_encode([
                    'jets/kf8opVqIx9nf0hbAXpxYrVcHMr7IT5NvBLQIh7eN.jpg',
                    'jets/kyxCnRM5QXvPcmrv76PVckYPwIDcu3ujyGMBieJn.jpg'
                ])
            ],
            [
                'nom' => 'Dassault Falcon 8X',
                'modele' => 'Falcon 8X',
                'capacite' => 14,
                'image' => 'jets/kEKhGWzAxTFFCrU6aZcZoBeDfMi2MA2TQlP2PgOg.jpg',
                'description' => 'L\'élégance française combinée à une performance trimoteur agile, capable d\'accéder à des aéroports difficiles.',
                'prix' => 5800.00,
                'disponible' => true,
                'localisation' => 'Genève, Suisse',
                'autonomie_km' => 11945,
                'categorie' => 'Large Cabin',
                'images' => json_encode([
                    'jets/lMmpBoNIoo2EAqiMptt7ahFNuePHvVDMUh5qAlMs.jpg'
                ])
            ],
            [
                'nom' => 'Embraer Lineage 1000E',
                'modele' => 'Lineage 1000E',
                'capacite' => 19,
                'image' => 'jets/kf8opVqIx9nf0hbAXpxYrVcHMr7IT5NvBLQIh7eN.jpg',
                'description' => 'Un appartement volant avec cinq zones de cabine luxueuses, idéal pour les voyages de groupe dans un confort absolu.',
                'prix' => 8500.00,
                'disponible' => true,
                'localisation' => 'Londres, UK',
                'autonomie_km' => 8519,
                'categorie' => 'Bizliner',
                'images' => json_encode([
                    'jets/MApKvGYMuTZ4431BFwpB43ftvTOv75NKeDFV6LTf.jpg'
                ])
            ],
            [
                'nom' => 'Cessna Citation Longitude',
                'modele' => 'Longitude',
                'capacite' => 12,
                'image' => 'jets/kyxCnRM5QXvPcmrv76PVckYPwIDcu3ujyGMBieJn.jpg',
                'description' => 'Le fleuron de la gamme Citation, offrant une cabine ultra-silencieuse et une efficacité opérationnelle remarquable.',
                'prix' => 4200.00,
                'disponible' => true,
                'localisation' => 'Marrakech, Maroc',
                'autonomie_km' => 6482,
                'categorie' => 'Super Midsize',
                'images' => json_encode([
                    'jets/MB0TrwxXYZYd3E6lF52wF6k4AzLsEkD3LJrT3eV7.jpg'
                ])
            ],
        ];

        foreach ($jets as $jetData) {
            Jet::updateOrCreate(
                ['nom' => $jetData['nom']],
                $jetData
            );
        }
    }
}
