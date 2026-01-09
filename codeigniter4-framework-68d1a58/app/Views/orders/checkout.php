<?= view('header') ?>

<style>
    .checkout-container {
        max-width: 800px;
        margin: 30px auto;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .checkout-title {
        color: #8b4513;
        margin-bottom: 20px;
    }
    .cart-summary {
        margin-bottom: 30px;
    }
    .cart-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }
    .total {
        font-size: 1.5em;
        font-weight: bold;
        color: #c41e3a;
        text-align: right;
        margin-top: 20px;
    }
    .btn-confirm {
        width: 100%;
        padding: 15px;
        background: #8bc34a;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.2em;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-confirm:hover {
        background: #6fa02f;
    }
</style>

<div class="checkout-container">
    <h1 class="checkout-title">Confirmation de commande</h1>
    
    <div class="cart-summary">
        <h2>Récapitulatif</h2>
        <?php foreach ($items as $item): ?>
            <div class="cart-item">
                <span><?= esc($item['name']) ?> x <?= $item['quantity'] ?></span>
                <span><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
            </div>
        <?php endforeach; ?>
        
        <div class="total">
            Total: <?= number_format($total, 2) ?> €
        </div>
    </div>
    
    <form action="/order/place" method="post">
        <button type="submit" class="btn-confirm">Valider la commande</button>
    </form>
</div>

</body>
</html>
