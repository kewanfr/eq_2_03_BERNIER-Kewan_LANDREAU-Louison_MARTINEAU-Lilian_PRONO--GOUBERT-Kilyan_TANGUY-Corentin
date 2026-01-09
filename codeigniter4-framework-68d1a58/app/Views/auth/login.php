<?php
namespace CodeIgniter\Views\auth;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/assets/style/colors.css">
    <link rel="stylesheet" href="/assets/style/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
<div class="color-light children-text-dark main_container">
    <label>Se connecter</label>
    <div class="separator color-primary"></div>

    <?php if (session('error') !== null) : ?>
        <div class="errormessage" role="alert"><?= esc(session('error')) ?></div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="errormessage" role="alert">
            <?php if (is_array(session('errors'))) : ?>
                <?php foreach (session('errors') as $error) : ?>
                    <?= esc($error) ?>
                    <br>
                <?php endforeach ?>
            <?php else : ?>
                <?= esc(session('errors')) ?>
            <?php endif ?>
        </div>
    <?php endif ?>

    <form action="/auth/login" method="post">
        <label for="email">Email ou nom d'utilisateur:</label><br>
        <input id="email" type="text" name="email" class="textfield" autocomplete="username" placeholder="Email ou nom d'utilisateur"><br>

        <label for="password">Mot de passe:</label><br>
        <input id="password" type="password" name="password" class="textfield"><br>

        <a href="" class="forgot-password">Mot de passe oublié?</a><br>

        <div class="remember">
        <input id="remember" type="checkbox" name="remember">
        <label for="remember">Se souvenir de moi</label><br>
        </div>

        <input id="submit" type="submit" value="Se connecter">
    </form>

    <div class="separator color-primary"></div>

    <span>Pas de compte? <a href="/register">S'inscrire</a></span>
</div>
</body>
</html>