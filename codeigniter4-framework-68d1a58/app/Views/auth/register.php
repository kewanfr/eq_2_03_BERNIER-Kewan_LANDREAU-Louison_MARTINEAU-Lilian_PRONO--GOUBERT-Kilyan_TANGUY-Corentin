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
        <input id="username" type="text" name="username" class="textfield">

        <label for="email">E-mail:</label><br>
        <input id="email" type="email" name="email" class="textfield"><br>

        <label for="password">Mot de passe:</label><br>
        <input id="password" type="password" name="password" class="textfield"><br>

        <label for="password_confirm">Confirmez le mot de passe:</label><br>
        <input id="password_confirm" type="password" name="password_confirm" class="textfield"><br>

        <div class="remember">
            <input id="remember" type="checkbox" name="remember">
            <label for="remember">Se souvenir de moi</label><br>
        </div>

        <input id="submit" type="submit" value="S'inscrire">
    </form>

    <div class="separator color-primary"></div>

    <span>Déjà un compte? <a href="/login">Se connecter</a></span>
</div>
</body>
</html>