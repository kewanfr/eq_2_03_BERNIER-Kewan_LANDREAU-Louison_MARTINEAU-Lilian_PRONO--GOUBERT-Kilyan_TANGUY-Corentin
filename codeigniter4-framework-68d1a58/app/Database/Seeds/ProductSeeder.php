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
                'img_src' => '/assets/img/products/cidre-brut.webp',
                'price' => 4.50,
                'quantity' => 120,
                'category' => 'Cidres',
                'tags' => 'brut,traditionnel,bio'
            ],
            [
                'name' => 'Cidre Doux',
                'desc' => 'Cidre doux fruité, légèrement sucré. Parfait pour l\'apéritif ou accompagner les desserts.',
                'img_src' => '/assets/img/products/cidre-doux.webp',
                'price' => 4.50,
                'quantity' => 95,
                'category' => 'Cidres',
                'tags' => 'doux,fruité'
            ],
            [
                'name' => 'Jus de Pomme Artisanal',
                'desc' => 'Pur jus de pomme sans sucre ajouté, 100% fruits pressés. Goût authentique de nos vergers.',
                'img_src' => '/assets/img/products/jus-pomme.webp',
                'price' => 3.20,
                'quantity' => 150,
                'category' => 'Jus',
                'tags' => 'jus,sans-alcool,bio'
            ],
            [
                'name' => 'Vinaigre de Cidre Bio',
                'desc' => 'Vinaigre de cidre bio non filtré, parfait pour vos salades et marinades.',
                'img_src' => '/assets/img/products/vinaigre.webp',
                'price' => 5.80,
                'quantity' => 60,
                'category' => 'Vinaigres',
                'tags' => 'vinaigre,bio,artisanal'
            ],
            [
                'name' => 'Cidre Rosé',
                'desc' => 'Cidre rosé fruité élaboré avec des pommes rouges. Couleur rosée et arômes délicats.',
                'img_src' => '/assets/img/products/cidre-rose.webp',
                'price' => 5.20,
                'quantity' => 80,
                'category' => 'Cidres',
                'tags' => 'rosé,fruité,premium'
            ],
            [
                'name' => 'Calvados 5 ans',
                'desc' => 'Eau-de-vie de cidre vieillie 5 ans en fût de chêne. Notes boisées et fruitées.',
                'img_src' => '/assets/img/products/calvados-5ans.webp',
                'price' => 28.50,
                'quantity' => 25,
                'category' => 'Spiritueux',
                'tags' => 'calvados,premium,spiritueux'
            ],
            [
                'name' => 'Gelée de Pomme',
                'desc' => 'Gelée de pomme artisanale, préparée avec nos pommes du verger. Délicieuse sur des tartines.',
                'img_src' => '/assets/img/products/gelee-citron.webp',
                'price' => 4.80,
                'quantity' => 70,
                'category' => 'Confitures',
                'tags' => 'confiture,artisanal,sucré'
            ],
            [
                'name' => 'Coffret Découverte',
                'desc' => 'Coffret découverte avec 3 cidres différents (brut, doux, rosé) et 1 jus de pomme.',
                'img_src' => '/assets/img/products/coffret-premium.webp',
                'price' => 19.90,
                'quantity' => 45,
                'category' => 'Coffrets',
                'tags' => 'coffret,cadeau,découverte'
            ],
            [
                'name' => 'Cidre Épicée Cannelle',
                'desc' => 'Cidre brut aromatisé aux épices, cannelle douce et notes chaudes. Idéal en hiver.',
                'img_src' => '/assets/img/products/cidre-epice.webp',
                'price' => 5.50,
                'quantity' => 85,
                'category' => 'Cidres',
                'tags' => 'épicé,premium,hivernal'
            ],
            [
                'name' => 'Cidre Pétillante',
                'desc' => 'Cidre légèrement pétillante aux bulles fines. Fraîcheur et légèreté garanties.',
                'img_src' => '/assets/img/products/cidre-petillante.webp',
                'price' => 6.50,
                'quantity' => 110,
                'category' => 'Cidres',
                'tags' => 'pétillant,frais,premium'
            ],
            [
                'name' => 'Cidre Sec Verger',
                'desc' => 'Cidre sec élaboré avec une sélection de pommes amères. Goût complexe et raffiné.',
                'img_src' => '/assets/img/products/cidre-sec.webp',
                'price' => 7.20,
                'quantity' => 65,
                'category' => 'Cidres',
                'tags' => 'sec,premium,artisanal'
            ],
            [
                'name' => 'Jus de Pomme Miel',
                'desc' => 'Jus de pomme enrichi au miel local. Saveur naturelle douce et réconfortante.',
                'img_src' => '/assets/img/products/jus-miel.webp',
                'price' => 4.50,
                'quantity' => 140,
                'category' => 'Jus',
                'tags' => 'jus,miel,sans-alcool'
            ],
            [
                'name' => 'Jus de Pomme Raisin',
                'desc' => 'Mélange fruité de jus de pomme et raisin. Goût sucré naturel et délicieux.',
                'img_src' => '/assets/img/products/jus-raisin.webp',
                'price' => 4.80,
                'quantity' => 130,
                'category' => 'Jus',
                'tags' => 'jus,fruité,sans-alcool'
            ],
            [
                'name' => 'Jus de Pomme Poire',
                'desc' => 'Jus bio 100% naturel pomme-poire. Saveur douce et délicate pour toute la famille.',
                'img_src' => '/assets/img/products/jus-poire.webp',
                'price' => 4.80,
                'quantity' => 125,
                'category' => 'Jus',
                'tags' => 'jus,poire,bio,sans-alcool'
            ],
            [
                'name' => 'Vinaigre Balsamique Cidre',
                'desc' => 'Vinaigre de cidre vieilli avec notes balsamiques. Excellent pour les vinaigrettes.',
                'img_src' => '/assets/img/products/vinaigre-balsamique.webp',
                'price' => 7.50,
                'quantity' => 50,
                'category' => 'Vinaigres',
                'tags' => 'vinaigre,premium,gastronomie'
            ],
            [
                'name' => 'Vinaigre Cidre Herbes',
                'desc' => 'Vinaigre de cidre infusé avec herbes aromatiques (thym, romarin). Saveur délicate.',
                'img_src' => '/assets/img/products/vinaigre-herbes.webp',
                'price' => 6.80,
                'quantity' => 55,
                'category' => 'Vinaigres',
                'tags' => 'vinaigre,herbes,artisanal'
            ],
            [
                'name' => 'Calvados 10 ans',
                'desc' => 'Eau-de-vie premium vieillie 10 ans. Arômes profonds, boisés et fruités.',
                'img_src' => '/assets/img/products/calvados-10ans.webp',
                'price' => 45.00,
                'quantity' => 15,
                'category' => 'Spiritueux',
                'tags' => 'calvados,premium,spiritueux,luxe'
            ],
            [
                'name' => 'Calvados VSOP 7 ans',
                'desc' => 'Calvados VSOP vieilli 7 ans en fût. Qualité supérieure et goût raffiné.',
                'img_src' => '/assets/img/products/calvados-10ans.webp',
                'price' => 35.00,
                'quantity' => 20,
                'category' => 'Spiritueux',
                'tags' => 'calvados,premium,spiritueux'
            ],
            [
                'name' => 'Confiture Pomme Cannelle',
                'desc' => 'Confiture artisanale pomme-cannelle. Saveurs chaudes et réconfortantes.',
                'img_src' => '/assets/img/products/confiture-cannelle.webp',
                'price' => 5.50,
                'quantity' => 80,
                'category' => 'Confitures',
                'tags' => 'confiture,épicée,artisanal'
            ],
            [
                'name' => 'Confiture Pomme Gingembre',
                'desc' => 'Confiture pomme-gingembre fait maison. Piquant et aromatique pour vos tartines.',
                'img_src' => '/assets/img/products/confiture-gingembre.webp',
                'price' => 5.50,
                'quantity' => 75,
                'category' => 'Confitures',
                'tags' => 'confiture,gingembre,artisanal'
            ],
            [
                'name' => 'Confiture Pomme Vanille',
                'desc' => 'Confiture délicate pomme-vanille. Douceur et élégance en pot.',
                'img_src' => '/assets/img/products/confiture-vanille.webp',
                'price' => 6.80,
                'quantity' => 70,
                'category' => 'Confitures',
                'tags' => 'confiture,vanille,premium,artisanal'
            ],
            [
                'name' => 'Gelée Pomme Citron',
                'desc' => 'Gelée légère pomme-citron. Acidité et fraîcheur à chaque bouchée.',
                'img_src' => '/assets/img/products/gelee-citron.webp',
                'price' => 5.20,
                'quantity' => 85,
                'category' => 'Confitures',
                'tags' => 'confiture,citron,frais'
            ],
            [
                'name' => 'Pâte de Fruit Pomme',
                'desc' => 'Pâte de fruit artisanale pomme. Texture fondante et goût intense.',
                'img_src' => '/assets/img/products/pate-fruit.webp',
                'price' => 4.50,
                'quantity' => 100,
                'category' => 'Confitures',
                'tags' => 'pâte,fruit,confiserie,artisanal'
            ],
            [
                'name' => 'Coffret Premium',
                'desc' => 'Coffret dégustation avec cidre premium.',
                'img_src' => '/assets/img/products/coffret-premium.webp',
                'price' => 42.50,
                'quantity' => 20,
                'category' => 'Coffrets',
                'tags' => 'coffret,cadeau,premium,luxe'
            ],
            [
                'name' => 'Coffret Tempete de l\'ouest',
                'desc' => 'Coffret avec confit, cidre et autres délices de l\'ouest.',
                'img_src' => '/assets/img/products/coffret-tempete.webp',
                'price' => 24.90,
                'quantity' => 35,
                'category' => 'Coffrets',
                'tags' => 'coffret,cadeau,gourmet,conserves'
            ],
            [
                'name' => 'Coffret Famille Jus',
                'desc' => 'Coffret 6 bouteilles jus de pomme variés. Parfait pour les familles.',
                'img_src' => '/assets/img/products/coffret-famille.webp',
                'price' => 22.50,
                'quantity' => 40,
                'category' => 'Coffrets',
                'tags' => 'coffret,jus,famille,sans-alcool'
            ],
            [
                'name' => 'Cidre Pêche',
                'desc' => 'Cidre fruité aromatisé à la pêche. Doux et savoureux pour l\'été.',
                'img_src' => '/assets/img/products/cidre-peche.webp',
                'price' => 5.50,
                'quantity' => 90,
                'category' => 'Cidres',
                'tags' => 'cidre,pêche,fruité,été'
            ],
            [
                'name' => 'Cidre Framboise',
                'desc' => 'Cidre de jeunesse avec arômes de framboise. Fruité et équilibré.',
                'img_src' => '/assets/img/products/cidre-framboise.webp',
                'price' => 6.00,
                'quantity' => 75,
                'category' => 'Cidres',
                'tags' => 'cidre,framboise,fruité,premium'
            ],
        ];

        foreach ($data as $product) {
            $this->db->table('products')->insert($product);
        }
    }
}

