<?= view('header') ?>

<style>
    .profile-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }
    
    .profile-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .profile-title {
        color: #8b4513;
        margin-bottom: 10px;
        border-bottom: 3px solid #8bc34a;
        padding-bottom: 10px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        font-weight: bold;
        color: #8b4513;
        margin-bottom: 8px;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1em;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #8bc34a;
        outline: none;
    }
    
    .btn-primary {
        background: #8bc34a;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-primary:hover {
        background: #6fa02f;
        transform: translateY(-2px);
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-danger:hover {
        background: #c82333;
    }
    
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .badge-pro {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #8b6914;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.9em;
        font-weight: bold;
        display: inline-block;
        margin-left: 10px;
    }
    
    .pro-fields {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
    }
</style>

<div class="profile-container">
    <h1 style="color: #8b4513; margin-bottom: 30px;">Mon Profil</h1>
    
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        <div class="alert alert-error"><?= session('error') ?></div>
    <?php endif; ?>
    
    <!-- Informations personnelles -->
    <div class="profile-card">
        <h2 class="profile-title">
            Informations personnelles
            <?php if ($user->customer_type === 'professionnel'): ?>
                <span class="badge-pro">⭐ PRO</span>
            <?php endif; ?>
        </h2>
        
        <form action="/profile/update" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="<?= esc($user->username) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= esc($email) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Numéro de téléphone</label>
                <input type="tel" id="phone" name="phone" value="<?= esc($user->phone ?? '') ?>" placeholder="Ex: 06 12 34 56 78">
            </div>
            
            <div class="form-group">
                <label for="address">Adresse</label>
                <textarea id="address" name="address" rows="3" placeholder="Adresse complète"><?= esc($user->address ?? '') ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="customer_type">Type de compte</label>
                <select id="customer_type" name="customer_type" onchange="toggleProFields()" required>
                    <option value="particulier" <?= $user->customer_type === 'particulier' ? 'selected' : '' ?>>Particulier</option>
                    <option value="professionnel" <?= $user->customer_type === 'professionnel' ? 'selected' : '' ?>>Professionnel</option>
                </select>
            </div>
            
            <!-- Champs professionnels -->
            <div id="pro_fields" class="pro-fields" style="display: <?= $user->customer_type === 'professionnel' ? 'block' : 'none' ?>;">
                <h3 style="color: #8b4513; margin-bottom: 15px;">Informations professionnelles</h3>
                
                <div class="form-group">
                    <label for="company_name">Nom de l'entreprise</label>
                    <input type="text" id="company_name" name="company_name" value="<?= esc($user->company_name ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label for="siret">Numéro SIRET</label>
                    <input type="text" id="siret" name="siret" value="<?= esc($user->siret ?? '') ?>" pattern="[0-9]{14}" placeholder="14 chiffres">
                </div>
                
                <div class="form-group">
                    <label for="tva_number">N° TVA intracommunautaire</label>
                    <input type="text" id="tva_number" name="tva_number" value="<?= esc($user->tva_number ?? '') ?>" placeholder="Ex: FR12345678901">
                </div>
            </div>
            
            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
        </form>
    </div>
    
    <!-- Changement de mot de passe -->
    <div class="profile-card">
        <h2 class="profile-title">Changer le mot de passe</h2>
        
        <form action="/profile/password" method="post">
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </div>
            
            <button type="submit" class="btn-danger">Changer le mot de passe</button>
        </form>
    </div>
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

</body>
</html>
