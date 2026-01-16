<footer class="site-footer">
    <div class="footer-container">
        <!-- Logo et description -->
        <div class="footer-section footer-brand">
            <div class="footer-logo">
                <img src="/assets/img/logo.png" alt="TechnoPomme" class="footer-logo-img">
                <span class="footer-logo-text">TechnoPomme</span>
            </div>
            <p class="footer-description">
                Cidrerie artisanale depuis 1985. Nous produisons des cidres et jus de pomme de qualité, 
                issus de vergers traditionnels normands.
            </p>
            <div class="footer-social">
                <a href="#" class="social-link" title="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                </a>
                <a href="#" class="social-link" title="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                </a>
                <a href="#" class="social-link" title="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Liens rapides -->
        <div class="footer-section">
            <h4 class="footer-title">
                <span class="footer-icon">🍎</span> Navigation
            </h4>
            <ul class="footer-links">
                <li><a href="/">Accueil</a></li>
                <li><a href="/products">Nos produits</a></li>
                <li><a href="/contact">Nous contacter</a></li>
                <li><a href="/orders">Mes commandes</a></li>
            </ul>
        </div>
        
        <!-- Nos produits -->
        <div class="footer-section">
            <h4 class="footer-title">
                <span class="footer-icon">🍏</span> Nos Produits
            </h4>
            <ul class="footer-links">
                <li><a href="/products?category=cidre">Cidres</a></li>
                <li><a href="/products?category=jus">Jus de pomme</a></li>
                <li><a href="/products?category=vinaigre">Vinaigres</a></li>
                <li><a href="/products?category=coffret">Coffrets cadeaux</a></li>
            </ul>
        </div>
        
        <!-- Contact -->
        <div class="footer-section">
            <h4 class="footer-title">
                <span class="footer-icon">📍</span> Contact
            </h4>
            <div class="footer-contact">
                <p>
                    <strong>Cidrerie TechnoPomme</strong><br>
                    123 Route des Vergers<br>
                    14000 Caen, Normandie
                </p>
                <p>
                    <span class="contact-icon">📞</span> 02 31 00 00 00<br>
                    <span class="contact-icon">✉️</span> contact@technopomme.fr
                </p>
                <p class="footer-hours">
                    <strong>Horaires :</strong><br>
                    Lun-Ven : 9h - 18h<br>
                    Sam : 10h - 16h
                </p>
            </div>
        </div>
    </div>
    
    <!-- Barre du bas -->
    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p class="copyright">
                🍏 © <?= date('Y') ?> TechnoPomme - Tous droits réservés
            </p>
            <div class="footer-bottom-links">
                <a href="/cookies">Politique de cookies</a>
                <span class="separator">|</span>
                <a href="/mentions-legales">Mentions légales</a>
                <span class="separator">|</span>
                <a href="/cgv">CGV</a>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Sticky footer - moins intrusif */
    html {
        overflow-x: hidden; /* Évite l'espace horizontal */
    }
    
    body {
        display: flex !important;
        flex-direction: column !important;
        min-height: 100vh !important;
        /* Garde le padding-top existant pour le header fixe */
    }
    
    .site-footer {
        background: linear-gradient(135deg, #12372a 0%, #436850 100%);
        color: #fff9e9;
        margin-top: 80px;
        font-family: 'Georgia', serif;
        width: 100vw;
        box-sizing: border-box;
        position : absolute;
    }
    
    .site-footer .footer-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 50px 30px 30px;
    }
    
    .site-footer .footer-section {
        padding: 10px;
    }
    
    .site-footer .footer-brand {
        max-width: 320px;
    }
    
    .site-footer .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }
    
    .site-footer .footer-logo-img {
        width: 50px;
        height: 50px;
        object-fit: contain;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }
    
    .site-footer .footer-logo-text {
        font-size: 1.5em;
        font-weight: bold;
        background: linear-gradient(135deg, #c41e3a, #8bc34a);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .site-footer .footer-description {
        font-size: 0.9em;
        line-height: 1.6;
        opacity: 0.9;
        margin-bottom: 20px;
    }
    
    .site-footer .footer-social {
        display: flex;
        gap: 12px;
    }
    
    .site-footer .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        color: #fff9e9;
        transition: all 0.3s ease;
    }
    
    .site-footer .social-link:hover {
        background: linear-gradient(135deg, #c41e3a, #8bc34a);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .site-footer .footer-title {
        font-size: 1.15em;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(139, 195, 74, 0.5);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .site-footer .footer-icon {
        font-size: 1.2em;
    }
    
    .site-footer .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .site-footer .footer-links li {
        margin-bottom: 12px;
    }
    
    .site-footer .footer-links a {
        color: #fff9e9;
        text-decoration: none;
        font-size: 0.95em;
        opacity: 0.85;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .site-footer .footer-links a::before {
        content: '›';
        font-size: 1.2em;
        color: #8bc34a;
        transition: transform 0.3s;
    }
    
    .site-footer .footer-links a:hover {
        opacity: 1;
        color: #8bc34a;
        padding-left: 5px;
    }
    
    .site-footer .footer-links a:hover::before {
        transform: translateX(3px);
    }
    
    .site-footer .footer-contact {
        font-size: 0.9em;
        line-height: 1.8;
    }
    
    .site-footer .footer-contact p {
        margin-bottom: 15px;
    }
    
    .site-footer .contact-icon {
        margin-right: 5px;
    }
    
    .site-footer .footer-hours {
        background: rgba(255,255,255,0.1);
        padding: 12px 15px;
        border-radius: 8px;
        border-left: 3px solid #8bc34a;
    }
    
    .site-footer .footer-bottom {
        background: rgba(0,0,0,0.2);
        padding: 20px 30px;
        margin-top: 20px;
    }
    
    .site-footer .footer-bottom-content {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .site-footer .copyright {
        font-size: 0.9em;
        opacity: 0.9;
    }
    
    .site-footer .footer-bottom-links {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .site-footer .footer-bottom-links a {
        color: #fff9e9;
        text-decoration: none;
        font-size: 0.85em;
        opacity: 0.8;
        transition: all 0.3s;
    }
    
    .site-footer .footer-bottom-links a:hover {
        opacity: 1;
        color: #8bc34a;
    }
    
    .site-footer .footer-bottom-links .separator {
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .site-footer .footer-container {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 30px 20px;
        }
        
        .site-footer .footer-brand {
            max-width: 100%;
        }
        
        .site-footer .footer-logo {
            justify-content: center;
        }
        
        .site-footer .footer-social {
            justify-content: center;
        }
        
        .site-footer .footer-links a::before {
            display: none;
        }
        
        .site-footer .footer-bottom-content {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
