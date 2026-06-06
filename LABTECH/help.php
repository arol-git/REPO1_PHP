<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'aide - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="help-container">
            <h1>❓ Centre d'aide</h1>
            
            <div class="help-grid">
                <div class="help-card" id="shipping">
                    <h3>📦 Livraison</h3>
                    <p>Livraison gratuite à partir de 50 000 FCFA d'achat. Délai de livraison : 2-3 jours ouvrés.</p>
                </div>
                
                <div class="help-card" id="returns">
                    <h3>🔄 Retours</h3>
                    <p>Vous disposez de 14 jours pour retourner votre produit. Remboursement sous 48h.</p>
                </div>
                
                <div class="help-card" id="payment">
                    <h3>💳 Paiement</h3>
                    <p>Paiement sécurisé par carte bancaire, Orange Money, MTN Mobile Money.</p>
                </div>
                
                <div class="help-card" id="warranty">
                    <h3>🔧 Garantie</h3>
                    <p>Garantie 2 ans sur tous nos produits. Service après-vente réactif.</p>
                </div>
                
                <div class="help-card">
                    <h3>📞 Contact</h3>
                    <p>Service client : <a href="tel:+237697421261">+237 697 421 261</a><br>Email : yemeliarol04@gmail.com</p>
                </div>

                <div class="help-card">
                    <h3>⚡ À propos</h3>
                    <p>DATALAB-TECH : votre partenaire technologique de confiance au Cameroun.</p>
                </div>
            </div>
            
            <div class="faq-section">
                <h2>Questions fréquentes</h2>
                
                <div class="faq-item">
                    <h3>Comment passer commande ?</h3>
                    <p>Ajoutez les produits souhaités au panier, puis validez votre commande en suivant les étapes de paiement.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Comment suivre ma commande ?</h3>
                    <p>Un email de confirmation avec numéro de suivi vous sera envoyé dès l'expédition de votre commande.</p>
                </div>
                
                <div class="faq-item">
                    <h3>Que faire en cas de produit défectueux ?</h3>
                    <p>Contactez notre service client qui vous guidera pour un échange ou remboursement.</p>
                </div>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>
</body>
</html>