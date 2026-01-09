<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Cidre Brut Traditionnel',
                'desc' => 'Cidre brut traditionnel élaboré avec nos pommes à cidre récoltées à maturité. Notes acidulées et fraîches.',
                'img_src' => '/assets/img/cidre-brut.jpg',
                'price' => 4.50,
                'quantity' => 120,
                'category' => 'Cidres',
                'tags' => 'brut,traditionnel,bio'
            ],
            [
                'name' => 'Cidre Doux',
                'desc' => 'Cidre doux fruité, légèrement sucré. Parfait pour l\'apéritif ou accompagner les desserts.',
                'img_src' => '/assets/img/cidre-doux.jpg',
                'price' => 4.50,
                'quantity' => 95,
                'category' => 'Cidres',
                'tags' => 'doux,fruité'
            ],
            [
                'name' => 'Jus de Pomme Artisanal',
                'desc' => 'Pur jus de pomme sans sucre ajouté, 100% fruits pressés. Goût authentique de nos vergers.',
                'img_src' => '/assets/img/jus-pomme.jpg',
                'price' => 3.20,
                'quantity' => 150,
                'category' => 'Jus',
                'tags' => 'jus,sans-alcool,bio'
            ],
            [
                'name' => 'Vinaigre de Cidre Bio',
                'desc' => 'Vinaigre de cidre bio non filtré, parfait pour vos salades et marinades.',
                'img_src' => '/assets/img/vinaigre.jpg',
                'price' => 5.80,
                'quantity' => 60,
                'category' => 'Vinaigres',
                'tags' => 'vinaigre,bio,artisanal'
            ],
            [
                'name' => 'Cidre Rosé',
                'desc' => 'Cidre rosé fruité élaboré avec des pommes rouges. Couleur rosée et arômes délicats.',
                'img_src' => '/assets/img/cidre-rose.jpg',
                'price' => 5.20,
                'quantity' => 80,
                'category' => 'Cidres',
                'tags' => 'rosé,fruité,premium'
            ],
            [
                'name' => 'Calvados 5 ans',
                'desc' => 'Eau-de-vie de cidre vieillie 5 ans en fût de chêne. Notes boisées et fruitées.',
                'img_src' => '/assets/img/calvados.jpg',
                'price' => 28.50,
                'quantity' => 25,
                'category' => 'Spiritueux',
                'tags' => 'calvados,premium,spiritueux'
            ],
            [
                'name' => 'Gelée de Pomme',
                'desc' => 'Gelée de pomme artisanale, préparée avec nos pommes du verger. Délicieuse sur des tartines.',
                'img_src' => '/assets/img/gelee.jpg',
                'price' => 4.80,
                'quantity' => 70,
                'category' => 'Confitures',
                'tags' => 'confiture,artisanal,sucré'
            ],
            [
                'name' => 'Coffret Découverte',
                'desc' => 'Coffret découverte avec 3 cidres différents (brut, doux, rosé) et 1 jus de pomme.',
                'img_src' => '/assets/img/coffret.jpg',
                'price' => 19.90,
                'quantity' => 45,
                'category' => 'Coffrets',
                'tags' => 'coffret,cadeau,découverte'
            ],
        ];

        foreach ($data as $product) {
            $this->db->table('products')->insert($product);
        }
    }
}
