<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Produits - Cidrerie</title>
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
        
        .page-title {
            background: rgba(255,255,255,0.95);
            padding: 30px;
            margin: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            text-align: center;
        }
        
        .page-title h1 {
            color: #c41e3a;
            font-size: 2.5em;
            margin: 0;
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
    
    <div class="page-title">
        <h1>📦 Notre Catalogue de Produits</h1>
    </div>
    
    <div class="search-filters">
        <form class="search-bar" method="GET" action="/products">
            <input type="text" name="search" placeholder="Rechercher un produit..." value="<?= esc($currentSearch ?? '') ?>">
            <button type="submit">Rechercher</button>
        </form>
        
        <div class="filters">
            <div class="filter-group">
                <label>Catégories:</label>
                <a href="/products" class="filter-btn <?= empty($currentCategory) ? 'active' : '' ?>">Toutes</a>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="/products?category=<?= urlencode($cat['category']) ?>" class="filter-btn <?= ($currentCategory ?? '') === $cat['category'] ? 'active' : '' ?>">
                            <?= ucfirst(esc($cat['category'])) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="filter-group">
                <label>Tags:</label>
                <?php if (!empty($tags)): ?>
                    <?php foreach ($tags as $t): ?>
                        <a href="/products?tag=<?= urlencode($t) ?>" class="filter-btn <?= ($currentTag ?? '') === $t ? 'active' : '' ?>">
                            <?= esc($t) ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="products-container">
        <?php foreach ($products as $product): ?>
            <?= view("products", $product); ?>
        <?php endforeach; ?>
    </div>
</body>
</html>
