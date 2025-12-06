<?php ?>

<div>
    <button id="logout-button">logout</button>
    <button id="reset-cookies">reset cookies</button>
    <button id="login-button">login</button>
</div>

<script>
    document.getElementById("logout-button").onclick = () => {
        fetch("<?= site_url("auth/logout") ?> ")
    }

    document.getElementById("login-button").onclick = () => {
        location.replace("<?= site_url("login") ?>")
    }
</script>
