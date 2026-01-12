<?php
namespace CodeIgniter\Views\auth;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/assets/style/colors.css">
    <link rel="stylesheet" href="/assets/style/register.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="color-light children-text-dark main_container">
    <label>S'inscrire</label>
    <div class="separator color-primary"></div>

    <?php if (session('errors') !== null && !empty(session('errors'))): ?>
        <div class="errormessage">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="/auth/register" method="post">

        <label for="username">Nom d'utilisateur:</label>
        <input id="username" type="text" name="username" class="textfield" required>

        <label for="email">E-mail:</label><br>
        <input id="email" type="email" name="email" class="textfield" required><br>

        <label for="password">Mot de passe:</label><br>
        <input id="password" type="password" name="password" class="textfield" required><br>

        <label for="password_confirm">Confirmez le mot de passe:</label><br>
        <input id="password_confirm" type="password" name="password_confirm" class="textfield" required><br>

        <!-- Informations de contact (optionnelles) -->
        <label for="phone">Numéro de téléphone (optionnel):</label><br>
        <input id="phone" type="tel" name="phone" class="textfield" placeholder="Ex: 06 12 34 56 78"><br>

        <label for="address">Adresse (optionnel):</label><br>
        <textarea id="address" name="address" class="textfield" rows="3" placeholder="Adresse complète"></textarea><br>

        <!-- Type de client -->
        <label for="customer_type">Type de compte:</label><br>
        <select id="customer_type" name="customer_type" class="textfield" onchange="toggleProFields()" required>
            <option value="particulier">Particulier</option>
            <option value="professionnel">Professionnel (Restaurant, Bar, etc.)</option>
        </select><br>

        <!-- Champs spécifiques pour les professionnels -->
        <div id="pro_fields" style="display: none;">
            <label for="company_name">Nom de l'entreprise:</label><br>
            <input id="company_name" type="text" name="company_name" class="textfield"><br>

            <label for="siret">Numéro SIRET:</label><br>
            <input id="siret" type="text" name="siret" class="textfield" pattern="[0-9]{14}" placeholder="14 chiffres"><br>

            <label for="tva_number">N° TVA intracommunautaire (optionnel):</label><br>
            <input id="tva_number" type="text" name="tva_number" class="textfield" placeholder="Ex: FR12345678901"><br>
        </div>

        <div class="remember">
            <input id="remember" type="checkbox" name="remember">
            <label for="remember">Se souvenir de moi</label><br>
        </div>

        <input id="submit" type="submit" value="S'inscrire">
    </form>

    <script>
        function toggleProFields() {
            const customerType = document.getElementById('customer_type').value;
            const proFields = document.getElementById('pro_fields');
            const companyName = document.getElementById('company_name');
            const siret = document.getElementById('siret');
            
            if (customerType === 'professionnel') {
                proFields.style.display = 'block';
                companyName.required = true;
                siret.required = true;
            } else {
                proFields.style.display = 'none';
                companyName.required = false;
                siret.required = false;
            }
        }
    </script>

    <div class="separator color-primary"></div>

    <span>Déjà un compte? <a href="/login">Se connecter</a></span>
</div>
</body>
</html>