<!-- Footer Section -->
<footer class="footer">
    <div class="footer-container">
        <!-- Newsletter Section -->
        <div class="newsletter-section">
            <div class="newsletter-content">
                <h3>📧 Restez informé</h3>
                <p>Abonnez-vous pour recevoir nos dernières offres et nouveautés</p>
                <form id="newsletter-form" class="newsletter-form">
                    <div class="newsletter-input-group">
                        <input type="email" id="newsletter-email" placeholder="Votre email" required>
                        <button type="submit" class="newsletter-btn">S'abonner</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Main Content -->
        <div class="footer-main">
            <div class="footer-column">
                <div class="footer-logo">
                    <span class="logo-icon">⚡</span>
                    <span class="logo-text">DATALAB-TECH</span>
                </div>
                <p class="footer-description">
                    Votre boutique d'accessoires électroniques de confiance.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link">📘</a>
                    <a href="#" class="social-link">📷</a>
                    <a href="#" class="social-link">🐦</a>
                    <a href="#" class="social-link">🔗</a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Contact</h4>
                <ul class="footer-links">
                    <li><a href="tel:+237697421261">📞 +237 697 421 261</a></li>
                    <li><a href="mailto:yemeliarol04@gmail.com">✉️ Email</a></li>
                    <li><a href="https://wa.me/237621074991" target="_blank">💬 WhatsApp</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Service</h4>
                <ul class="footer-links">
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Livraison</a></li>
                    <li><a href="#">Retours</a></li>
                    <li><a href="#">Aide</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Paiement</h4>
                <div class="payment-icons">
                    <span>💳 Visa</span>
                    <span>💳 Mastercard</span>
                    <span>📱 Orange Money</span>
                    <span>📱 MTN Money</span>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            <p>© 2024 DATALAB-TECH. Tous droits réservés.</p>
        </div>
    </div>
</footer>

<script>
// Newsletter subscription
document.addEventListener('DOMContentLoaded', () => {
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = document.getElementById('newsletter-email').value;

            // Simple validation
            if (!email) return;

            // Save to localStorage
            let subscribers = JSON.parse(localStorage.getItem('newsletter_subscribers') || '[]');
            if (!subscribers.includes(email)) {
                subscribers.push(email);
                localStorage.setItem('newsletter_subscribers', JSON.stringify(subscribers));
                showNotification('✅ Merci pour votre abonnement !', 'success');
                newsletterForm.reset();
            } else {
                showNotification('Cet email est déjà abonné', 'info');
            }
        });
    }
});

function showNotification(message, type) {
    const notification = document.createElement('div';
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>