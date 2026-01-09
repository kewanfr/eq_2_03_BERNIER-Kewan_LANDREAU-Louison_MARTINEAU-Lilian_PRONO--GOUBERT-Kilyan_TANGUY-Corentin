<?= view('admin/header') ?>

<style>
    .badge { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-right: 5px; }
    .badge-admin { background: #dc3545; color: white; }
    .badge-commercial { background: #17a2b8; color: white; }
    .badge-client { background: #6c757d; color: white; }
</style>

<h2 class="admin-title">Gestion des Utilisateurs</h2>

<div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom d'utilisateur</th>
                            <th>Rôles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= esc($user['username']) ?></td>
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
                                    <a href="/admin/users/<?= $user['id'] ?>/roles" class="btn btn-primary">Modifier rôles</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
        </div>

<?= view('admin/footer') ?>
