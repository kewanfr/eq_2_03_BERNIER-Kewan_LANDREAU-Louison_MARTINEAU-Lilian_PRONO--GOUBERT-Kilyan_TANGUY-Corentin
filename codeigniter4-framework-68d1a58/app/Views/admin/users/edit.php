<?= $this->include('admin/header') ?>

<style>
    .user-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 30px;
    }
    
    .user-info p {
        margin-bottom: 10px;
        color: #2c3e50;
    }
    
    .user-info strong {
        font-weight: bold;
    }
    
    .checkbox-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    
    .checkbox-item input[type="checkbox"] {
        margin-right: 10px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .checkbox-item label {
        margin: 0;
        cursor: pointer;
        font-weight: normal;
    }
    
    .role-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 10px;
    }
    
    .badge-admin { background-color: #dc3545; color: white; }
    .badge-commercial { background-color: #007bff; color: white; }
    .badge-preparation { background-color: #ffc107; color: black; }
    .badge-production { background-color: #28a745; color: white; }
    .badge-saisonnier { background-color: #17a2b8; color: white; }
    .badge-client { background-color: #6c757d; color: white; }
</style>

<div class="header">
    <h1>Éditer les rôles de l'utilisateur</h1>
    <a href="/admin/users" class="btn-secondary">← Retour à la liste</a>
</div>

<div class="content-card">
    <h2>Informations utilisateur</h2>
    <div class="user-info">
        <p><strong>ID:</strong> <?= esc($user->id) ?></p>
        <p><strong>Nom d'utilisateur:</strong> <?= esc($user->username) ?></p>
        <p><strong>Type de compte:</strong> 
            <?php if ($user->customer_type === 'professionnel'): ?>
                <span style="background: linear-gradient(135deg, #ffd700, #ffed4e); color: #8b6914; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold;">⭐ PRO</span>
                <?php if (!empty($user->company_name)): ?>
                    - <?= esc($user->company_name) ?>
                <?php endif; ?>
            <?php else: ?>
                <span style="background: #e3f2fd; color: #1976d2; padding: 4px 8px; border-radius: 8px; font-size: 11px; font-weight: bold;">👤 Particulier</span>
            <?php endif; ?>
        </p>
        <p><strong>Rôles actuels:</strong> 
            <?php if (empty($userRoles)): ?>
                <span class="role-badge badge-client">Aucun rôle</span>
            <?php else: ?>
                <?php foreach ($userRoles as $role): ?>
                    <span class="role-badge badge-<?= esc($role) ?>"><?= esc(ucfirst($role)) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </p>
    </div>
    
    <form action="/admin/users/<?= esc($user->id) ?>/roles" method="post">
        <div class="form-group" style="margin-bottom: 25px;">
            <label for="phone">Numéro de téléphone:</label>
            <input type="tel" id="phone" name="phone" class="textfield" value="<?= esc($user->phone ?? '') ?>" placeholder="Ex: 06 12 34 56 78" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
        </div>
        
        <div class="form-group" style="margin-bottom: 25px;">
            <label for="address">Adresse:</label>
            <textarea id="address" name="address" class="textfield" rows="3" placeholder="Adresse complète" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;"><?= esc($user->address ?? '') ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Sélectionner les rôles:</label>
            <div class="checkbox-group">
                <?php 
                $allRoles = ['admin', 'commercial', 'preparation', 'production', 'saisonnier', 'client'];
                foreach ($allRoles as $role): 
                    $checked = in_array($role, $userRoles) ? 'checked' : '';
                ?>
                    <div class="checkbox-item">
                        <input type="checkbox" 
                               id="role_<?= $role ?>" 
                               name="roles[]" 
                               value="<?= $role ?>"
                               <?= $checked ?>>
                        <label for="role_<?= $role ?>">
                            <?= ucfirst($role) ?>
                            <span class="role-badge badge-<?= $role ?>"><?= ucfirst($role) ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <button type="submit" class="btn-primary">Enregistrer les modifications</button>
        <a href="/admin/users" class="btn-secondary" style="margin-left: 10px;">Annuler</a>
    </form>
</div>

<?= $this->include('admin/footer') ?>
