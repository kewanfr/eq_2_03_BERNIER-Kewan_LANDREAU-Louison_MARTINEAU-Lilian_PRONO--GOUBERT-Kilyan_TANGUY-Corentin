<?= $this->include('admin/header') ?>

<style>
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
</style>

<div class="header">
    <h1>Créer un nouvel utilisateur</h1>
    <a href="/admin/users" class="btn-secondary">← Retour à la liste</a>
</div>

<?php if (session()->has('error')): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 4px; margin-bottom: 20px;">
        <?= session('error') ?>
    </div>
<?php endif; ?>

<div class="content-card">
    <h2>Informations utilisateur</h2>
    
    <form action="/admin/users/create" method="post">
        <div class="form-group">
            <label for="username">Nom d'utilisateur *</label>
            <input type="text" id="username" name="username" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="password">Mot de passe *</label>
            <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input type="tel" id="phone" name="phone" placeholder="Ex: 06 12 34 56 78" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
        </div>

        <div class="form-group">
            <label for="address">Adresse</label>
            <textarea id="address" name="address" rows="3" placeholder="Adresse complète" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;"></textarea>
        </div>

        <div class="form-group">
            <label for="customer_type">Type de compte</label>
            <select id="customer_type" name="customer_type" onchange="toggleProFields()" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
                <option value="particulier">Particulier</option>
                <option value="professionnel">Professionnel (Restaurant, Bar, etc.)</option>
            </select>
        </div>

        <!-- Champs spécifiques pour les professionnels -->
        <div id="pro_fields" style="display: none;">
            <div class="form-group">
                <label for="company_name">Nom de l'entreprise</label>
                <input type="text" id="company_name" name="company_name" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group">
                <label for="siret">Numéro SIRET</label>
                <input type="text" id="siret" name="siret" pattern="[0-9]{14}" placeholder="14 chiffres" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
            </div>

            <div class="form-group">
                <label for="tva_number">N° TVA intracommunautaire</label>
                <input type="text" id="tva_number" name="tva_number" placeholder="Ex: FR12345678901" style="width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px;">
            </div>
        </div>

        <div class="form-group">
            <label>Rôles</label>
            <div class="checkbox-group">
                <?php 
                $allRoles = ['admin', 'commercial', 'preparation', 'production', 'saisonnier', 'client'];
                foreach ($allRoles as $role): 
                ?>
                    <div class="checkbox-item">
                        <input type="checkbox" 
                               id="role_<?= $role ?>" 
                               name="roles[]" 
                               value="<?= $role ?>"
                               <?= $role === 'client' ? 'checked' : '' ?>>
                        <label for="role_<?= $role ?>">
                            <?= ucfirst($role) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <button type="submit" class="btn-primary">Créer l'utilisateur</button>
        <a href="/admin/users" class="btn-secondary" style="margin-left: 10px;">Annuler</a>
    </form>
</div>

<script>
    function toggleProFields() {
        const customerType = document.getElementById('customer_type').value;
        const proFields = document.getElementById('pro_fields');
        
        if (customerType === 'professionnel') {
            proFields.style.display = 'block';
        } else {
            proFields.style.display = 'none';
        }
    }
</script>

<?= $this->include('admin/footer') ?>
