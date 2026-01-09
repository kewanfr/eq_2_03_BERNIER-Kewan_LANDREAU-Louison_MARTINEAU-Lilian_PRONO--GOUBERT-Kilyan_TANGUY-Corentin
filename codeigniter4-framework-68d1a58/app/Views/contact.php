<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - PommeHub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(to bottom, #f5f5dc 0%, #d2b48c 100%);
            background-attachment: fixed;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .contact-card {
            background: rgba(255,255,255,0.95);
            padding: 40px;
            margin: 20px 0;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        h1 {
            color: #c41e3a;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 10px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item strong {
            color: #8b4513;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #8b4513;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #8bc34a;
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #c41e3a;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background: #a01828;
            transform: translateY(-2px);
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #c41e3a;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?= view('header') ?>
    
    <div class="container">
        <div class="contact-card">
            <h1>📧 Contactez-nous</h1>
            <p class="subtitle">Une question ? Une remarque ? N'hésitez pas à nous contacter !</p>
            
            <div class="contact-info">
                <div class="info-item">
                    <span>📍</span>
                    <div>
                        <strong>Adresse:</strong><br>
                        123 Route du Cidre<br>
                        29000 Quimper, Bretagne
                    </div>
                </div>
                <div class="info-item">
                    <span>📞</span>
                    <div>
                        <strong>Téléphone:</strong><br>
                        02 98 XX XX XX
                    </div>
                </div>
                <div class="info-item">
                    <span>✉️</span>
                    <div>
                        <strong>Email:</strong><br>
                        contact@pommehub.fr
                    </div>
                </div>
                <div class="info-item">
                    <span>🕐</span>
                    <div>
                        <strong>Horaires:</strong><br>
                        Lun-Ven: 9h-18h<br>
                        Sam: 9h-12h
                    </div>
                </div>
            </div>
            
            <?php if (session()->has('success')): ?>
                <div class="alert alert-success">
                    <?= session('success') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-error">
                    <ul style="margin-left: 20px;">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="post" action="/contact/send">
                <div class="form-group">
                    <label>Nom complet *</label>
                    <input type="text" name="name" value="<?= old('name') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="<?= old('email') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Sujet *</label>
                    <input type="text" name="subject" value="<?= old('subject') ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="message" required><?= old('message') ?></textarea>
                </div>
                
                <button type="submit" class="btn-submit">Envoyer le message</button>
            </form>
            
            <a href="/" class="back-link">← Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
