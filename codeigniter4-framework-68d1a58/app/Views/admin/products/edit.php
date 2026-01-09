<?= view('admin/header') ?>

<h2 class="admin-title">Modifier le produit</h2>

<div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 700px;">
    <form method="post" action="/admin/products/update/<?= $product['id'] ?>">
        <div class="form-group">
            <label>Nom du produit</label>
            <input type="text" name="name" value="<?= esc($product['name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="desc" required style="min-height: 100px; resize: vertical;"><?= esc($product['desc']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Catégorie</label>
            <select name="category">
                <option value="">-- Sélectionner --</option>
                <option value="cidres" <?= ($product['category'] ?? '') === 'cidres' ? 'selected' : '' ?>>Cidres</option>
                <option value="jus" <?= ($product['category'] ?? '') === 'jus' ? 'selected' : '' ?>>Jus</option>
                <option value="vinaigre" <?= ($product['category'] ?? '') === 'vinaigre' ? 'selected' : '' ?>>Vinaigre</option>
                <option value="accessoires" <?= ($product['category'] ?? '') === 'accessoires' ? 'selected' : '' ?>>Accessoires</option>
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
