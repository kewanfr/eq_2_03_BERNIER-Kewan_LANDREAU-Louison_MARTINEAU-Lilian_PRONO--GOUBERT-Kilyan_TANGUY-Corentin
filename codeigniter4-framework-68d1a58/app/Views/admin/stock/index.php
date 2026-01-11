<?= view('admin/header') ?>

<style>
    .low-stock { background: #fff3cd; }
    .stock-actions { display: flex; gap: 10px; align-items: center; }
    .stock-actions input { width: 80px; padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
</style>

<h2 class="admin-title">Gestion des Stocks</h2>

<div>
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Stock Actuel</th>
                            <th>Prix</th>
                            <th>Ajuster Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr class="<?= isset($product['low_stock']) && $product['low_stock'] ? 'low-stock' : '' ?>">
                                <td><?= esc($product['name']) ?></td>
                                <td>
                                    <strong><?= $product['quantity'] ?></strong>
                                    <?php if (isset($product['low_stock']) && $product['low_stock']): ?>
                                        <span style="color: #856404; margin-left: 10px;">⚠️ Stock faible</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($product['price'], 2) ?> €</td>
                                <td>
                                    <form method="post" action="/admin/stock/<?= $product['id'] ?>/adjust" class="stock-actions">
                                        <input type="number" name="adjustment" placeholder="±" required>
                                        <input type="text" name="note" placeholder="Motif (optionnel)">
                                        <button type="submit" class="btn btn-success">Ajuster</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </div>

<div style="margin-top: 20px;">
    <a class="btn" href="/admin/stock/history" style="background:#007bff; color:white; padding:8px 14px; border-radius:6px; text-decoration:none;">Voir l'historique des mouvements</a>
</div>

<?= view('admin/footer') ?>
