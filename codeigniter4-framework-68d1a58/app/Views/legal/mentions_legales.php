<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales - TechnoPomme</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(135deg, #fff9e9 0%, #f5f0e1 100%);
            color: #333;
            line-height: 1.8;
        }
        
        .legal-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 30px 80px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #436850;
            text-decoration: none;
            font-size: 1em;
            margin-bottom: 30px;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #c41e3a;
        }
        
        .legal-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 3px solid #436850;
        }
        
        .legal-header h1 {
            font-size: 2.5em;
            color: #12372a;
            margin-bottom: 10px;
        }
        
        .legal-header p {
            color: #666;
            font-style: italic;
        }
        
        .legal-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #8bc34a;
        }
        
        .legal-section h2 {
            color: #12372a;
            font-size: 1.4em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .legal-section h2 .icon {
            font-size: 1.3em;
        }
        
        .legal-section h3 {
            color: #436850;
            font-size: 1.1em;
            margin: 20px 0 10px;
        }
        
        .legal-section p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        .legal-section ul {
            margin: 15px 0;
            padding-left: 25px;
        }
        
        .legal-section ul li {
            margin-bottom: 10px;
        }
        
        .info-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .info-box strong {
            color: #12372a;
        }
        
        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .contact-item .icon {
            font-size: 1.2em;
        }
        
        .update-date {
            text-align: center;
            color: #888;
            font-size: 0.9em;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        @media (max-width: 768px) {
            .legal-container {
                padding: 20px 15px 60px;
            }
            
            .legal-header h1 {
                font-size: 1.8em;
            }
            
            .legal-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?= view('header') ?>
    
    <div class="legal-container">
        <a href="/" class="back-link">← Retour à l'accueil</a>
        
        <div class="legal-header">
            <h1>🍎 Mentions Légales</h1>
            <p>Conformément aux dispositions de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique</p>
        </div>

        <!-- Éditeur du site -->
        <section class="legal-section">
            <h2><span class="icon">🏢</span> 1. Éditeur du site</h2>
            <div class="info-box">
                <p><strong>Raison sociale :</strong> TechnoPomme SARL</p>
                <p><strong>Forme juridique :</strong> Société à Responsabilité Limitée (SARL)</p>
                <p><strong>Capital social :</strong> 50 000 €</p>
                <p><strong>Siège social :</strong> 123 Route des Vergers, 14000 Caen, France</p>
                <p><strong>SIRET :</strong> 123 456 789 00012</p>
                <p><strong>RCS :</strong> Caen B 123 456 789</p>
                <p><strong>Numéro de TVA intracommunautaire :</strong> FR 12 123456789</p>
            </div>
            
            <h3>Contact</h3>
            <div class="contact-info">
                <div class="contact-item">
                    <span class="icon">📞</span>
                    <span>02 31 00 00 00</span>
                </div>
                <div class="contact-item">
                    <span class="icon">✉️</span>
                    <span>contact@technopomme.fr</span>
                </div>
            </div>
            
            <h3>Directeur de la publication</h3>
            <p>Monsieur Jean DUPONT, en qualité de Gérant de la société TechnoPomme SARL.</p>
        </section>

        <!-- Hébergeur -->
        <section class="legal-section">
            <h2><span class="icon">🌐</span> 2. Hébergement</h2>
            <div class="info-box">
                <p><strong>Hébergeur :</strong> OVH SAS</p>
                <p><strong>Adresse :</strong> 2 rue Kellermann, 59100 Roubaix, France</p>
                <p><strong>Téléphone :</strong> 1007 (numéro non surtaxé)</p>
                <p><strong>Site web :</strong> www.ovh.com</p>
            </div>
        </section>

        <!-- Propriété intellectuelle -->
        <section class="legal-section">
            <h2><span class="icon">©️</span> 3. Propriété intellectuelle</h2>
            <p>L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.</p>
            
            <p>La reproduction de tout ou partie de ce site sur un support électronique quel qu'il soit est formellement interdite sauf autorisation expresse du directeur de la publication.</p>
            
            <h3>Éléments protégés</h3>
            <ul>
                <li>Le logo et la marque "TechnoPomme"</li>
                <li>L'ensemble des textes, images et photographies</li>
                <li>La charte graphique et le design du site</li>
                <li>Les bases de données constituant le site</li>
            </ul>
        </section>

        <!-- Données personnelles -->
        <section class="legal-section">
            <h2><span class="icon">🔒</span> 4. Protection des données personnelles</h2>
            <p>Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés du 6 janvier 1978 modifiée, vous disposez des droits suivants concernant vos données personnelles :</p>
            
            <ul>
                <li><strong>Droit d'accès :</strong> obtenir la confirmation que des données vous concernant sont traitées et en obtenir une copie</li>
                <li><strong>Droit de rectification :</strong> demander la correction des données inexactes</li>
                <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données dans certaines conditions</li>
                <li><strong>Droit à la limitation :</strong> demander la limitation du traitement de vos données</li>
                <li><strong>Droit à la portabilité :</strong> recevoir vos données dans un format structuré</li>
                <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos données</li>
            </ul>
            
            <h3>Responsable du traitement</h3>
            <p>TechnoPomme SARL, représentée par Monsieur Jean DUPONT.</p>
            
            <h3>Délégué à la protection des données (DPO)</h3>
            <p>Pour exercer vos droits ou pour toute question relative à la protection de vos données, vous pouvez nous contacter :</p>
            <div class="info-box">
                <p><strong>Email :</strong> dpo@technopomme.fr</p>
                <p><strong>Courrier :</strong> TechnoPomme - DPO, 123 Route des Vergers, 14000 Caen</p>
            </div>
            
            <p>Vous pouvez également introduire une réclamation auprès de la CNIL (Commission Nationale de l'Informatique et des Libertés) : <a href="https://www.cnil.fr" target="_blank" style="color: #436850;">www.cnil.fr</a></p>
        </section>

        <!-- Cookies -->
        <section class="legal-section">
            <h2><span class="icon">🍪</span> 5. Cookies</h2>
            <p>Le site TechnoPomme utilise des cookies pour améliorer l'expérience utilisateur et mesurer l'audience. Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, tablette, smartphone) lors de votre visite sur notre site.</p>
            
            <h3>Types de cookies utilisés</h3>
            <ul>
                <li><strong>Cookies essentiels :</strong> nécessaires au fonctionnement du site (panier, connexion)</li>
                <li><strong>Cookies de performance :</strong> pour analyser l'utilisation du site et améliorer ses performances</li>
                <li><strong>Cookies de préférences :</strong> pour mémoriser vos choix (langue, vérification d'âge)</li>
            </ul>
            
            <p>Vous pouvez gérer vos préférences en matière de cookies via notre bandeau de consentement ou les paramètres de votre navigateur.</p>
            
            <p>Pour plus d'informations, consultez notre <a href="/cookies" style="color: #436850;">Politique de cookies</a>.</p>
        </section>

        <!-- Vente d'alcool -->
        <section class="legal-section">
            <h2><span class="icon">🍺</span> 6. Vente d'alcool</h2>
            <p><strong>L'abus d'alcool est dangereux pour la santé. À consommer avec modération.</strong></p>
            
            <p>Conformément aux articles L.3342-1 et suivants du Code de la santé publique, la vente d'alcool est interdite aux mineurs de moins de 18 ans. La preuve de la majorité de l'acheteur est exigée au moment de la vente en ligne.</p>
            
            <div class="info-box">
                <p>⚠️ <strong>Attention :</strong> En passant commande sur notre site, vous certifiez avoir l'âge légal pour acheter des boissons alcoolisées dans votre pays de résidence.</p>
            </div>
            
            <p>TechnoPomme se réserve le droit de demander une pièce d'identité lors de la livraison pour vérifier l'âge du destinataire.</p>
        </section>

        <!-- Responsabilité -->
        <section class="legal-section">
            <h2><span class="icon">⚖️</span> 7. Limitation de responsabilité</h2>
            <p>TechnoPomme s'efforce d'assurer au mieux de ses possibilités l'exactitude et la mise à jour des informations diffusées sur ce site. Toutefois, TechnoPomme décline toute responsabilité :</p>
            
            <ul>
                <li>En cas d'interruption ou d'inaccessibilité du site</li>
                <li>En cas de survenance de bugs</li>
                <li>En cas d'inexactitude ou d'omission dans les informations disponibles sur le site</li>
                <li>En cas de dommages résultant d'une intrusion frauduleuse d'un tiers</li>
            </ul>
            
            <p>Les liens hypertextes présents sur ce site peuvent renvoyer vers d'autres sites internet. TechnoPomme décline toute responsabilité concernant le contenu de ces sites externes.</p>
        </section>

        <!-- Droit applicable -->
        <section class="legal-section">
            <h2><span class="icon">🇫🇷</span> 8. Droit applicable et juridiction compétente</h2>
            <p>Les présentes mentions légales sont régies par le droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>
            
            <p>Conformément aux dispositions du Code de la consommation concernant le règlement amiable des litiges, TechnoPomme adhère au Service du Médiateur du e-commerce de la FEVAD (Fédération du e-commerce et de la vente à distance) dont les coordonnées sont les suivantes :</p>
            
            <div class="info-box">
                <p><strong>Médiateur de la consommation :</strong></p>
                <p>Médiateur du e-commerce de la FEVAD</p>
                <p>60 Rue La Boétie, 75008 Paris</p>
                <p><a href="https://www.mediateurfevad.fr" target="_blank" style="color: #436850;">www.mediateurfevad.fr</a></p>
            </div>
        </section>

        <!-- Crédits -->
        <section class="legal-section">
            <h2><span class="icon">🎨</span> 9. Crédits</h2>
            <p><strong>Conception et développement :</strong> Équipe TechnoPomme</p>
            <p><strong>Photographies :</strong> © TechnoPomme - Tous droits réservés</p>
            <p><strong>Icônes :</strong> Emojis standards Unicode</p>
        </section>

        <p class="update-date">
            📅 Dernière mise à jour : <?= date('d/m/Y') ?>
        </p>
    </div>

    <?= view('footer') ?>
</body>
</html>
