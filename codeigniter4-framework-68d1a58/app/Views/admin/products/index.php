<?= view('admin/header') ?>

<style>
    .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; vertical-align: middle; }
    .actions { display: flex; gap: 5px; align-items: center; }
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    .alert-success { background: #d4edda; color: #155724; }
    .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    td { vertical-align: middle !important; }
</style>

<div class="header-actions">
    <h2 class="admin-title">Gestion des Produits</h2>
    <a href="/product/add" class="btn btn-success">+ Ajouter</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div>
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><img src="<?= esc($product['img_src']) ?>" alt="<?= esc($product['name']) ?>" class="product-img"></td>
                                <td><?= esc($product['name']) ?></td>
                                <td><?= esc(substr($product['desc'], 0, 50)) ?>...</td>
                                <td><?= number_format($product['price'], 2) ?> €</td>
                                <td><?= $product['quantity'] ?></td>
                                <td class="actions">
                                    <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-primary">Modifier</a>
                                    <a href="/admin/products/delete/<?= $product['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </div>

<?= view('admin/footer') ?>
