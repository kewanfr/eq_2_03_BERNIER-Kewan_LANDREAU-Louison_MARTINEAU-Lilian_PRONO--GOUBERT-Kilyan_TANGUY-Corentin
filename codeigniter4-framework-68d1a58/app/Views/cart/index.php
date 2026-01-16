<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - TechnoPomme</title>
    <link rel="stylesheet" href="/assets/style/cart/index.css">
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
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock_quantity'] ?>" title="Stock disponible: <?= $item['stock_quantity'] ?>">
                                    <button type="submit" class="update-qty-btn">Mettre à jour</button>
                                </form>
                                <div style="font-size: 12px; color: <?= $item['stock_quantity'] < 5 ? '#dc3545' : '#666' ?>; margin-top: 5px;">
                                    Stock disponible: <?= $item['stock_quantity'] ?> unité(s)
                                </div>
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

    <script>
        // Validation du stock avant soumission
        document.querySelectorAll('.quantity-controls form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const input = this.querySelector('input[name="quantity"]');
                const quantity = parseInt(input.value);
                const maxStock = parseInt(input.max);
                
                if (quantity > maxStock) {
                    e.preventDefault();
                    alert(`Stock insuffisant ! Maximum disponible : ${maxStock} unité(s)`);
                    input.value = maxStock;
                    return false;
                }
                
                if (quantity < 1) {
                    e.preventDefault();
                    alert('La quantité doit être au moins 1');
                    input.value = 1;
                    return false;
                }
            });
        });
        
        // Empêcher de dépasser le max lors de la saisie
        document.querySelectorAll('input[name="quantity"]').forEach(input => {
            input.addEventListener('input', function() {
                const max = parseInt(this.max);
                const value = parseInt(this.value);
                
                if (value > max) {
                    this.value = max;
                }
                
                if (value < 1) {
                    this.value = 1;
                }
            });
        });
    </script>



<?= view('footer') ?>
</body>
</html>
