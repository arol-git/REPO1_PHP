<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - DATALAB-TECH</title>
    <link rel="stylesheet" href="style-main.css">
    <link rel="stylesheet" href="style-navbar.css">
    <link rel="stylesheet" href="style-footer.css">
</head>
<body>
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="cart-container">
            <h1>Mon Panier</h1>
            <div id="cart-items"></div>
            <div id="cart-total"></div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script>
        async function loadCart() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartContainer = document.getElementById('cart-items');
            const totalContainer = document.getElementById('cart-total');
            
            if(cart.length === 0) {
                cartContainer.innerHTML = '<div class="cart-empty"><h3>🛒 Votre panier est vide</h3><p>Découvrez nos produits et faites votre sélection !</p><a href="shop.php" class="admin-btn" style="margin-top:1rem;display:inline-block;">Découvrir la boutique</a></div>';
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
                            <strong>${product.name}</strong>
                        </div>
                        <div>
                            <label>Quantité: </label>
                            <input type="number" value="${item.quantity}" min="1" max="${product.stock}" class="cart-quantity" data-id="${product.id}">
                        </div>
                        <div>
                            <strong>${(subtotal * 655.96).toLocaleString('fr-FR')} FCFA</strong>
                        </div>
                        <button class="remove-item" data-id="${product.id}">🗑️</button>
                    </div>
                `;
            }
            
            html += `
                <div class="cart-summary">
                    <h3>Total: ${(total * 655.96).toLocaleString('fr-FR')} FCFA</h3>
                    <button id="checkout-btn" class="submit-btn">✅ Procéder au paiement</button>
                </div>
            `;
            
            cartContainer.innerHTML = html;
            
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
            showNotification('Produit retiré du panier', 'success');
        }
        
        function checkout() {
            if(!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
                window.location.href = 'login.php?redirect=cart.php';
                return;
            }
            showNotification('Fonctionnalité de paiement bientôt disponible', 'info');
        }
        
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cart-count');
            if(cartCountElement) cartCountElement.textContent = count;
        }
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        loadCart();
    </script>
</body>
</html>