<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office - Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h2 { margin-bottom: 30px; font-size: 20px; }
        .sidebar nav a { display: block; padding: 12px 15px; color: white; text-decoration: none; margin-bottom: 5px; border-radius: 4px; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #34495e; }
        .main-content { flex: 1; padding: 30px; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card h3 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .value { font-size: 32px; font-weight: bold; color: #2c3e50; }
        .orders-table { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8f9fa; padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; }
        td { padding: 12px; border-bottom: 1px solid #dee2e6; }
        .badge { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 14px; display: inline-block; }
        .btn-primary { background: #007bff; color: white; }
        .logout { margin-top: 30px; padding-top: 20px; border-top: 1px solid #34495e; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>📊 Back Office</h2>
            <nav>
                <a href="/admin" class="active">Dashboard</a>
                <a href="/admin/products">Produits</a>
                <a href="/admin/orders">Commandes</a>
                <a href="/admin/stock">Gestion Stock</a>
                <?php if ($userRole === 'admin'): ?>
                    <a href="/admin/users">Utilisateurs</a>
                <?php endif; ?>
            </nav>
            <div class="logout">
                <a href="/" style="color: white; text-decoration: none;">← Site public</a><br><br>
                <a href="/auth/logout" style="color: #e74c3c; text-decoration: none;">Déconnexion</a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Tableau de bord</h1>
                <p>Bienvenue dans l'espace d'administration</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>TOTAL COMMANDES</h3>
                    <div class="value"><?= $statistics['total_orders'] ?></div>
                </div>
                <div class="stat-card">
                    <h3>CHIFFRE D'AFFAIRES</h3>
                    <div class="value"><?= number_format($statistics['total_revenue'], 2) ?> €</div>
                </div>
                <?php foreach ($statistics['by_status'] as $stat): ?>
                    <div class="stat-card">
                        <h3><?= strtoupper(str_replace('_', ' ', $stat['status'])) ?></h3>
                        <div class="value"><?= $stat['count'] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="orders-table">
                <h2 style="margin-bottom: 20px;">Dernières commandes</h2>
                <?php if (empty($recentOrders)): ?>
                    <p style="color: #666; text-align: center; padding: 20px;">Aucune commande</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
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
                                    <td><?= esc($order['username']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
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
        </div>
    </div>
</body>
</html>
