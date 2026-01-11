<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
</head>
<body>
    <?= view('header'); ?>
    <style>
        .orders-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        .order-card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .order-id { font-weight: bold; font-size: 18px; }
        .order-status { padding: 5px 15px; border-radius: 12px; font-size: 14px; font-weight: bold; }
        .status-PAYEE { background: #d1ecf1; color: #0c5460; }
        .status-EN_PREPARATION { background: #fff3cd; color: #856404; }
        .status-EXPEDIEE { background: #d4edda; color: #155724; }
        .status-LIVREE { background: #d4edda; color: #155724; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 4px; background: #007bff; color: white; }
    </style>
    
    <div class="orders-container">
        <h1>Mes Commandes</h1>
        
        <?php if (empty($orders)): ?>
            <p style="text-align: center; padding: 40px; color: #666;">Aucune commande</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <span class="order-id">Commande #<?= $order['id'] ?></span>
                            <span style="color: #666; margin-left: 15px;"><?= date('d/m/Y', strtotime($order['order_date'])) ?></span>
                        </div>
                        <span class="order-status status-<?= $order['status'] ?>"><?= $order['status'] ?></span>
                    </div>
                    <div>
                        <strong>Total:</strong> <?= number_format($order['total_ttc'], 2) ?> €
                    </div>
                    <a href="/orders/<?= $order['id'] ?>" class="btn" style="margin-top: 10px; display: inline-block;">Voir détails</a>
                    <?php if ($order['status'] === 'PAYEE'): ?>
                        <form action="/orders/<?= $order['id'] ?>/cancel" method="post" style="display: inline; margin-left: 10px;">
                            <button type="submit" class="btn" style="background: #dc3545;">Annuler</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
