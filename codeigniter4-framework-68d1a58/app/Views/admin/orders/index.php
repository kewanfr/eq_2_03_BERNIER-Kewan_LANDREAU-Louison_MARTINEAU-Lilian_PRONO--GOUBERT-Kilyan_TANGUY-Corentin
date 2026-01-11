<?= view('admin/header') ?>

<style>
    .badge { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-info { background: #d1ecf1; color: #0c5460; }
    .badge-danger { background: #f8d7da; color: #721c24; }
    .filters { margin-bottom: 20px; }
    .filters select { padding: 8px; border: 2px solid #8bc34a; border-radius: 4px; }
</style>

<h2 class="admin-title">Gestion des Commandes</h2>

<div class="filters">
    <form method="get" action="/admin/orders">
        <label>Filtrer par statut: </label>
        <select name="status" onchange="this.form.submit()">
            <option value="">Tous</option>
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status ?>" <?= (isset($_GET['status']) && $_GET['status'] === $status) ? 'selected' : '' ?>>
                    <?= str_replace('_', ' ', $status) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div>
                <?php if (empty($orders)): ?>
                    <p style="color: #666; text-align: center; padding: 40px;">Aucune commande</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Montant TTC</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= $order['id'] ?></td>
                                    <td>
                                        <?= esc($order['username']) ?>
                                        <?php if ($order['customer_type'] === 'professionnel' && !empty($order['company_name'])): ?>
                                            <br><small style="color: #666;"><?= esc($order['company_name']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($order['customer_type'] === 'professionnel'): ?>
                                            <span style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #8b6914; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold; display: inline-flex; align-items: center; gap: 3px;">⭐ PRO</span>
                                        <?php else: ?>
                                            <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold;">👤 Particulier</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($order['email']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['order_date'])) ?></td>
                                    <td><?= number_format($order['total_ttc'], 2) ?> €</td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'badge-info';
                                        if ($order['status'] === 'LIVREE') $badgeClass = 'badge-success';
                                        elseif ($order['status'] === 'ANNULEE') $badgeClass = 'badge-danger';
                                        elseif ($order['status'] === 'EN_PREPARATION' || $order['status'] === 'PAYEE') $badgeClass = 'badge-warning';
                                        elseif ($order['status'] === 'PRETE' || $order['status'] === 'EXPEDIEE') $badgeClass = 'badge-info';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $order['status'] ?></span>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-primary">Détails</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
        </div>

<?= view('admin/footer') ?>
