<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales de Vente - TechnoPomme</title>
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
            border-left: 4px solid #c41e3a;
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
        
        .legal-section ul, .legal-section ol {
            margin: 15px 0;
            padding-left: 25px;
        }
        
        .legal-section ul li, .legal-section ol li {
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
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
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
            <h1>🍏 Conditions Générales de Vente</h1>
            <p>Applicables aux ventes réalisées sur le site www.technopomme.fr</p>
        </div>

        <!-- Article 1 -->
        <section class="legal-section">
            <h2><span class="icon">📋</span> Article 1 - Objet</h2>
            <p>Les présentes Conditions Générales de Vente (CGV) régissent les relations contractuelles entre la société TechnoPomme SARL et toute personne physique ou morale souhaitant effectuer un achat via le site internet www.technopomme.fr.</p>
            <p>Toute commande passée sur le site implique l'acceptation préalable et sans réserve des présentes CGV.</p>
        </section>

        <!-- Article 2 -->
        <section class="legal-section">
            <h2><span class="icon">🏢</span> Article 2 - Identité du vendeur</h2>
            <div class="info-box">
                <p><strong>TechnoPomme SARL</strong></p>
                <p>Capital social : 50 000 €</p>
                <p>Siège social : 123 Route des Vergers, 14000 Caen</p>
                <p>SIRET : 123 456 789 00012</p>
                <p>Téléphone : 02 31 00 00 00</p>
                <p>Email : contact@technopomme.fr</p>
            </div>
        </section>

        <!-- Article 3 -->
        <section class="legal-section">
            <h2><span class="icon">🍎</span> Article 3 - Produits</h2>
            <p>Les produits proposés à la vente sont décrits et présentés avec la plus grande exactitude possible. Toutefois, des erreurs ou omissions peuvent survenir.</p>
            <p>Les photographies des produits sont les plus fidèles possibles mais ne peuvent assurer une similitude parfaite avec le produit offert.</p>
            
            <div class="warning-box">
                <p>⚠️ <strong>Vente d'alcool :</strong> Conformément à la législation en vigueur, la vente d'alcool est interdite aux mineurs de moins de 18 ans. L'acheteur certifie être majeur au moment de la commande.</p>
            </div>
        </section>

        <!-- Article 4 -->
        <section class="legal-section">
            <h2><span class="icon">💰</span> Article 4 - Prix</h2>
            <p>Les prix sont indiqués en euros et s'entendent toutes taxes comprises (TTC), hors frais de livraison.</p>
            <p>TechnoPomme se réserve le droit de modifier ses prix à tout moment, étant entendu que le prix applicable est celui indiqué au moment de la validation de la commande.</p>
            
            <h3>Taux de TVA applicables</h3>
            <ul>
                <li>Cidres et boissons alcoolisées : TVA 20%</li>
                <li>Jus de fruits : TVA 5,5%</li>
                <li>Vinaigres et produits alimentaires : TVA 5,5%</li>
            </ul>
        </section>

        <!-- Article 5 -->
        <section class="legal-section">
            <h2><span class="icon">🛒</span> Article 5 - Commandes</h2>
            <p>La commande est validée après les étapes suivantes :</p>
            <ol>
                <li>Sélection des produits et ajout au panier</li>
                <li>Vérification du contenu du panier</li>
                <li>Identification du client (connexion ou création de compte)</li>
                <li>Choix du mode de livraison</li>
                <li>Validation du paiement</li>
                <li>Confirmation de commande par email</li>
            </ol>
            
            <p>TechnoPomme se réserve le droit de refuser toute commande pour motif légitime, notamment en cas de problème de paiement ou de litige antérieur.</p>
        </section>

        <!-- Article 6 -->
        <section class="legal-section">
            <h2><span class="icon">💳</span> Article 6 - Paiement</h2>
            <p>Le paiement s'effectue en ligne au moment de la commande par les moyens suivants :</p>
            <ul>
                <li>Carte bancaire (Visa, Mastercard)</li>
                <li>PayPal</li>
                <li>Virement bancaire (pour les professionnels)</li>
            </ul>
            
            <p>Les transactions sont sécurisées par un protocole SSL. TechnoPomme ne conserve pas les données bancaires des clients.</p>
        </section>

        <!-- Article 7 -->
        <section class="legal-section">
            <h2><span class="icon">🚚</span> Article 7 - Livraison</h2>
            <p>Les livraisons sont effectuées en France métropolitaine uniquement.</p>
            
            <h3>Délais de livraison</h3>
            <ul>
                <li>Livraison standard : 3 à 5 jours ouvrés</li>
                <li>Livraison express : 24 à 48h</li>
                <li>Retrait en cidrerie : disponibilité sous 24h</li>
            </ul>
            
            <h3>Frais de livraison</h3>
            <ul>
                <li>Gratuit à partir de 50€ d'achat</li>
                <li>Livraison standard : 5,90€</li>
                <li>Livraison express : 9,90€</li>
                <li>Retrait en cidrerie : gratuit</li>
            </ul>
            
            <p>En cas de retard de livraison supérieur à 7 jours, le client peut annuler sa commande et obtenir un remboursement intégral.</p>
        </section>

        <!-- Article 8 -->
        <section class="legal-section">
            <h2><span class="icon">↩️</span> Article 8 - Droit de rétractation</h2>
            <p>Conformément à l'article L.221-18 du Code de la consommation, vous disposez d'un délai de 14 jours à compter de la réception de votre commande pour exercer votre droit de rétractation, sans avoir à justifier de motifs ni à payer de pénalités.</p>
            
            <h3>Conditions de retour</h3>
            <ul>
                <li>Les produits doivent être retournés dans leur emballage d'origine, non ouverts</li>
                <li>Les frais de retour sont à la charge du client</li>
                <li>Le remboursement intervient sous 14 jours après réception des produits</li>
            </ul>
            
            <div class="warning-box">
                <p>⚠️ <strong>Exception :</strong> Conformément à l'article L.221-28 du Code de la consommation, le droit de rétractation ne s'applique pas aux produits susceptibles de se détériorer ou de se périmer rapidement.</p>
            </div>
        </section>

        <!-- Article 9 -->
        <section class="legal-section">
            <h2><span class="icon">🔧</span> Article 9 - Garanties</h2>
            <p>Tous nos produits bénéficient des garanties légales :</p>
            <ul>
                <li><strong>Garantie de conformité</strong> (articles L.217-4 et suivants du Code de la consommation)</li>
                <li><strong>Garantie des vices cachés</strong> (articles 1641 et suivants du Code civil)</li>
            </ul>
            
            <p>En cas de produit défectueux ou non conforme, veuillez nous contacter dans les plus brefs délais avec les éléments suivants : numéro de commande, photos du produit, description du problème.</p>
        </section>

        <!-- Article 10 -->
        <section class="legal-section">
            <h2><span class="icon">⚖️</span> Article 10 - Réclamations et médiation</h2>
            <p>Pour toute réclamation, vous pouvez nous contacter :</p>
            <div class="info-box">
                <p><strong>Par email :</strong> sav@technopomme.fr</p>
                <p><strong>Par courrier :</strong> TechnoPomme - Service Client, 123 Route des Vergers, 14000 Caen</p>
            </div>
            
            <p>En cas de litige non résolu, vous pouvez recourir gratuitement au médiateur de la consommation :</p>
            <div class="info-box">
                <p><strong>Médiateur du e-commerce de la FEVAD</strong></p>
                <p>60 Rue La Boétie, 75008 Paris</p>
                <p>www.mediateurfevad.fr</p>
            </div>
        </section>

        <!-- Article 11 -->
        <section class="legal-section">
            <h2><span class="icon">🇫🇷</span> Article 11 - Droit applicable</h2>
            <p>Les présentes CGV sont soumises au droit français. Tout litige relatif à leur interprétation ou leur exécution relève de la compétence exclusive des tribunaux français.</p>
        </section>

        <p class="update-date">
            📅 Dernière mise à jour : <?= date('d/m/Y') ?>
        </p>
    </div>

    <?= view('footer') ?>
</body>
</html>
