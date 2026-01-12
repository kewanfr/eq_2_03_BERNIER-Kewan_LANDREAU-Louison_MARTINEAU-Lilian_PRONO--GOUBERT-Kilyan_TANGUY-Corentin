<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MigrateProductTags extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Lire tous les produits avec la colonne tags (CSV)
        $products = $db->table('products')->select('id, tags')->get()->getResultArray();

        foreach ($products as $p) {
            if (empty($p['tags'])) continue;
            $tags = array_unique(array_filter(array_map('trim', explode(',', $p['tags']))));
            foreach ($tags as $name) {
                if ($name === '') continue;
                // Insérer tag s'il n'existe pas
                $existing = $db->table('tags')->where('name', $name)->get()->getRowArray();
                if (!$existing) {
                    $db->table('tags')->insert(['name' => $name]);
                    $tagId = $db->insertID();
                } else {
                    $tagId = $existing['id'];
                }
                // Lier produit-tag (unique)
                $existsLink = $db->table('product_tags')->where(['product_id' => $p['id'], 'tag_id' => $tagId])->countAllResults();
                if ($existsLink == 0) {
                    $db->table('product_tags')->insert(['product_id' => $p['id'], 'tag_id' => $tagId]);
                }
            }
        }
    }

    public function down()
    {
        // Rien: on ne recrée pas le CSV
    }
}
