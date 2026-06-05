<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css?v=<?php echo filemtime(__DIR__ . '/css/style.css'); ?>">

<header class="header">
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="index.php">
                    <span class="logo-text">DATALAB-TECH</span>
                </a>
            </div>
            
            <div class="nav-menu" id="navMenu">
                <ul class="nav-links">
                    <li><a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Accueil</a></li>
                    <li><a href="shop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">Boutique</a></li>
                    <li><a href="categories.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">Catégories</a></li>
                    <li><a href="help.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'help.php' ? 'active' : ''; ?>">Contact</a></li>
                </ul>
            </div>
            
            <div class="nav-actions">
                <button class="theme-toggle" id="theme-toggle" title="Changer de thème">
                    <span class="theme-icon">🌙</span>
                </button>
                <button class="search-btn" id="searchBtn">
                    <a href="search.php" class="search-btn" id="searchBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </a>
                </button>
                <div class="cart-icon" id="cartIcon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </div>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
    <div class="nav-overlay" id="navOverlay"></div>
    
    <!-- Search Bar -->
    <div class="search-overlay" id="searchOverlay">
        <div class="search-container">
            <form action="search.php" method="GET" style="width: 100%;">
                <input type="text" name="q" placeholder="Rechercher un produit..." id="searchInput" autocomplete="off">
                <button type="submit" class="search-submit" style="position: absolute; right: 50px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-primary); cursor: pointer;">🔍</button>
                <button type="button" class="close-search" id="closeSearch">✕</button>
            </form>
        </div>
    </div>
</header>

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