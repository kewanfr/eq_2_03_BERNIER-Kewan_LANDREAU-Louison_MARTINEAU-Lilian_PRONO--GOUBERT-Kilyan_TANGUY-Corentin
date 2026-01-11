<?= view('admin/header') ?>

<h2 class="admin-title">Historique des mouvements de stock</h2>

<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; border-bottom: 1px solid #ddd; }
    th { background: #f7f7f7; text-align: left; }
    .qty-plus { color: #28a745; font-weight: bold; }
    .qty-minus { color: #dc3545; font-weight: bold; }
    .reason { font-weight: bold; }
</style>

<div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Variation</th>
                <th>Raison</th>
                <th>Référence</th>
                <th>Utilisateur</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($movements)): ?>
                <tr><td colspan="7" style="text-align:center; color:#666;">Aucun mouvement enregistré</td></tr>
            <?php else: ?>
                <?php foreach ($movements as $m): ?>
                    <tr>
                        <td><?= esc($m['created_at']) ?></td>
                        <td><?= esc($m['product_name'] ?? ('#'.$m['product_id'])) ?></td>
                        <td>
                            <?php $q = (int)$m['quantity_change']; ?>
                            <span class="<?= $q >= 0 ? 'qty-plus' : 'qty-minus' ?>">
                                <?= $q >= 0 ? '+' . $q : $q ?>
                            </span>
                        </td>
                        <td class="reason">
                            <?php
                                $labels = [
                                    'ORDER' => 'Commande',
                                    'CANCELLATION' => 'Annulation',
                                    'MANUAL' => 'Ajustement manuel',
                                ];
                                echo esc($labels[$m['reason']] ?? $m['reason']);
                            ?>
                        </td>
                        <td>
                            <?php if (!empty($m['reference_id'])): ?>
                                <?= 'Commande #' . esc($m['reference_id']) ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td><?= esc($m['actor_username'] ?? '—') ?></td>
                        <td><?= esc($m['note'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    <a class="btn" href="/admin/stock" style="background:#6c757d; color:white; padding:8px 14px; border-radius:6px; text-decoration:none;">← Retour gestion stock</a>
</div>

<?= view('admin/footer') ?>
