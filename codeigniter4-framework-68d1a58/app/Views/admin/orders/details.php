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
    </div>
    <?php if (!empty($order['email'])): ?>
    <div class="info-group">
        <strong>Email:</strong> <?= esc($order['email']) ?>
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
        <span style="background: #ffc107; padding: 5px 10px; border-radius: 4px; display: inline-block;">
            <?= $order['status'] ?>
        </span>
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
                ?>
                    <option value="<?= $status ?>"><?= $status ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (!empty($statuses)): ?>
            <button type="submit" class="btn-primary">Mettre à jour le statut</button>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">
                Aucune action disponible pour votre rôle sur cette commande.
            </p>
        <?php endif; ?>
    </form>
    
    <?php if ($isAdmin || $isCommercial): ?>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
            <form method="post" action="/admin/orders/<?= $order['id'] ?>/cancel" 
                  onsubmit="return confirm('Voulez-vous vraiment annuler cette commande ? Le stock sera restauré.');">
                <input type="hidden" name="cancel" value="1">
                <button type="submit" class="btn-danger">Annuler la commande</button>
            </form>
        </div>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="content-card">
    <p style="color: #999; font-style: italic;">
        Cette commande est terminée (<?= $order['status'] ?>). Aucune modification possible.
    </p>
</div>
<?php endif; ?>

<?= $this->include('admin/footer') ?>
