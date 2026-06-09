<?php require_once 'config.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main>
        <div class="cart-container">
            <h1>Mon Panier</h1>
            <div id="cartPageItems"></div>
            <div id="cartPageTotal"></div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script>
        async function loadCart() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartContainer = document.getElementById('cartPageItems');
            const totalContainer = document.getElementById('cartPageTotal');
            
            if(cart.length === 0) {
                cartContainer.innerHTML = '<div class="cart-empty"><span>🛒</span><p>Votre panier est vide</p><a href="shop.php" class="btn-primary" style="margin-top:1rem;display:inline-block;">Découvrir la boutique</a></div>';
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
                        <img src="uploads/${product.image}" alt="${product.name}" class="cart-item-img">
                        <div class="cart-item-info">
                            <h4 class="cart-item-title">${product.name}</h4>
                        </div>
                        <div style="display:flex; gap:0.5rem; align-items:center;">
                            <label>Qté:</label>
                            <input type="number" value="${item.quantity}" min="1" max="${product.stock}" class="cart-quantity" data-id="${product.id}" style="width:60px; padding:0.3rem;">
                        </div>
                        <div>
                            <strong>${(subtotal * 655.96).toLocaleString('fr-FR')} FCFA</strong>
                        </div>
                        <button class="remove-item" data-id="${product.id}">🗑️</button>
                    </div>
                `;
            }
            
            html += `
                <div class="cart-summary" style="margin-top:1.5rem; padding:1rem; background:var(--bg-card); border-radius:12px;">
                    <h3>Total: ${(total * 655.96).toLocaleString('fr-FR')} FCFA</h3>
                    <button class="btn-primary checkout-btn" style="margin-top:1rem;">✅ Commander</button>
                </div>
            `;
            
            cartContainer.innerHTML = html;
            
            document.querySelectorAll('.cart-quantity').forEach(input => {
                input.addEventListener('change', updateQuantity);
            });
            
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', removeItem);
            });
            
            const checkoutButton = document.querySelector('.checkout-btn');
            if (checkoutButton) {
                checkoutButton.addEventListener('click', () => {
                    window.location.href = 'checkout.php';
                });
            }
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
        
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountElement = document.getElementById('cartCount');
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
      <script>
        // Initialiser les éléments du panier
        cartSidebar = document.getElementById('cartSidebar');
        cartOverlay = document.getElementById('cartOverlay');
        closeCart = document.getElementById('closeCart');
        cartContainer = document.getElementById('cartPageItems');
        cartTotal = document.getElementById('cartPageTotal');
        
        // Charger le panier et mettre à jour l'interface
        loadCart();
    </script>
</body>
</html>
