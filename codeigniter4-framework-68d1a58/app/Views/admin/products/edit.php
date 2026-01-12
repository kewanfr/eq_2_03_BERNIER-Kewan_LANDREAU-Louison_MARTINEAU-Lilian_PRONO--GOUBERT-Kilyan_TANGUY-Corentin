<?= view('admin/header') ?>

<h2 class="admin-title">Modifier le produit</h2>

<div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 700px;">
    <form method="post" action="/admin/products/update/<?= $product['id'] ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="name" value="<?= esc($product['name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="desc" required style="min-height: 100px; resize: vertical;"><?= esc($product['desc']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Image actuelle</label>
            <?php if (!empty($product['img_src'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="<?= esc($product['img_src']) ?>" alt="Image actuelle" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
            <?php endif; ?>
            <label>Changer l'image</label>
            <input type="file" name="image" accept="image/*" style="padding: 10px; border: 2px dashed #ddd; border-radius: 8px; background: #f9f9f9;">
            <small style="color: #666; font-size: 0.9em; display: block; margin-top: 5px;">Formats acceptés: JPG, PNG, GIF, WEBP. Laissez vide pour conserver l'image actuelle.</small>
        </div>
        
        <div class="form-group">
            <label>Catégorie</label>
            <select name="category">
                <option value="">-- Sélectionner --</option>
                <option value="cidres" <?= ($product['category'] ?? '') === 'cidres' ? 'selected' : '' ?>>Cidres</option>
                <option value="jus" <?= ($product['category'] ?? '') === 'jus' ? 'selected' : '' ?>>Jus</option>
                <option value="vinaigre" <?= ($product['category'] ?? '') === 'vinaigre' ? 'selected' : '' ?>>Vinaigre</option>
                <option value="accessoires" <?= ($product['category'] ?? '') === 'accessoires' ? 'selected' : '' ?>>Accessoires</option>
                <option value="autre" <?= ($product['category'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Tags (séparés par des virgules)</label>
            <input type="text" name="tags" value="<?= esc($product['tags'] ?? '') ?>" placeholder="bio, tradition, brut...">
            <small style="color: #666; font-size: 0.9em;">Exemples: bio, tradition, monovariétal, doux, brut, artisanal</small>
        </div>
        
        <div class="form-group">
            <label>Prix (€)</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Quantité en stock</label>
            <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
        </div>
        
        <div style="margin-top: 25px;">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/products" class="btn btn-secondary" style="background: #6c757d; margin-left: 10px;">Annuler</a>
        </div>
    </form>
</div>

<?= view('admin/footer') ?>
