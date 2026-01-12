<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            min-height: 100vh;
            padding: 20px;
        }
        
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .sidebar ul {
            list-style: none;
        }
        
        .sidebar ul li {
            margin-bottom: 10px;
        }
        
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .sidebar ul li a:hover {
            background-color: #34495e;
        }
        
        .sidebar ul li a.active {
            background-color: #34495e;
        }
        
        .content {
            flex: 1;
            padding: 30px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 800px;
        }
        
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .alert {
            padding: 12px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
        }
        
        .form-group textarea {
            resize: vertical;
        }
        
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Administration</h2>
        <ul>
            <li><a href="/admin">Tableau de bord</a></li>
            <li><a href="/admin/products" class="active">Produits</a></li>
            <li><a href="/admin/orders">Commandes</a></li>
            <li><a href="/admin/users">Utilisateurs</a></li>
            <li><a href="/admin/stock">Stock</a></li>
            <li><a href="/">Retour au site</a></li>
        </ul>
    </div>
    
    <div class="content">
        <div class="card">
            <h1>Ajouter un produit</h1>
            
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                </div>
            <?php endif; ?>
            
            <form action="/product/add/add" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nom du produit:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                
                <div class="form-group">
                    <label for="desc">Description:</label>
                    <textarea name="desc" id="desc" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category">Catégorie:</label>
                    <select name="category" id="category">
                        <option value="">-- Sélectionner --</option>
                        <option value="cidres">Cidres</option>
                        <option value="jus">Jus</option>
                        <option value="vinaigre">Vinaigre</option>
                        <option value="accessoires">Accessoires</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="tags">Tags (séparés par des virgules):</label>
                    <input type="text" name="tags" id="tags" placeholder="bio, tradition, brut...">
                    <small style="color: #666; font-size: 0.9em;">Exemples: bio, tradition, monovariétal, doux, brut, artisanal</small>
                </div>
                
                <div class="form-group">
                    <label for="price">Prix (€):</label>
                    <input type="number" name="price" id="price" step="0.01" min="0" required placeholder="ex: 19.99">
                </div>

                <div class="form-group">
                    <label for="quantity">Quantité:</label>
                    <input type="number" name="qtt" id="quantity" min="0">
                </div>
                
                <div class="form-group">
                    <label for="userfile">Image du produit:</label>
                    <input type="file" name="userfile" id="userfile" accept="image/*" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Ajouter le produit</button>
                <a href="/admin/products" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</body>
</html>