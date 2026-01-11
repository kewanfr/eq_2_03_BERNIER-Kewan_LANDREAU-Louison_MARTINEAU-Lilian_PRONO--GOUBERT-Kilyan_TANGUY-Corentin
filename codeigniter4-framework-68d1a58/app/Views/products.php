<div class="product-container">
    <?php
    $img = $img_src ?? '/assets/img/missing_product.jpg';
    if (!file_exists(FCPATH . $img)) {
        $img = "/assets/img/missing_product.jpg";
    }
    ?>
    <a href="/product/<?= $id ?>" style="text-decoration: none; color: inherit;">
        <img class='product_img' src='<?= $img ?>' alt='<?= esc($name ?? 'Produit') ?>'>
    </a>
    
    <div class='product_info'>
        <?php if (!empty($category)): ?>
            <div><span class="category-tag"><?= esc($category) ?></span></div>
        <?php endif; ?>
        
        <a href="/product/<?= $id ?>" style="text-decoration: none; color: inherit;">
            <span class='product_name'><?= esc($name ?? 'Nom du produit') ?></span>
        </a>
        
        <?php if (!empty($format)): ?>
            <span style="font-size: 0.9em; color: #2196f3; font-weight: bold;">📏 <?= esc($format) ?></span>
        <?php endif; ?>
        
        <span class='product_desc'><?= esc($desc ?? 'Description du produit') ?></span>
        
        <?php if (!empty($tags)): ?>
            <div style="margin: 10px 0; display: flex; flex-wrap: wrap; gap: 5px;">
                <?php foreach (explode(',', $tags) as $tag): ?>
                    <span class="product-tag"><?= esc(trim($tag)) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php
        $tvaRate = $tva_rate ?? 20.0;
        $priceTTC = $price ?? 0;
        $priceHT = $priceTTC / (1 + $tvaRate / 100);
        ?>
        
        <div class='price-qtt-container'>
            <div>
                <span class='product_price'><?= number_format($priceTTC, 2) ?> € TTC</span>
                <div style="font-size: 0.8em; color: #888;">
                    (<?= number_format($priceHT, 2) ?> € HT)
                </div>
            </div>
            <span class='product_qtt'>Stock: <?= esc($quantity ?? '0') ?></span>
        </div>
        
        <div style="margin-top: auto; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="number" class="qty-input" value="1" min="1" max="<?=$quantity?>" id="qty-<?=$id?>">
            <button onclick="addToCart(<?=$id?>, this)" class="add-to-cart-btn" style="flex: 1; min-width: 120px;">Ajouter au panier</button>
            <span class="cart-feedback" style="display: none; color: #28a745; font-weight: bold;">✓ Ajouté !</span>
        </div>
    </div>
</div>