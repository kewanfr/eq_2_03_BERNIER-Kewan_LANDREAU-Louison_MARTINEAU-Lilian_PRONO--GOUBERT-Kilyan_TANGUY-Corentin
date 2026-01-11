<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; padding-left: 0px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 20px; }
        .cart-empty { text-align: center; padding: 40px; color: #666; }
        .cart-item { display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #eee; align-items: center; }
        .cart-item img { width: 100px; height: 100px; object-fit: cover; border-radius: 4px; }
        .item-info { flex: 1; }
        .item-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .item-price { color: #007bff; font-size: 16px; margin-bottom: 10px; }
        .quantity-controls { display: flex; gap: 10px; align-items: center; }
        .quantity-controls form { display: inline; }
        .update-qty-btn { 
            padding: 8px 20px; 
            cursor: pointer; 
            border: none; 
            background: #8bc34a !important; 
            color: white !important;
            border-radius: 6px; 
            font-weight: bold;
            transition: all 0.2s;
        }
        .update-qty-btn:hover {
            background: #6fa02f !important;
        }
        .quantity-controls input { width: 70px; text-align: center; padding: 8px; border: 2px solid #ddd; border-radius: 4px; font-size: 16px; }
        .remove-btn { 
            color: white; 
            background: #dc3545;
            text-decoration: none; 
            padding: 10px 20px; 
            border: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .remove-btn:hover { 
            background: #c82333;
        }
        .cart-summary {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            position: relative;
        }
        
        .clear-cart-link {
            position: absolute;
            top: 20px;
            left: 0;
            color: #dc3545;
            text-decoration: none;
            font-size: 13px;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        
        .clear-cart-link:hover {
            opacity: 1;
            text-decoration: underline;
        }
        
        .cart-total {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            margin-bottom: 25px;
            margin-top: 30px;
        }
        
        .cart-actions { 
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
            align-items: center;
        }
        
        .cart-btn-secondary { 
            padding: 10px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            color: white;
            background: #6c757d;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .cart-btn-secondary:hover {
            background: #5a6268;
        }
        
        .cart-btn-primary { 
            padding: 12px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            background: #c41e3a;
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .cart-btn-primary:hover { 
            background: #a01828;
        }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <?= view('header'); ?>
    <div class="container">
        <a href="/" class="back-link">← Retour à l'accueil</a>
        
        <h1>Mon Panier</h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <div class="cart-empty">
                <p>Votre panier est vide</p>
                <a href="/" class="cart-btn-primary" style="margin-top: 20px; display: inline-block;">Continuer mes achats</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item">
                        <img src="<?= esc($item['img_src']) ?>" alt="<?= esc($item['name']) ?>">
                        <div class="item-info">
                            <div class="item-name"><?= esc($item['name']) ?></div>
                            <?php 
                            $tvaRate = $item['tva_rate'] ?? 20.0;
                            $priceHT = $item['unit_price'] / (1 + $tvaRate / 100);
                            $priceTTC = $item['unit_price'];
                            ?>
                            <div class="item-price">
                                <div style="font-size: 14px; color: #666;">
                                    Prix HT: <?= number_format($priceHT, 2) ?> € | 
                                    <strong style="color: #007bff;">TTC: <?= number_format($priceTTC, 2) ?> €</strong>
                                    <span style="font-size: 12px; color: #888;">(TVA <?= number_format($tvaRate, 1) ?>%)</span>
                                </div>
                            </div>
                            <div class="quantity-controls">
                                <form method="post" action="/cart/update">
                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                    <button type="submit" class="update-qty-btn">Mettre à jour</button>
                                </form>
                                <?php 
                                $subtotalTTC = $priceTTC * $item['quantity'];
                                $subtotalHT = $priceHT * $item['quantity'];
                                ?>
                                <span>
                                    Sous-total: <strong><?= number_format($subtotalTTC, 2) ?> € TTC</strong>
                                    <span style="font-size: 12px; color: #666;">(<?= number_format($subtotalHT, 2) ?> € HT)</span>
                                </span>
                            </div>
                        </div>
                        <a href="/cart/remove/<?= $item['product_id'] ?>" class="remove-btn" onclick="return confirm('Retirer cet article ?')">Retirer</a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <a href="/cart/clear" class="clear-cart-link" onclick="return confirm('Vider le panier ?')">🗑️ Vider le panier</a>
                
                <?php
                // Calcul du total HT
                $totalHT = 0;
                foreach ($items as $item) {
                    $tvaRate = $item['tva_rate'] ?? 20.0;
                    $priceHT = $item['unit_price'] / (1 + $tvaRate / 100);
                    $totalHT += $priceHT * $item['quantity'];
                }
                $totalTVA = $total - $totalHT;
                ?>
                
                <div class="cart-total">
                    <div style="font-size: 16px; color: #666; margin-bottom: 10px;">
                        Total HT: <?= number_format($totalHT, 2) ?> €<br>
                        TVA: <?= number_format($totalTVA, 2) ?> €
                    </div>
                    <div style="border-top: 2px solid #333; padding-top: 10px;">
                        <strong>Total TTC: <?= number_format($total, 2) ?> €</strong>
                    </div>
                    <br><small style="font-size: 14px; color: #666;">(<?= $itemCount ?> article<?= $itemCount > 1 ? 's' : '' ?>)</small>
                </div>
                <div class="cart-actions">
                    <a href="/" class="cart-btn-secondary">← Continuer mes achats</a>
                    <a href="/checkout" class="cart-btn-primary">COMMANDER →</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
