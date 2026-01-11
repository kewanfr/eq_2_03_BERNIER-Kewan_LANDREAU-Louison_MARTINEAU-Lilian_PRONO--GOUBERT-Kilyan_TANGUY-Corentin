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
    .delivery-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }
    .delivery-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .delivery-option:hover {
        border-color: #8bc34a;
        background: #f1f8e9;
    }
    .delivery-option input[type="radio"] {
        margin-right: 15px;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    .delivery-option.selected {
        border-color: #8bc34a;
        background: #f1f8e9;
    }
    .delivery-info {
        flex: 1;
    }
    .delivery-name {
        font-weight: bold;
        font-size: 1.1em;
        color: #8b4513;
    }
    .delivery-desc {
        color: #666;
        font-size: 0.9em;
        margin-top: 5px;
    }
    .delivery-price {
        font-size: 1.2em;
        font-weight: bold;
        color: #c41e3a;
    }
</style>

<div class="checkout-container">
    <h1 class="checkout-title">Confirmation de commande</h1>
    
    <div class="delivery-section">
        <h2>Mode de livraison</h2>
        <p style="color: #666; margin-bottom: 15px;">Choisissez votre mode de livraison préféré :</p>
        
        <label class="delivery-option" onclick="selectDelivery('pickup', 0)">
            <input type="radio" name="delivery_method" value="pickup" checked onchange="updateTotal()">
            <div class="delivery-info">
                <div class="delivery-name">🏪 Retrait à la cidrerie</div>
                <div class="delivery-desc">Récupérez votre commande directement sur place</div>
            </div>
            <div class="delivery-price">Gratuit</div>
        </label>
        
        <label class="delivery-option" onclick="selectDelivery('local_delivery', 7.50)">
            <input type="radio" name="delivery_method" value="local_delivery" onchange="updateTotal()">
            <div class="delivery-info">
                <div class="delivery-name">🚗 Livraison locale</div>
                <div class="delivery-desc">Livraison dans un rayon de 30 km (délai : 3-5 jours)</div>
            </div>
            <div class="delivery-price">7,50 €</div>
        </label>
        
        <label class="delivery-option" onclick="selectDelivery('carrier_delivery', 15)">
            <input type="radio" name="delivery_method" value="carrier_delivery" onchange="updateTotal()">
            <div class="delivery-info">
                <div class="delivery-name">📦 Livraison transporteur</div>
                <div class="delivery-desc">Livraison partout en France (délai : 2-3 jours)</div>
            </div>
            <div class="delivery-price">15,00 €</div>
        </label>
    </div>
    
    <div class="cart-summary">
        <h2>Récapitulatif</h2>
        <?php foreach ($items as $item): ?>
            <div class="cart-item">
                <span><?= esc($item['name']) ?> x <?= $item['quantity'] ?></span>
                <span><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
            </div>
        <?php endforeach; ?>
        
        <div class="cart-item" style="font-weight: bold; border-top: 2px solid #8b4513; margin-top: 10px; padding-top: 15px;">
            <span>Sous-total produits</span>
            <span id="subtotal"><?= number_format($total, 2) ?> €</span>
        </div>
        
        <div class="cart-item" style="font-weight: bold;">
            <span>Frais de livraison</span>
            <span id="delivery-cost">Gratuit</span>
        </div>
        
        <div class="total">
            Total TTC: <span id="total-amount"><?= number_format($total, 2) ?> €</span>
        </div>
    </div>
    
    <form action="/order/place" method="post" id="checkout-form">
        <input type="hidden" name="delivery_method" id="delivery_method_input" value="pickup">
        <input type="hidden" name="delivery_cost" id="delivery_cost_input" value="0">
        <button type="submit" class="btn-confirm">Valider la commande</button>
    </form>
</div>

<script>
    const subtotal = <?= $total ?>;
    let deliveryCost = 0;
    
    function selectDelivery(method, cost) {
        // Met à jour les inputs cachés
        document.getElementById('delivery_method_input').value = method;
        document.getElementById('delivery_cost_input').value = cost;
        deliveryCost = cost;
        
        // Met à jour l'affichage
        const deliveryCostElem = document.getElementById('delivery-cost');
        if (cost === 0) {
            deliveryCostElem.textContent = 'Gratuit';
        } else {
            deliveryCostElem.textContent = cost.toFixed(2) + ' €';
        }
        
        // Met à jour le total
        const totalAmount = subtotal + deliveryCost;
        document.getElementById('total-amount').textContent = totalAmount.toFixed(2) + ' €';
        
        // Met à jour les styles des options
        document.querySelectorAll('.delivery-option').forEach(option => {
            option.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');
    }
    
    function updateTotal() {
        const selectedRadio = document.querySelector('input[name="delivery_method"]:checked');
        const method = selectedRadio.value;
        
        // Prix fixes
        let cost = 0;
        if (method === 'local_delivery') {
            cost = 7.50;
        } else if (method === 'carrier_delivery') {
            cost = 15.00;
        }
        
        selectDelivery(method, cost);
    }
    
    // Sélectionne la première option par défaut
    document.querySelector('.delivery-option').classList.add('selected');
</script>

</body>
</html>
