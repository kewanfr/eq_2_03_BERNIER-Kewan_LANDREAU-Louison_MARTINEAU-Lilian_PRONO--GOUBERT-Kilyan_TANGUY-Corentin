<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cidrerie</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
        }
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(to bottom, #f5f5dc 0%, #d2b48c 100%);
            background-attachment: fixed;
        }
        
        .search-filters {
            background: rgba(255,255,255,0.9);
            padding: 25px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-bar input {
            flex: 1;
            padding: 12px 20px;
            border: 2px solid #8bc34a;
            border-radius: 25px;
            font-size: 16px;
        }
        
        .search-bar button {
            padding: 12px 30px;
            background: #c41e3a;
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .filter-group label {
            font-weight: bold;
            color: #8b4513;
        }
        
        .filter-btn {
            padding: 8px 15px;
            background: #f5deb3;
            border: 2px solid #d2691e;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            color: #8b4513;
            transition: all 0.3s;
        }
        
        .filter-btn:hover, .filter-btn.active {
            background: #8bc34a;
            border-color: #8bc34a;
            color: white;
        }
        
        .products-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            padding: 20px;
        }
        
        .product-container {
            background: white;
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .product-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(196,30,58,0.3);
        }
        
        .product_img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 12px;
        }
        
        .product_info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product_name {
            font-size: 1.2em;
            font-weight: bold;
            color: #8b4513;
            margin-bottom: 6px;
        }
        
        .product_desc {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            line-height: 1.4;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        
        .category-tag {
            display: inline-block;
            background: #8bc34a;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8em;
            margin: 5px 5px 5px 0;
        }
        
        .product-tag {
            display: inline-block;
            background: #f5deb3;
            color: #8b4513;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75em;
            margin: 2px;
            border: 1px solid #d2691e;
        }
        
        .price-qtt-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8px 0;
            padding: 8px 0;
            border-top: 2px solid #f5deb3;
        }
        
        .product_price {
            font-size: 1.3em;
            font-weight: bold;
            color: #c41e3a;
        }
        
        .product_qtt {
            font-size: 0.9em;
            color: #666;
        }
        
        .qty-input {
            width: 60px;
            padding: 8px;
            border: 2px solid #8bc34a;
            border-radius: 8px;
        }
        
        .add-to-cart-btn {
            padding: 10px 20px;
            background: #c41e3a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        
        .add-to-cart-btn:hover {
            background: #a01828;
        }
    </style>
</head>
<body>
    <?= view('header') ?>
    <?= view('cookies') ?>
    
    <!-- Section À propos -->
    <div style="background: rgba(255,255,255,0.95); padding: 40px; margin: 20px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); position: relative;">
        <!-- Drapeau breton -->
        <img src="/assets/img/gwenn-ha-du.svg" alt="Gwenn ha du" style="position: absolute; top: 20px; right: 20px; width: 70px; height: auto; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        
        <h2 style="color: #c41e3a; font-size: 2em; margin-bottom: 20px; text-align: center;">
            🍎 PommeHub - Plateforme de cidreries artisanales
        </h2>
        <div style="max-width: 900px; margin: 0 auto; line-height: 1.8; color: #333;">
            <p style="font-size: 1.1em; margin-bottom: 15px;">
                Bienvenue chez <strong>PommeHub</strong>, votre cidrerie artisanale de tradition bretonne.
                Nous sommes fiers de perpétuer un savoir-faire ancestral transmis de génération en génération à travers cette cidrerie familiale, ainsi que la plateforme PommeHub, regroupant plusieurs produits de cidreries artisanales de la région. 
                Filiale de <strong>Technochantier & CIE</strong>, nous cultivons nos vergers avec passion depuis plus de 30 ans 
                et produisons des cidres, jus de pomme et vinaigres d'exception.
            </p>
            <p style="font-size: 1.1em; margin-bottom: 25px;">
                Nos produits sont élaborés à partir de pommes 100% locales, récoltées à la main et 
                transformées selon des méthodes traditionnelles. Nous privilégions les variétés anciennes 
                et le respect des saisons pour vous offrir des saveurs authentiques.
            </p> 
            <p style="font-size: 0.95em; margin-bottom: 15px; color: #666; font-style: italic;">
                <strong>Technochantier & CIE</strong> est notre groupe familial qui regroupe également nos sociétés sœurs : <br />
                <a href="https://technochantier.kewan.fr/" target="_blank" style="text-decoration: none; font-weight: bold;">Technochantier</a> (équipements de chantier innovants), <a href="https://dassault.kewan.fr/" target="_blank" style="text-decoration: none; font-weight: bold;">Dassault Aviation</a>, 
                <strong>GlobalBeats</strong> (plateforme musicale), 
                <strong>Framework</strong> (ordinateurs modulaires et facilement réparables) et <strong>reparEco</strong> (formation de réparations d'équipements électroniques).
            </p>
            <p style="font-size: 1.1em; color: #8bc34a; font-weight: bold; text-align: center; margin-top: 20px;">
                🌱 Agriculture responsable • 🍏 Savoir-faire artisanal • 🏆 Qualité premium
            </p>
        </div>
    </div>
    
    <!-- Produits phares -->
    <div style="background: rgba(255,255,255,0.95); padding: 40px 20px; margin: 20px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <h2 style="color: #c41e3a; font-size: 2em; margin-bottom: 30px; text-align: center;">⭐ Nos Produits Phares</h2>
        <div class="products-container" style="margin-bottom: 30px;">
            <?php 
            $featuredProducts = array_slice($products, 0, 3); 
            foreach ($featuredProducts as $product): 
            ?>
                <?= view("products", $product); ?>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center;">
            <a href="/products" style="display: inline-block; padding: 15px 40px; background: #c41e3a; color: white; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 1.1em; transition: all 0.3s;" onmouseover="this.style.background='#a01828'" onmouseout="this.style.background='#c41e3a'">
                Voir tous nos produits →
            </a>
        </div>
    </div>
    
    <!-- Tous les produits sur la page d'accueil -->
    <div id="all-products" style="margin-top: 40px;">
        <h2 style="color: #8b4513; font-size: 1.8em; margin: 20px; text-align: center; background: rgba(255,255,255,0.8); padding: 20px; border-radius: 10px;">
            📦 Tous nos produits
        </h2>
        
        <div class="products-container">
            <?php foreach ($products as $product): ?>
                <?= view("products", $product); ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
