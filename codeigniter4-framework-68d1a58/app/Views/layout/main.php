<?php ?>
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
<?= view('cookies'); ?>
<?= view('debug/logout'); ?>
<body>
    <h1>
        <?php
        //Script pour tester la bannière de cookies.
        //TODO: le vrai layout principal de la page, CAD la disposition des produits.

        helper('cookies');

        if (get_cookie('acceptcookies') == null) {
            echo "choice not made";
        } else if (get_cookie('acceptcookies') == 0) {
            echo "cookies declined";
        } else if (get_cookie('acceptcookies') == 1) {
            echo "cookies accepted";
        } else {
            echo "unknown";
        }
        ?>
    </h1>
</body>
