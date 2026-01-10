<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande #<?= $order['id'] ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(to bottom, #f5f5dc 0%, #d2b48c 100%);
            background-attachment: fixed;
            padding: 20px;
            padding-left: 0px;
        }
        
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #8b4513;
            margin-bottom: 30px;
            font-size: 2.5em;
            border-bottom: 3px solid #8bc34a;
            padding-bottom: 15px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #c41e3a;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .back-link:hover {
            color: #a01828;
            transform: translateX(-5px);
        }
        
        .order-info {
            background: #f5f5dc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        
        .info-label {
            font-weight: bold;
            color: #8b4513;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1em;
        }
        
        .status-PAYEE { background: #ffc107; color: #333; }
        .status-EN_PREPARATION { background: #17a2b8; color: white; }
        .status-PRETE { background: #28a745; color: white; }
        .status-EXPEDIEE { background: #007bff; color: white; }
        .status-LIVREE { background: #6c757d; color: white; }
        .status-ANNULEE { background: #dc3545; color: white; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        
        th {
            background: #8bc34a;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 1.1em;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:hover {
            background: #f5deb3;
        }
        
        .total-row {
            background: #f5f5dc;
            font-weight: bold;
            font-size: 1.2em;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #c41e3a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #a01828;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #8bc34a;
        }
        
        .btn-secondary:hover {
            background: #6fa02f;
        }
    </style>
</head>
<body>
    <?= view('header') ?>
    
    <div class="container">
        <a href="/orders" class="back-link">← Retour à mes commandes</a>
        
        <h1>Commande #<?= $order['id'] ?></h1>
        
        <div class="order-info">
            <div class="info-row">
                <span class="info-label">Date de commande:</span>
                <span><?= date('d/m/Y à H:i', strtotime($order['order_date'])) ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="status-badge status-<?= $order['status'] ?>"><?= $order['status'] ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Total HT:</span>
                <span><?= number_format($order['total_ht'], 2) ?> €</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total TTC:</span>
                <span style="font-size: 1.3em; color: #c41e3a; font-weight: bold;"><?= number_format($order['total_ttc'], 2) ?> €</span>
            </div>
        </div>
        
        <h2 style="color: #8b4513; margin-bottom: 20px;">Produits commandés</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php if (!empty($item['img_src'])): ?>
                                    <img src="<?= esc($item['img_src']) ?>" alt="<?= esc($item['name']) ?>" class="product-image">
                                <?php endif; ?>
                                <span><?= esc($item['name']) ?></span>
                            </div>
                        </td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['unit_price'], 2) ?> €</td>
                        <td><?= number_format($item['unit_price'] * $item['quantity'], 2) ?> €</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">Total TTC:</td>
                    <td><?= number_format($order['total_ttc'], 2) ?> €</td>
                </tr>
            </tfoot>
        </table>
        
        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <a href="/orders" class="btn btn-secondary">Retour à mes commandes</a>
            <a href="/" class="btn">Continuer mes achats</a>
        </div>
    </div>
</body>
</html>
