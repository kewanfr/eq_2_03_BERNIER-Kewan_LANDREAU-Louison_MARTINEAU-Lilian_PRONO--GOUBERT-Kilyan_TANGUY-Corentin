<?= $this->include('admin/header') ?>

<div class="header">
    <h1>Commande #<?= $order['id'] ?></h1>
    <a href="/admin/orders" class="btn-secondary">← Retour à la liste</a>
</div>

<?php if (session()->has('success')): ?>
    <div style="background: #d4edda; color: #155724; padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;">
        <?= session('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;">
        <?= session('error') ?>
    </div>
<?php endif; ?>

<div class="content-card">
    <h2>Informations client</h2>
    <div class="info-group">
        <strong>Utilisateur:</strong> <?= esc($order['username']) ?>
        <?php if ($order['customer_type'] === 'professionnel'): ?>
            <span style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #8b6914; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold; display: inline-flex; align-items: center; gap: 3px; margin-left: 8px;">PRO</span>
        <?php endif; ?>
    </div>
    <?php if ($order['customer_type'] === 'professionnel'): ?>
        <?php if (!empty($order['company_name'])): ?>
        <div class="info-group">
            <strong>Entreprise:</strong> <?= esc($order['company_name']) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($order['siret'])): ?>
        <div class="info-group">
            <strong>SIRET:</strong> <?= esc($order['siret']) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($order['tva_number'])): ?>
        <div class="info-group">
            <strong>N° TVA:</strong> <?= esc($order['tva_number']) ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (!empty($order['email'])): ?>
    <div class="info-group">
        <strong>Email:</strong> <?= esc($order['email']) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($order['phone'])): ?>
    <div class="info-group">
        <strong>Téléphone:</strong> <?= esc($order['phone']) ?>
    </div>
    <?php endif; ?>
    <?php if (!empty($order['address'])): ?>
    <div class="info-group">
        <strong>Adresse:</strong> <?= esc($order['address']) ?>
    </div>
    <?php endif; ?>
</div>

<div class="content-card">
    <h2>Informations commande</h2>
    <div class="info-group">
        <strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($order['order_date'])) ?>
    </div>
    <div class="info-group">
        <strong>Statut actuel:</strong> 
        <?php
        $statusColor = '#6c757d';
        $statusBg = '#e9ecef';
        switch($order['status']) {
            case 'PAYEE':
                $statusColor = '#856404';
                $statusBg = '#fff3cd';
                break;
            case 'EN_PREPARATION':
                $statusColor = '#856404';
                $statusBg = '#fff3cd';
                break;
            case 'PRETE':
                $statusColor = '#0c5460';
                $statusBg = '#d1ecf1';
                break;
            case 'EXPEDIEE':
                $statusColor = '#0c5460';
                $statusBg = '#d1ecf1';
                break;
            case 'LIVREE':
                $statusColor = '#155724';
                $statusBg = '#d4edda';
                break;
            case 'ANNULEE':
                $statusColor = '#721c24';
                $statusBg = '#f8d7da';
                break;
        }
        ?>
        <span style="background: <?= $statusBg ?>; color: <?= $statusColor ?>; padding: 5px 12px; border-radius: 12px; display: inline-block; font-weight: bold; font-size: 13px;">
            <?= $order['status'] ?>
        </span>
    </div>
    <div class="info-group">
        <strong>Mode de livraison:</strong>
        <?php
        $deliveryNames = [
            'pickup' => 'Retrait à la cidrerie',
            'local_delivery' => 'Livraison locale',
            'carrier_delivery' => 'Livraison transporteur'
        ];
        $deliveryMethod = $order['delivery_method'] ?? 'pickup';
        ?>
        <?= $deliveryNames[$deliveryMethod] ?? 'Retrait à la cidrerie' ?>
    </div>
    <div class="info-group">
        <strong>Frais de livraison:</strong> <?= number_format($order['delivery_cost'] ?? 0, 2) ?> €
    </div>
    <div class="info-group">
        <strong>Total HT:</strong> <?= number_format($order['total_ht'], 2) ?> €
    </div>
    <div class="info-group">
        <strong>Total TTC:</strong> <?= number_format($order['total_ttc'], 2) ?> €
    </div>
</div>

<div class="content-card">
    <h2>Produits commandés</h2>
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
                    <td><?= esc($item['name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['unit_price'], 2) ?> €</td>
                    <td><?= number_format($item['unit_price'] * $item['quantity'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total TTC:</strong></td>
                <td><strong><?= number_format($order['total_ttc'], 2) ?> €</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if ($order['status'] !== 'LIVREE' && $order['status'] !== 'ANNULEE'): ?>
<div class="content-card">
    <h2>Modifier le statut</h2>
    <p style="color: #666; margin-bottom: 15px;">
        Les statuts disponibles dépendent de votre rôle et du statut actuel.
    </p>
    
    <form method="post" action="/admin/orders/<?= $order['id'] ?>/status">
        <div class="form-group">
            <label>Nouveau statut:</label>
            <select name="status" required>
                <?php
                // Statuts disponibles selon le rôle
                $currentStatus = $order['status'];
                $userRoles = session()->get('user_roles') ?? [];
                $isAdmin = in_array('admin', $userRoles);
                $isCommercial = in_array('commercial', $userRoles);
                $isPreparation = in_array('preparation', $userRoles);
                $isProduction = in_array('production', $userRoles);
                
                // ADMIN peut tout faire
                if ($isAdmin) {
                    $statuses = ['PAYEE', 'EN_PREPARATION', 'PRETE', 'EXPEDIEE', 'LIVREE'];
                }
                // COMMERCIAL peut valider le paiement
                elseif ($isCommercial) {
                    $statuses = [];
                    if ($currentStatus === 'PAYEE') {
                        $statuses[] = 'EN_PREPARATION';
                    }
                }
                // PREPARATION peut marquer comme prête
                elseif ($isPreparation) {
                    $statuses = [];
                    if ($currentStatus === 'EN_PREPARATION') {
                        $statuses[] = 'PRETE';
                    }
                }
                // PRODUCTION peut expédier et marquer comme livrée
                elseif ($isProduction) {
                    $statuses = [];
                    if ($currentStatus === 'PRETE') {
                        $statuses[] = 'EXPEDIEE';
                    }
                    if ($currentStatus === 'EXPEDIEE') {
                        $statuses[] = 'LIVREE';
                    }
                }
                else {
                    $statuses = [];
                }
                
                foreach ($statuses as $status):
                    $selected = ($status === $currentStatus) ? 'selected' : '';
                ?>
                    <option value="<?= $status ?>" <?= $selected ?>><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (!empty($statuses)): ?>
            <button type="submit" class="btn-primary" style="background: #8bc34a; color: white; padding: 12px 30px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 15px; transition: all 0.3s;" onmouseover="this.style.background='#7cb342'" onmouseout="this.style.background='#8bc34a'">✓ Mettre à jour le statut</button>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">
                Aucune action disponible pour votre rôle sur cette commande.
            </p>
        <?php endif; ?>
        
        <?php if ($isAdmin || $isCommercial): ?>
            <button type="submit" name="action" value="cancel" style="background: #dc3545; color: white; padding: 8px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: normal; font-size: 13px; transition: all 0.3s; margin-left: 10px;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'" onclick="return confirm('⚠️ Voulez-vous vraiment annuler cette commande ?\n\nLe stock des produits sera restauré automatiquement.');">✗ Annuler la commande</button>
        <?php endif; ?>
    </form>
</div>
<?php else: ?>
<div class="content-card">
    <p style="color: #999; font-style: italic;">
        Cette commande est terminée (<?= $order['status'] ?>). Aucune modification possible.
    </p>
</div>
<?php endif; ?>

<?= $this->include('admin/footer') ?>
