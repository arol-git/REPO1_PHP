<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexTech | Premium Electronics</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header / Navbar -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <a href="#">
                        <span class="logo-icon">⚡</span>
                        <span class="logo-text">NexTech</span>
                    </a>
                </div>
                
                <div class="nav-menu" id="navMenu">
                    <ul class="nav-links">
                        <li><a href="#" class="nav-link active">Accueil</a></li>
                        <li><a href="#" class="nav-link">Boutique</a></li>
                        <li><a href="#" class="nav-link">Catégories</a></li>
                        <li><a href="#" class="nav-link">Contact</a></li>
                    </ul>
                </div>
                
                <div class="nav-actions">
                    <button class="search-btn" id="searchBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                    <div class="cart-icon" id="cartIcon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="cart-count" id="cartCount">0</span>
                    </div>
                    <button class="user-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </button>
                    <div class="hamburger" id="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Search Bar -->
        <div class="search-overlay" id="searchOverlay">
            <div class="search-container">
                <input type="text" placeholder="Rechercher des produits..." id="searchInput">
                <button class="close-search" id="closeSearch">✕</button>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-badge">🔥 Nouvelle Collection 2024</div>
                    <h1 class="hero-title">
                        Next Generation<br>
                        <span class="gradient-text">Tech Experience</span>
                    </h1>
                    <p class="hero-subtitle">
                        Découvrez les derniers accessoires électroniques qui révolutionnent 
                        votre quotidien. Qualité premium, design innovant.
                    </p>
                    <div class="hero-buttons">
                        <button class="btn-primary">Shop Now →</button>
                        <button class="btn-secondary">En savoir plus</button>
                    </div>
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-number">50K+</span>
                            <span class="stat-label">Clients satisfaits</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Support technique</span>
                        </div>
                        <div class="stat">
                            <span class="stat-number">2 ans</span>
                            <span class="stat-label">Garantie</span>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-image-wrapper">
                        <img src="https://images.unsplash.com/photo-1592899677977-9e10cb588e9e?w=600" alt="Premium Headphones" loading="lazy">
                        <div class="floating-card card-1">
                            <span>🔥 -25%</span>
                        </div>
                        <div class="floating-card card-2">
                            <span>⚡ Charge rapide</span>
                        </div>
                        <div class="floating-card card-3">
                            <span>🎵 Audio HD</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Categories -->
        <section class="categories fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Catégories</span>
                    <h2 class="section-title">Explorez nos<br><span class="gradient-text">produits phares</span></h2>
                    <p class="section-subtitle">Des accessoires de qualité pour tous vos besoins technologiques</p>
                </div>
                <div class="categories-grid">
                    <div class="category-card" data-category="audio">
                        <div class="category-icon">🎧</div>
                        <h3>Audio</h3>
                        <p>Écouteurs & Casques</p>
                        <span class="category-count">24 produits</span>
                    </div>
                    <div class="category-card" data-category="power">
                        <div class="category-icon">🔋</div>
                        <h3>Power</h3>
                        <p>Chargeurs & Batteries</p>
                        <span class="category-count">18 produits</span>
                    </div>
                    <div class="category-card" data-category="smart">
                        <div class="category-icon">⌚</div>
                        <h3>Smart Devices</h3>
                        <p>Montres & Accessoires</p>
                        <span class="category-count">12 produits</span>
                    </div>
                    <div class="category-card" data-category="accessories">
                        <div class="category-icon">💻</div>
                        <h3>Accessoires</h3>
                        <p>Câbles & Stations</p>
                        <span class="category-count">32 produits</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Meilleures ventes</span>
                    <h2 class="section-title">Produits<br><span class="gradient-text">populaires</span></h2>
                    <p class="section-subtitle">Les accessoires les plus demandés par nos clients</p>
                </div>
                
                <!-- Filters -->
                <div class="product-filters">
                    <button class="filter-btn active" data-filter="all">Tous</button>
                    <button class="filter-btn" data-filter="audio">Audio</button>
                    <button class="filter-btn" data-filter="power">Power</button>
                    <button class="filter-btn" data-filter="smart">Smart</button>
                    <button class="filter-btn" data-filter="accessories">Accessoires</button>
                </div>
                
                <div class="products-grid" id="productsGrid">
                    <!-- Product Card 1 -->
                    <div class="product-card" data-category="audio">
                        <div class="product-badge">New</div>
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300" alt="Wireless Headphones" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Casque Audio Pro X2</h3>
                            <div class="product-rating">
                                <span>★★★★★</span>
                                <span class="rating-count">(128)</span>
                            </div>
                            <p class="product-price">59 900 FCFA</p>
                            <button class="add-to-cart" data-id="1" data-name="Casque Audio Pro X2" data-price="59900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Card 2 -->
                    <div class="product-card" data-category="power">
                        <div class="product-badge hot">Hot</div>
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1605464315542-bda3e2f4e605?w=300" alt="Power Bank" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Power Bank 20000mAh</h3>
                            <div class="product-rating">
                                <span>★★★★☆</span>
                                <span class="rating-count">(94)</span>
                            </div>
                            <p class="product-price">39 900 FCFA</p>
                            <button class="add-to-cart" data-id="2" data-name="Power Bank 20000mAh" data-price="39900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Card 3 -->
                    <div class="product-card" data-category="audio">
                        <div class="product-badge sale">-20%</div>
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=300" alt="Wireless Earbuds" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Écouteurs TWS Pro</h3>
                            <div class="product-rating">
                                <span>★★★★★</span>
                                <span class="rating-count">(256)</span>
                            </div>
                            <p class="product-price"><span class="old-price">49 900 FCFA</span> 39 900 FCFA</p>
                            <button class="add-to-cart" data-id="3" data-name="Écouteurs TWS Pro" data-price="39900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Card 4 -->
                    <div class="product-card" data-category="power">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=300" alt="Fast Charger" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Chargeur Rapide 65W GaN</h3>
                            <div class="product-rating">
                                <span>★★★★☆</span>
                                <span class="rating-count">(67)</span>
                            </div>
                            <p class="product-price">29 900 FCFA</p>
                            <button class="add-to-cart" data-id="4" data-name="Chargeur Rapide 65W GaN" data-price="29900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Card 5 -->
                    <div class="product-card" data-category="smart">
                        <div class="product-badge new">New</div>
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=300" alt="Smart Watch" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Smart Watch Ultra</h3>
                            <div class="product-rating">
                                <span>★★★★★</span>
                                <span class="rating-count">(189)</span>
                            </div>
                            <p class="product-price">89 900 FCFA</p>
                            <button class="add-to-cart" data-id="5" data-name="Smart Watch Ultra" data-price="89900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                    
                    <!-- Product Card 6 -->
                    <div class="product-card" data-category="accessories">
                        <div class="product-image">
                            <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?w=300" alt="USB Cable" loading="lazy">
                            <div class="product-overlay">
                                <button class="quick-view">👁️ Voir rapide</button>
                            </div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">Câble USB-C Tressé 2m</h3>
                            <div class="product-rating">
                                <span>★★★★☆</span>
                                <span class="rating-count">(342)</span>
                            </div>
                            <p class="product-price">6 900 FCFA</p>
                            <button class="add-to-cart" data-id="6" data-name="Câble USB-C Tressé 2m" data-price="6900">
                                Ajouter au panier
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="products-more">
                    <button class="btn-outline">Voir tous les produits →</button>
                </div>
            </div>
        </section>

        <!-- Special Offer Banner -->
        <section class="offer-banner fade-up">
            <div class="offer-container">
                <div class="offer-content">
                    <span class="offer-badge">🎁 Offre Limitée</span>
                    <h2>Jusqu'à <span class="gradient-text">-30%</span><br>sur les accessoires audio</h2>
                    <p>Profitez de nos remises exceptionnelles sur une sélection de produits premium. Offre valable jusqu'à épuisement des stocks.</p>
                    <button class="btn-primary">Profiter de l'offre →</button>
                </div>
                <div class="offer-image">
                    <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?w=400" alt="Special Offer" loading="lazy">
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="features fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Pourquoi nous ?</span>
                    <h2 class="section-title">Pourquoi choisir<br><span class="gradient-text">NexTech ?</span></h2>
                    <p class="section-subtitle">Une expérience d'achat unique et des services premium</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🚚</div>
                        <h3>Livraison Express</h3>
                        <p>Livraison gratuite sous 24-48h sur toute commande</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔒</div>
                        <h3>Paiement Sécurisé</h3>
                        <p>Transactions 100% sécurisées avec cryptage SSL</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>Garantie 2 Ans</h3>
                        <p>Service après-vente réactif et garantie constructeur</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🎧</div>
                        <h3>Support 24/7</h3>
                        <p>Assistance technique disponible à tout moment</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials fade-up">
            <div class="container">
                <div class="section-header">
                    <span class="section-badge">Témoignages</span>
                    <h2 class="section-title">Ce que nos<br><span class="gradient-text">clients disent</span></h2>
                </div>
                <div class="testimonials-slider">
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★★</div>
                        <p class="testimonial-text">"Excellente qualité de produits ! Le service client est réactif et professionnel. Je recommande vivement."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Client">
                            <div>
                                <h4>Thomas M.</h4>
                                <span>Client fidèle</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★★</div>
                        <p class="testimonial-text">"Livraison rapide et produit conforme. Le casque audio est incroyable, le son est parfait !"</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/women/2.jpg" alt="Client">
                            <div>
                                <h4>Sarah K.</h4>
                                <span>Audiophile</span>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-rating">★★★★☆</div>
                        <p class="testimonial-text">"Très bonne expérience globale. La batterie externe tient parfaitement ses promesses."</p>
                        <div class="testimonial-author">
                            <img src="https://randomuser.me/api/portraits/men/3.jpg" alt="Client">
                            <div>
                                <h4>David L.</h4>
                                <span>Tech enthusiast</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <span class="logo-icon">⚡</span>
                        <span class="logo-text">NexTech</span>
                    </div>
                    <p class="footer-description">
                        La référence en matière d'accessoires électroniques premium. 
                        Innovation, qualité et design au service de nos clients.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link">📘</a>
                        <a href="#" class="social-link">📷</a>
                        <a href="#" class="social-link">🐦</a>
                        <a href="#" class="social-link">💼</a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Liens rapides</h4>
                    <ul>
                        <li><a href="#">Accueil</a></li>
                        <li><a href="#">Boutique</a></li>
                        <li><a href="#">Catégories</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Service client</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Livraison</a></li>
                        <li><a href="#">Retours</a></li>
                        <li><a href="#">Garantie</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li>📞 <a href="tel:+237697421261">+237 697 421 261</a></li>
                        <li>💬 <a href="#">WhatsApp</a></li>
                        <li>✉️ <a href="mailto:contact@nextech.com">contact@nextech.com</a></li>
                        <li>⏰ Lun-Sam: 9h-17h30</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 NexTech. Tous droits réservés. Designé avec ❤️ pour une expérience premium.</p>
                <div class="payment-methods">
                    <span>💳 Visa</span>
                    <span>💳 Mastercard</span>
                    <span>📱 Orange Money</span>
                    <span>📱 MTN Money</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>Mon Panier</h3>
            <button class="close-cart" id="closeCart">✕</button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="cart-empty">
                <span>🛒</span>
                <p>Votre panier est vide</p>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total</span>
                <span id="cartTotal">0 FCFA</span>
            </div>
            <button class="checkout-btn" id="checkoutBtn">Commander</button>
        </div>
    </div>
    
    <div class="cart-overlay" id="cartOverlay"></div>

    <script src="js/script.js"></script>
</body>
</html>