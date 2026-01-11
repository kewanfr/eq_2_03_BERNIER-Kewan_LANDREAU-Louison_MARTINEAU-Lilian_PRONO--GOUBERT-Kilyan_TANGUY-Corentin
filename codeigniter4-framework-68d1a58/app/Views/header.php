    <head>
    <link rel="stylesheet" href="/assets/style/header.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        /* Thème cidrerie - couleurs pommes/nature */
        :root {
            --pomme-rouge: #c41e3a;
            --pomme-verte: #8bc34a;
            --pomme-jaune: #ffc107;
            --terre: #8b4513;
            --bois: #d2691e;
        }
        .headerbar { background: linear-gradient(135deg, var(--pomme-rouge) 0%, var(--pomme-verte) 100%); }
        .btn, button[type="submit"] { background: var(--pomme-rouge) !important; transition: all 0.3s; }
        .btn:hover, button[type="submit"]:hover { background: #a01828 !important; transform: translateY(-2px); }
        .material-symbols-outlined { color: white; }
        
        .user-info-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            font-size: 0.9em;
        }
        
        .user-info-header .username {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 3px;
        }
        
        .user-info-header .roles {
            font-size: 0.85em;
            opacity: 0.95;
            margin-bottom: 5px;
        }
        
        .role-badge-header {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            background: rgba(255,255,255,0.4);
            margin-right: 5px;
            font-size: 0.9em;
            font-weight: bold;
        }
        
        .logout-btn {
            background: #dc3545;
            padding: 4px 12px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 0.85em;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<header class="headerbar">
    <div class="header-content">
        <div class="logo">
            <a href="/">
                <img class="logoimg" src="/assets/img/logo.png" alt="logo">
                <span class="logotext separate">TechnoPomme</span>
            </a>
        </div>
        <div class="navbar">
            <form class="nav" action="/products" method="get">
                <input type="search" name="search" placeholder="Rechercher des produits sur le site" class="navbar input">
                <button type="submit" class="navbar submit"><span class="material-symbols-outlined search">search</span></button>
            </form>
        </div>
        <div class="header links">
            <div class="small-search-container">
                <i class="material-symbols-outlined small-search" style="font-size: 50px;">search</i>
            </div>  
            <?php if (auth()->loggedIn()): ?>
                <?php 
                $userRoleModel = new \App\Models\UserRoleModel();
                $userRoles = $userRoleModel->getUserRoles(auth()->id());
                $username = auth()->user()->username ?? 'Utilisateur';
                
                // Récupère le type de client
                $db = \Config\Database::connect();
                $userInfo = $db->table('users')->where('id', auth()->id())->get()->getRow();
                $customerType = $userInfo->customer_type ?? 'particulier';
                $companyName = $userInfo->company_name ?? null;
                ?>
                <div class="login-link" style="cursor: default;">
                    <i class="material-symbols-outlined loginicon" style="font-size: 50px;">account_circle</i>
                    <span class="login text user-info-header">
                        <span class="username">
                            <?= esc($username) ?>
                            <?php if ($customerType === 'professionnel'): ?>
                                <span style="font-size: 0.8em; color: #ffd700;">⭐ PRO</span>
                            <?php endif; ?>
                        </span>
                        <?php if ($customerType === 'professionnel' && $companyName): ?>
                            <span style="font-size: 0.85em; opacity: 0.9;"><?= esc($companyName) ?></span>
                        <?php endif; ?>
                        <span class="roles">
                            <?php foreach ($userRoles as $role): ?>
                                <span class="role-badge-header"><?= esc(ucfirst($role)) ?></span>
                            <?php endforeach; ?>
                        </span>
                        <a href="/logout" class="logout-btn" style="color: white; text-decoration: none;">Se déconnecter</a>
                    </span>
                </div>
            <?php else: ?>
                <a href="/login" class="login-link">
                    <i class="material-symbols-outlined loginicon" style="font-size: 50px;">account_circle</i>
                    <span class="login text">
                        Mon compte : <br>
                        <strong>Se connecter</strong>
                    </span>
                </a>
            <?php endif; ?>
            <?php if (auth()->loggedIn()): ?>
                <a href="/cart" class="cart-link">
                    <i class="material-symbols-outlined carticon" style="font-size: 50px;">shopping_cart</i>
                    <span class="cart text">
                            Mon panier : <br>
                            <strong id="cart-count">
                                0 articles
                            </strong>
                        </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="header-menu">
        <div class="menu menu-container">
            <a href="/" class="menu about">A propos de nous</a>
        </div>
        <div class="menu menu-container">
            <a href="/products" class="menu products">Nos produits</a>
        </div>
        <div class="menu menu-container">
            <a href="/contact" class="menu contact">Nous contacter</a>
        </div>
        <?php if (auth()->loggedIn()): ?>
            <div class="menu menu-container">
                <a href="/orders" class="menu orders">Mes commandes</a>
            </div>
            <div class="menu menu-container">
                <a href="/profile" class="menu profile">Mon profil</a>
            </div>
            <?php 
            // Affiche le menu admin seulement pour les utilisateurs avec des rôles internes (pas client)
            $internalRoles = array_diff($userRoles, ['client']);
            if (!empty($internalRoles)): 
            ?>
            <div class="menu menu-container">
                <a href="/admin" class="menu admin">Admin</a>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="bottom-separator"></div>
    </div>
</header>

<script>
// Charge le nombre d'articles au chargement
window.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
});

// Fonction pour ajouter au panier
function addToCart(productId, button) {
    const qtyInput = document.getElementById('qty-' + productId);
    const quantity = qtyInput ? qtyInput.value : 1;
    const feedback = button.nextElementSibling;
    
    // Désactive le bouton
    button.disabled = true;
    button.textContent = 'Ajout...';
    
    // Envoie la requête AJAX
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + productId + '&quantity=' + quantity
    })
    .then(response => response.json())
    .then(data => {
        console.log('Réponse du serveur:', data);
        
        // Si redirection nécessaire (non connecté)
        if (data.redirect) {
            alert(data.message || 'Vous devez être connecté pour ajouter au panier');
            window.location.href = data.redirect;
            return;
        }
        
        // Animation de succès
        if (data.success) {
            feedback.style.display = 'inline';
            button.style.background = '#28a745';
            button.textContent = 'Ajouter au panier';
            button.disabled = false;
            
            // Met à jour le compteur
            updateCartCount();
            
            // Cache le feedback après 2 secondes
            setTimeout(() => {
                feedback.style.display = 'none';
            }, 2000);
        } else {
            console.error('Erreur serveur:', data.message);
            button.style.background = '#dc3545';
            button.textContent = data.message || 'Erreur';
            setTimeout(() => {
                button.style.background = '#28a745';
                button.textContent = 'Ajouter au panier';
                button.disabled = false;
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Erreur AJAX:', error);
        button.style.background = '#dc3545';
        button.textContent = 'Erreur réseau';
        setTimeout(() => {
            button.style.background = '#28a745';
            button.textContent = 'Ajouter au panier';
            button.disabled = false;
        }, 2000);
    });
}

// Met à jour le compteur du panier
function updateCartCount() {
    fetch('/cart/count')
    .then(response => response.json())
    .then(data => {
        const countElement = document.getElementById('cart-count');
        if (countElement) {
            countElement.textContent = data.count + ' article' + (data.count > 1 ? 's' : '');
        }
    });
}
</script>

