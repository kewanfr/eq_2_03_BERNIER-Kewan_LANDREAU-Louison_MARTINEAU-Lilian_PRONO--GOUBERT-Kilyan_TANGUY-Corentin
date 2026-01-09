<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cidrerie</title>
    <style>
        /* Thème cidrerie pour admin */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(to bottom, #f5f5dc 0%, #d2b48c 100%);
            background-attachment: fixed;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #c41e3a 0%, #8bc34a 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 {
            font-size: 1.8em;
        }
        
        .admin-nav {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 1;
            justify-content: center;
        }
        
        .admin-header nav {
            display: flex;
            gap: 10px;
        }
        
        .admin-header nav a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
        }
        
        .admin-header nav a:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .user-info {
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-details {
            text-align: right;
        }
        
        .user-details .username {
            font-weight: bold;
            font-size: 1.1em;
        }
        
        .user-details .roles {
            font-size: 0.85em;
            opacity: 0.9;
        }
        
        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            background: rgba(255,255,255,0.3);
            margin-left: 5px;
            font-size: 0.8em;
        }
        
        .btn-logout {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .btn-logout:hover {
            background: #c82333;
            border-color: white;
        }
        
        .admin-container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        }
        
        .admin-title {
            font-size: 2em;
            color: #8b4513;
            margin-bottom: 20px;
            border-bottom: 3px solid #8bc34a;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background: #8bc34a;
            color: white;
            padding: 12px;
            text-align: left;
        }
        
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        
        tr:hover {
            background: #f5deb3;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
            color: white;
            font-weight: bold;
        }
        
        .btn-primary {
            background: #c41e3a;
        }
        
        .btn-primary:hover {
            background: #a01828;
        }
        
        .btn-success {
            background: #8bc34a;
        }
        
        .btn-success:hover {
            background: #6fa02f;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        input[type="text"], input[type="number"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #8bc34a;
            border-radius: 5px;
            font-size: 1em;
            margin: 5px 0;
        }
        
        label {
            font-weight: bold;
            color: #8b4513;
            display: block;
            margin-top: 10px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div>
            <h1>Administration Cidrerie</h1>
        </div>
        <div class="admin-nav">
            <nav>
                <a href="/admin">Dashboard</a>
                
                <?php 
                $userRoles = session()->get('user_roles') ?? [];
                $hasPermission = function($permission) use ($userRoles) {
                    foreach ($userRoles as $role) {
                        if (\App\Enums\RoleInterne::hasPermission($role, $permission)) {
                            return true;
                        }
                    }
                    return false;
                };
                ?>
                
                <?php if ($hasPermission('manage_products')): ?>
                    <a href="/admin/products">Produits</a>
                <?php endif; ?>
                
                <?php if ($hasPermission('view_orders')): ?>
                    <a href="/admin/orders">Commandes</a>
                <?php endif; ?>
                
                <?php if ($hasPermission('manage_stock')): ?>
                    <a href="/admin/stock">Stock</a>
                <?php endif; ?>
                
                <?php if ($hasPermission('manage_users')): ?>
                    <a href="/admin/users">Utilisateurs</a>
                <?php endif; ?>
                
                <a href="/">Site public</a>
            </nav>
            <div class="user-info">
                <div class="user-details">
                    <div class="username"><?= esc(auth()->user()->username ?? 'Utilisateur') ?></div>
                    <div class="roles">
                        <?php 
                        $userRoles = session()->get('user_roles') ?? ['client'];
                        foreach ($userRoles as $role): 
                        ?>
                            <span class="role-badge"><?= esc(ucfirst($role)) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="/logout" class="btn-logout">Déconnexion</a>
            </div>
        </div>
    </div>
    <div class="admin-container">
