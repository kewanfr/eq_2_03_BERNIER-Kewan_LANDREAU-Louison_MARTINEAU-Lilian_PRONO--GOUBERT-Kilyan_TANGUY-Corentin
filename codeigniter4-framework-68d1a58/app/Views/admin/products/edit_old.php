<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .admin-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h2 { margin-bottom: 30px; font-size: 20px; }
        .sidebar nav a { display: block; padding: 12px 15px; color: white; text-decoration: none; margin-bottom: 5px; border-radius: 4px; }
        .sidebar nav a:hover, .sidebar nav a.active { background: #34495e; }
        .main-content { flex: 1; padding: 30px; }
        .header { background: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 600px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .btn { padding: 10px 20px; text-decoration: none; border-radius: 4px; font-size: 14px; border: none; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-secondary { background: #6c757d; color: white; margin-left: 10px; }
        .logout { margin-top: 30px; padding-top: 20px; border-top: 1px solid #34495e; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <h2>📊 Back Office</h2>
            <nav>
                <a href="/admin">Dashboard</a>
                <a href="/admin/products" class="active">Produits</a>
                <a href="/admin/orders">Commandes</a>
                <a href="/admin/stock">Gestion Stock</a>
                <a href="/admin/users">Utilisateurs</a>
            </nav>
            <div class="logout">
                <a href="/" style="color: white; text-decoration: none;">← Site public</a><br><br>
                <a href="/auth/logout" style="color: #e74c3c; text-decoration: none;">Déconnexion</a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>Modifier le Produit</h1>
            </div>

            <div class="form-container">
                <form method="post" action="/admin/products/update/<?= $product['id'] ?>">
                    <div class="form-group">
                        <label>Nom du produit</label>
                        <input type="text" name="name" value="<?= esc($product['name']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="desc" required><?= esc($product['desc']) ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Prix (€)</label>
                        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Quantité en stock</label>
                        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="/admin/products" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
