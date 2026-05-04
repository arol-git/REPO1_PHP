<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="style-navbar.css">

<nav class="navbar">
    <div class="nav-container">
        <div class="nav-top">
            <a href="index.php" class="logo">
                <span class="logo-icon">⚡</span>
                <span class="logo-text">DATALAB-TECH</span>
            </a>
            <div class="nav-right">
                <button id="theme-toggle" class="theme-toggle" title="Changer de thème">
                    <span class="theme-icon">🌙</span>
                </button>
                <a href="cart.php" class="cart-link">
                    🛒 <span>Panier</span>
                    <span id="cart-count" class="cart-count">0</span>
                </a>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                    <a href="admin-dashboard.php">👑 Admin</a>
                    <a href="logout.php">🚪 Déconnexion</a>
                <?php elseif(isset($_SESSION['user'])): ?>
                    <a href="logout.php">🚪 Déconnexion</a>
                <?php else: ?>
                    <a href="login.php">🔑 Connexion</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="nav-categories">
            <ul class="categories-list">
                <li><a href="shop.php?category=offres" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'offres') ? 'active' : ''; ?>"><span class="category-icon">🔥</span><span>Offres quotidiennes</span></a></li>
                <li><a href="shop.php?category=audio" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'audio') ? 'active' : ''; ?>"><span class="category-icon">🎧</span><span>Audio</span></a></li>
                <li><a href="shop.php?category=power" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'power') ? 'active' : ''; ?>"><span class="category-icon">⚡</span><span>Power</span></a></li>
                <li><a href="shop.php?category=watch" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'watch') ? 'active' : ''; ?>"><span class="category-icon">⌚</span><span>Montre et bureau</span></a></li>
                <li><a href="shop.php?category=personal" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'personal') ? 'active' : ''; ?>"><span class="category-icon">💆</span><span>Soins personnels</span></a></li>
                <li><a href="shop.php?category=appliances" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'appliances') ? 'active' : ''; ?>"><span class="category-icon">🏠</span><span>Appareils électroménagers</span></a></li>
                <li><a href="shop.php?category=new" class="category-link <?php echo (isset($_GET['category']) && $_GET['category'] == 'new') ? 'active' : ''; ?>"><span class="category-icon">✨</span><span>Nouveautés & Tendances</span></a></li>
                <li><a href="help.php" class="category-link <?php echo basename($_SERVER['PHP_SELF']) == 'help.php' ? 'active' : ''; ?>"><span class="category-icon">❓</span><span>Centre d'aide</span></a></li>
            </ul>
        </div>
    </div>
</nav>

<script>
// Gestion du thème
class ThemeManager {
    constructor() {
        this.themeToggle = document.getElementById('theme-toggle');
        this.currentTheme = localStorage.getItem('theme') || 'dark';
        this.init();
    }

    init() {
        this.applyTheme(this.currentTheme);
        if (this.themeToggle) {
            this.themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    toggleTheme() {
        this.currentTheme = this.currentTheme === 'dark' ? 'light' : 'dark';
        this.applyTheme(this.currentTheme);
        localStorage.setItem('theme', this.currentTheme);
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        const icon = this.themeToggle?.querySelector('.theme-icon');
        if (icon) {
            icon.textContent = theme === 'dark' ? '🌙' : '☀️';
        }
    }
}

// Mise à jour compteur panier
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCount = document.getElementById('cart-count');
    if (cartCount) cartCount.textContent = count;
}

document.addEventListener('DOMContentLoaded', () => {
    new ThemeManager();
    updateCartCount();
});
</script>