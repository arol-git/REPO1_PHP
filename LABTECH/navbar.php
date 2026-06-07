<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/css/style.css?v=<?php echo filemtime(__DIR__ . '/css/style.css'); ?>">

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
                    <span class="theme-icon"><i class="fa-solid fa-moon fa-rotate-by fa-xl" style="color: rgb(255, 212, 59); --fa-rotate-angle: 220deg;"></i></i></span>
                </button>
                <div class="search-btn" id="searchBtn"><i class="fa-solid fa-magnifying-glass fa-xl"></i></div>  <!-- la balise span permet de faire  -->
                <div class="cart-icon" id="cartIcon"><i class="fa-solid fa-cart-shopping fa-xl"></i><span class="cart-count" id="cartCount"></span></div>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </nav>
    <div class="nav-overlay" id="navOverlay"></div>
    
</header>

<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h3>Mon Panier</h3>
        <button class="close-cart" id="closeCart">✕</button>
    </div>
    <div class="cart-items" id="cartItems">
        <div class="cart-empty">
            <i class="fa-solid fa-cart-shopping fa-2xl"></i><span ></span>
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
<div class="search-overlay" id="searchOverlay" aria-hidden="true">
    <div class="search-overlay-backdrop" id="searchOverlayBackdrop"></div>
    <div class="search-overlay-content" id="searchOverlayContent">
        <button class="close-search" id="closeSearch" aria-label="Fermer la recherche">✕</button>
        <div id="searchOverlayInner"></div>
    </div>
</div>

<div class="cart-overlay" id="cartOverlay"></div>

<script src="/js/script.js"></script>