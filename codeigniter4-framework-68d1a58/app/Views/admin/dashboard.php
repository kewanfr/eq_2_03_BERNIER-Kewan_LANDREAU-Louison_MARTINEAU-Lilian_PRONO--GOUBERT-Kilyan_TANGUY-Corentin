<?= view('admin/header') ?>

<h2 class="admin-title">Tableau de bord</h2>

<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px;">TOTAL COMMANDES</h3>
        <div style="font-size: 32px; font-weight: bold; color: #c41e3a;"><?= $statistics['total_orders'] ?></div>
    </div>
    
    <?php 
    $userRoles = session()->get('user_roles') ?? [];
    $canViewRevenue = in_array('admin', $userRoles) || in_array('commercial', $userRoles);
    if ($canViewRevenue): 
    ?>
    <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h3 style="color: #666; font-size: 14px; margin-bottom: 10px;">CHIFFRE D'AFFAIRES</h3>
        <div style="font-size: 32px; font-weight: bold; color: #8bc34a;"><?= number_format($statistics['total_revenue'], 2) ?> €</div>
    </div>
    <?php endif; ?>
    
    <?php foreach ($statistics['by_status'] as $stat): ?>
        <div class="stat-card" style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <h3 style="color: #666; font-size: 14px; margin-bottom: 10px;"><?= strtoupper(str_replace('_', ' ', $stat['status'])) ?></h3>
            <div style="font-size: 32px; font-weight: bold; color: #ffc107;"><?= $stat['count'] ?></div>
        </div>
    <?php endforeach; ?>
</div>

<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="color: #8b4513; margin-bottom: 20px;">Dernières commandes</h2>
    <?php if (empty($recentOrders)): ?>
        <p style="color: #666; text-align: center; padding: 20px;">Aucune commande</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($recentOrders, 0, 10) as $order): ?>
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
                            <a href="/admin/orders/<?= $order['id'] ?>" class="btn btn-primary">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
    .badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-warning { background: #fff3cd; color: #856404; }
    .badge-info { background: #d1ecf1; color: #0c5460; }
    .badge-danger { background: #f8d7da; color: #721c24; }
</style>

<?= view('admin/footer') ?>
