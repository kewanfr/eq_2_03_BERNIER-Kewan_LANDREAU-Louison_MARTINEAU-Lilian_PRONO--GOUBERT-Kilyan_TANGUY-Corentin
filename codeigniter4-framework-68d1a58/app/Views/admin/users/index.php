<?= view('admin/header') ?>

<style>
    .badge { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-right: 5px; }
    .badge-admin { background: #dc3545; color: white; }
    .badge-commercial { background: #17a2b8; color: white; }
    .badge-client { background: #6c757d; color: white; }
    .user-details { font-size: 0.85em; color: #666; margin-top: 3px; }
    .user-details-icon { margin-right: 3px; }
    .btn-delete {
        background: #dc3545;
        color: white;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }
    .btn-delete:hover {
        background: #c82333;
    }
</style>

<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 class="admin-title" style="margin: 0;">Gestion des Utilisateurs</h2>
    <a href="/admin/users/create" class="btn-primary" style="text-decoration: none;">+ Créer un utilisateur</a>
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

<div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Contact</th>
                            <th>Type</th>
                            <th>Rôles</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td>
                                    <strong><?= esc($user['username']) ?></strong>
                                    <?php if ($user['customer_type'] === 'professionnel' && !empty($user['company_name'])): ?>
                                        <br><small style="color: #666;">🏢 <?= esc($user['company_name']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($user['siret'])): ?>
                                        <br><small style="color: #999;">SIRET: <?= esc($user['siret']) ?></small>
                                    <?php endif; ?>
                                    <?php if (!empty($user['tva_number'])): ?>
                                        <br><small style="color: #999;">TVA: <?= esc($user['tva_number']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($user['email'])): ?>
                                        <div class="user-details">📧 <?= esc($user['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($user['phone'])): ?>
                                        <div class="user-details">📞 <?= esc($user['phone']) ?></div>
                                    <?php endif; ?>
                                    <?php if (!empty($user['address'])): ?>
                                        <div class="user-details">📍 <?= esc($user['address']) ?></div>
                                    <?php endif; ?>
                                    <?php if (empty($user['email']) && empty($user['phone']) && empty($user['address'])): ?>
                                        <span style="color: #999; font-style: italic;">Aucune info</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['customer_type'] === 'professionnel'): ?>
                                        <span style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #8b6914; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold; display: inline-flex; align-items: center; gap: 3px;">PRO</span>
                                    <?php else: ?>
                                        <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold;"> Particulier</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($user['roles'])): ?>
                                        <?php foreach ($user['roles'] as $role): ?>
                                            <span class="badge badge-<?= $role ?>"><?= $role ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="badge badge-client">client</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($user['created_at'])): ?>
                                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                                    <?php else: ?>
                                        <span style="color: #999;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="/admin/users/<?= $user['id'] ?>/roles" class="btn btn-primary" style="margin-right: 5px;">Modifier</a>
                                    <?php if ($user['id'] != auth()->id()): ?>
                                        <button onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['username']) ?>')" class="btn-delete">Supprimer</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </div>

<script>
    function confirmDelete(userId, username) {
        if (confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur "' + username + '" ?\n\nCette action est irréversible.')) {
            // Crée un formulaire pour envoyer la requête DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/users/' + userId + '/delete';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<?= view('admin/footer') ?>
