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
    <script>
        // Scroll vers la section correspondante si un hash est présent dans l'URL
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash) {
                const targetId = window.location.hash.substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });

        // Fonction pour faire défiler en douceur vers une section
        function scrollToSection(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
        // Ajouter des écouteurs de clic aux liens de la section FAQ
        document.querySelectorAll('.faq-item h3').forEach(header => {
            header.addEventListener('click', function() {
                const content = this.nextElementSibling;
                if (content.style.display === 'block') {
                    content.style.display = 'none';
                } else {
                    content.style.display = 'block';
                }
            });
        });
    </script>
    <style>
        .faq-item p {
            display: none;
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
        }
        .faq-item h3 {
            cursor: pointer;
            transition: color 0.3s;
        }
        .faq-item h3:hover {
            color: var(--accent-primary);
        }
        .success-message, .error-message {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: inline-block;
            font-size: 0.9rem;
        }
        .success-message {
            background: rgba(0, 255, 136, 0.15);
            border: 1px solid rgba(0, 255, 136, 0.3);
            color: #00ff88;
        }
        .error-message {
            background: rgba(255, 68, 68, 0.15);
            border: 1px solid rgba(255, 68, 68, 0.3);
            color: #ff6666;
        }
    </style>
</body>
</html>
