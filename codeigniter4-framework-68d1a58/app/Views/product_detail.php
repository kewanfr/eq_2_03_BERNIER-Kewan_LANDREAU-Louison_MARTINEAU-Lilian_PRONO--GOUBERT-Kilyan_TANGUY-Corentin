<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($product['name']) ?> - Cidrerie</title>
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
        
        .back-link {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.9);
            color: #8b4513;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .back-link:hover {
            background: #8bc34a;
            color: white;
        }
        
        .product-detail-container {
            max-width: 1200px;
            margin: 20px auto;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }
        
        .product-detail-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }
        
        .product-image-container {
            position: sticky;
            top: 20px;
        }
        
        .product-detail-image {
            width: 100%;
            height: 500px;
            object-fit: contain;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .product-info-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .product-title {
            font-size: 2.5em;
            color: #8b4513;
            margin: 0;
            line-height: 1.2;
        }
        
        .category-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #8bc34a 0%, #689f38 100%);
            color: white;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
            box-shadow: 0 2px 8px rgba(139,195,74,0.3);
        }
        
        .format-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            color: white;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9em;
            margin-left: 10px;
            box-shadow: 0 2px 8px rgba(33,150,243,0.3);
        }
        
        .product-description {
            font-size: 1.1em;
            color: #555;
            line-height: 1.8;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #8bc34a;
            border-radius: 8px;
        }
        
        .price-section {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-radius: 10px;
            border: 2px solid #ff9800;
        }
        
        .price-label {
            font-size: 1.2em;
            color: #8b4513;
            font-weight: bold;
        }
        
        .price-value {
            font-size: 2.5em;
            color: #c41e3a;
            font-weight: bold;
        }
        
        .stock-info {
            padding: 15px 20px;
            background: #e8f5e9;
            border-radius: 8px;
            border-left: 4px solid #4caf50;
        }
        
        .stock-info.low-stock {
            background: #fff3e0;
            border-left-color: #ff9800;
        }
        
        .stock-info.out-of-stock {
            background: #ffebee;
            border-left-color: #f44336;
        }
        
        .stock-text {
            font-size: 1.1em;
            color: #2e7d32;
            font-weight: bold;
        }
        
        .stock-text.low {
            color: #f57c00;
        }
        
        .stock-text.out {
            color: #c62828;
        }
        
        .tags-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .tag {
            padding: 8px 15px;
            background: #f5deb3;
            border: 2px solid #d2691e;
            border-radius: 20px;
            color: #8b4513;
            font-size: 0.9em;
            font-weight: 600;
        }
        
        .add-to-cart-section {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 25px;
            background: #f5f5f5;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .qty-input {
            width: 80px;
            padding: 12px;
            border: 2px solid #8bc34a;
            border-radius: 8px;
            font-size: 1.1em;
            text-align: center;
        }
        
        .add-to-cart-btn {
            flex: 1;
            padding: 15px 30px;
            background: linear-gradient(135deg, #c41e3a 0%, #a01828 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.1em;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(196,30,58,0.3);
        }
        
        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(196,30,58,0.4);
        }
        
        .add-to-cart-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .cart-feedback {
            color: #4caf50;
            font-weight: bold;
            font-size: 1.1em;
            display: none;
        }
        
        @media (max-width: 768px) {
            .product-detail-content {
                grid-template-columns: 1fr;
            }
            
            .product-image-container {
                position: relative;
                top: 0;
            }
            
            .product-title {
                font-size: 1.8em;
            }
            
            .price-value {
                font-size: 2em;
            }
            
            .product-detail-image {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <?= view('header') ?>
    
    <a href="/products" class="back-link">← Retour au catalogue</a>
    
    <div class="product-detail-container">
        <div class="product-detail-content">
            <div class="product-image-container">
                <?php
                $img = $product['img_src'] ?? '/assets/img/missing_product.jpg';
                if (!file_exists(FCPATH . $img)) {
                    $img = "/assets/img/missing_product.jpg";
                }
                ?>
                <img class='product-detail-image' src='<?= $img ?>' alt='<?= esc($product['name']) ?>'>
            </div>
            
            <div class="product-info-section">
                <div>
                    <h1 class="product-title"><?= esc($product['name']) ?></h1>
                    <div style="margin-top: 15px;">
                        <?php if (!empty($product['category'])): ?>
                            <span class="category-badge"><?= esc($product['category']) ?></span>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['format'])): ?>
                            <span class="format-badge"><?= esc($product['format']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="product-description">
                    <?= esc($product['desc']) ?>
                </div>
                
                <?php
                $tvaRate = $product['tva_rate'] ?? 20.0;
                $priceTTC = $product['price'];
                $priceHT = $priceTTC / (1 + $tvaRate / 100);
                ?>
                
                <div class="price-section">
                    <div style="flex: 1;">
                        <div style="font-size: 14px; color: #8b4513; margin-bottom: 5px;">Prix HT: <?= number_format($priceHT, 2, ',', ' ') ?> €</div>
                        <div style="display: flex; align-items: baseline; gap: 10px;">
                            <span class="price-label">Prix TTC:</span>
                            <span class="price-value"><?= number_format($priceTTC, 2, ',', ' ') ?> €</span>
                        </div>
                        <div style="font-size: 12px; color: #888; margin-top: 5px;">TVA <?= number_format($tvaRate, 1) ?>%</div>
                    </div>
                </div>
                
                <?php
                $quantity = $product['quantity'];
                $stockClass = $quantity > 10 ? '' : ($quantity > 0 ? 'low-stock' : 'out-of-stock');
                $stockTextClass = $quantity > 10 ? '' : ($quantity > 0 ? 'low' : 'out');
                ?>
                <div class="stock-info <?= $stockClass ?>">
                    <span class="stock-text <?= $stockTextClass ?>">
                        <?php if ($quantity > 10): ?>
                            ✓ En stock (<?= $quantity ?> disponibles)
                        <?php elseif ($quantity > 0): ?>
                            ⚠️ Stock limité (<?= $quantity ?> restants)
                        <?php else: ?>
                            ✗ Rupture de stock
                        <?php endif; ?>
                    </span>
                </div>
                
                <?php if (!empty($product['tags'])): ?>
                    <div>
                        <h3 style="color: #8b4513; margin-bottom: 10px;">Tags</h3>
                        <div class="tags-section" style="max-height: 90px; overflow-y: auto;">
                            <?php foreach (explode(',', $product['tags']) as $tag): ?>
                                <span class="tag"><?= esc(trim($tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="add-to-cart-section">
                    <label for="qty" style="font-weight: bold; color: #8b4513;">Quantité:</label>
                    <input 
                        type="number" 
                        class="qty-input" 
                        value="1" 
                        min="1" 
                        max="<?= $quantity ?>" 
                        id="qty-<?= $product['id'] ?>"
                        <?= $quantity <= 0 ? 'disabled' : '' ?>
                    >
                    <button 
                        onclick="addToCart(<?= $product['id'] ?>, this)" 
                        class="add-to-cart-btn"
                        <?= $quantity <= 0 ? 'disabled' : '' ?>
                    >
                        <?= $quantity > 0 ? 'Ajouter au panier' : 'Indisponible' ?>
                    </button>
                    <span class="cart-feedback">✓ Ajouté !</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        async function addToCart(productId, button) {
            const qtyInput = document.getElementById('qty-' + productId);
            const quantity = parseInt(qtyInput.value);
            const feedback = button.nextElementSibling;
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });
                
                const data = await response.json();
                
                if (data.redirect) {
                    alert(data.message || 'Vous devez être connecté pour ajouter au panier');
                    window.location.href = data.redirect;
                    return;
                }
                
                if (data.success) {
                    feedback.style.display = 'inline';
                    button.textContent = '✓ Ajouté !';
                    
                    // Met à jour le compteur du panier (si la fonction existe)
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                    
                    setTimeout(() => {
                        button.textContent = 'Ajouter au panier';
                        feedback.style.display = 'none';
                    }, 2000);
                } else {
                    alert(data.message || 'Erreur lors de l\'ajout au panier');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'ajout au panier');
            }
        }
    </script>
</body>
</html>
