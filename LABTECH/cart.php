<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - TechStore</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
    <div class="nav-container">
        <div class="nav-top">
            <a href="index.php" class="logo">⚡ LABTECH</a>
            <div class="nav-right">
                <a href="cart.php" class="cart-link">
                    🛒 Panier
                    <span id="cart-count" class="cart-count">0</span>
                </a>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                    <a href="admin.php">Admin</a>
                    <a href="logout.php">Déconnexion</a>
                <?php elseif(isset($_SESSION['user'])): ?>
                    <a href="logout.php">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Nouvelle barre de navigation avec catégories -->
        <div class="nav-categories">
            <ul class="categories-list">
                <li><a href="shop.php?category=offres">🔥 Offres quotidiennes</a></li>
                <li><a href="shop.php?category=audio">🎧 Audio</a></li>
                <li><a href="shop.php?category=power">⚡ Power</a></li>
                <li><a href="shop.php?category=watch">⌚ Montre et bureau</a></li>
                <li><a href="shop.php?category=personal">💆 Soins personnels</a></li>
                <li><a href="shop.php?category=appliances">🏠 Appareils électroménagers</a></li>
                <li><a href="shop.php?category=new">✨ Nouveautés & Tendances</a></li>
                <li><a href="help.php">❓ Centre d'aide</a></li>
            </ul>
        </div>
    </div>
</nav>

    <main>
        <div class="cart-container">
            <h1>Mon Panier</h1>
            <div id="cart-items"></div>
            <div id="cart-total" class="cart-total"></div>
        </div>
    </main>

    <script src="script.js"></script>
    <script>
        // Fonction pour charger et afficher le panier
        async function loadCart() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartContainer = document.getElementById('cart-items');
            const totalContainer = document.getElementById('cart-total');
            
            if(cart.length === 0) {
                cartContainer.innerHTML = '<p>Votre panier est vide</p>';
                totalContainer.innerHTML = '';
                return;
            }
            
            let html = '';
            let total = 0;
            
            for(const item of cart) {
                const response = await fetch(`get_product.php?id=${item.id}`);
                const product = await response.json();
                const subtotal = product.price * item.quantity;
                total += subtotal;
                
                html += `
                    <div class="cart-item" data-id="${product.id}">
                        <img src="uploads/${product.image}" alt="${product.name}">
                        <div>
                            <h3>${product.name}</h3>
                            <p>${product.price} €</p>
                        </div>
                        <div>
                            <label>Quantité: </label>
                            <input type="number" value="${item.quantity}" min="1" max="${product.stock}" 
                                   class="cart-quantity" data-id="${product.id}">
                        </div>
                        <div>
                            <strong>${subtotal.toFixed(2)} €</strong>
                        </div>
                        <button class="remove-item" data-id="${product.id}">Supprimer</button>
                    </div>
                `;
            }
            
            html += `
                <div class="cart-summary">
                    <h3>Total: ${total.toFixed(2)} €</h3>
                    <button id="checkout-btn" class="submit-btn">Procéder au paiement</button>
                </div>
            `;
            
            cartContainer.innerHTML = html;
            totalContainer.innerHTML = '';
            
            // Ajouter les événements
            document.querySelectorAll('.cart-quantity').forEach(input => {
                input.addEventListener('change', updateQuantity);
            });
            
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', removeItem);
            });
            
            document.getElementById('checkout-btn')?.addEventListener('click', checkout);
        }
        
        async function updateQuantity(e) {
            const productId = e.target.dataset.id;
            const newQuantity = parseInt(e.target.value);
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const item = cart.find(i => i.id == productId);
            
            if(item) {
                item.quantity = newQuantity;
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCart();
                updateCartCount();
            }
        }
        
        function removeItem(e) {
            const productId = e.target.dataset.id;
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart = cart.filter(i => i.id != productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCart();
            updateCartCount();
            showNotification('Produit retiré du panier');
        }
        
        function checkout() {
            if(!isLoggedIn()) {
                window.location.href = 'login.php?redirect=cart.php';
                return;
            }
            alert('Fonctionnalité de paiement à implémenter');
        }
        
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if(cartCountElement) cartCountElement.textContent = count;
        }
        
        function isLoggedIn() {
            // Vérifier si l'utilisateur est connecté via session PHP
            return <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
        }
        
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        loadCart();
    </script>

    <?php require_once 'footer.php'; ?>
</body>
</html>