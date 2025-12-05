<?php helper('cookie') ?>

<?php if(get_cookie('acceptcookies') == null): ?>
<div id="cookieBanner">
    <h1>Est-ce que vous acceptez les cookies?</h1>
    <button id="acceptcookies" class="cookie-button">accepter</button>
    <button id="denycookies" class="cookie-button">refuser</button>

    <script>
        document.getElementById("acceptcookies").onclick = () => {
            fetch('<?= site_url("cookies/accept") ?>')
                .then(r => r.json())
                .then(() => document.getElementById("cookieBanner").remove())
        }

        document.getElementById("denycookies").onclick = () => {
            fetch('<?= site_url("cookies/decline") ?>')
                .then(r => r.json())
                .then(() => document.getElementById("cookieBanner").remove())
        }
    </script>
</div>

<style>
    #cookieBanner {
        position: fixed;
        bottom: 0; /* stick to bottom */
        left: 0;
        width: 100%;
        padding: 15px 20px;
        background: rgba(20,20,20,0.95);
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;

        z-index: 999999; /* always on top */
        box-shadow: 0 -4px 12px rgba(0,0,0,0.25);
    }

    #cookieBanner > * {
        margin: 20px;
    }

    .cookie-button {
        background: #4caf50;
        color: white;
        border: none;
        padding: 8px 18px;
        cursor: pointer;
        border-radius: 4px;
    }

    .cookie-button:hover {
        opacity: 0.9;
    }
</style>

<?php endif;?>

