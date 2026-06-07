<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $payload = json_decode(file_get_contents('php://input'), true);
    $cart = isset($payload['cart']) && is_array($payload['cart']) ? $payload['cart'] : [];
    $paymentMethod = isset($payload['payment_method']) && trim($payload['payment_method']) !== ''
        ? trim($payload['payment_method'])
        : 'Paiement à la livraison';

    if (empty($cart)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Le panier est vide']);
        exit;
    }

    try {
        $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS payment_method VARCHAR(50) DEFAULT 'Paiement à la livraison'");
        $pdo->beginTransaction();

        $total = 0;
        $items = [];

        $stmtProduct = $pdo->prepare('SELECT id, price, stock FROM products WHERE id = ? FOR UPDATE');

        foreach ($cart as $item) {
            $productId = isset($item['id']) ? (int) $item['id'] : 0;
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;

            if ($productId <= 0 || $quantity <= 0) {
                throw new Exception('Informations de produit invalides');
            }

            $stmtProduct->execute([$productId]);
            $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                throw new Exception('Produit introuvable dans le panier');
            }

            if ($product['stock'] < $quantity) {
                throw new Exception('Stock insuffisant pour le produit #' . $productId);
            }

            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product['price'],
            ];
            $total += $product['price'] * $quantity;
        }

        $shippingInfo = isset($payload['shipping_info']) && is_array($payload['shipping_info']) ? json_encode($payload['shipping_info'], JSON_UNESCAPED_UNICODE) : null;
        $pdo->exec("ALTER TABLE orders ADD COLUMN IF NOT EXISTS shipping_info TEXT NULL");
        $userId = isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
        $stmtOrder = $pdo->prepare('INSERT INTO orders (user_id, total, status, payment_method, shipping_info) VALUES (?, ?, ?, ?, ?)');
        $stmtOrder->execute([$userId, $total, 'pending', $paymentMethod, $shippingInfo]);
        $orderId = $pdo->lastInsertId();

        $stmtItem = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
        $stmtUpdateStock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ?');

        foreach ($items as $item) {
            $stmtItem->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            $stmtUpdateStock->execute([$item['quantity'], $item['product_id']]);
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Commande enregistrée', 'order_id' => $orderId]);
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Si GET, afficher la page de checkout
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - DATALAB-TECH</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .checkout-page {
            padding: 3rem 1rem;
            min-height: calc(100vh - 220px);
            background: var(--bg-body);
        }
        .checkout-container {
            max-width: 1180px;
            margin: 0 auto;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
        }
        .checkout-breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            color: var(--text-muted);
            margin-bottom: 1.8rem;
            font-size: 0.95rem;
        }
        .checkout-layout {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 2rem;
        }
        .checkout-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        .checkout-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 1.8rem;
        }
        .checkout-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .checkout-card-header h1,
        .checkout-card-header h2 {
            margin: 0;
            font-size: 1.6rem;
        }
        .checkout-card-header p {
            margin: 0.35rem 0 0;
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .shipping-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .shipping-form .form-row .form-group {
            margin-bottom: 1rem;
        }
        .shipping-form .form-group {
            margin-bottom: 1rem;
        }
        .checkbox-group {
            align-self: end;
            margin-top: 0.5rem;
        }
        .phone-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .phone-prefix {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            padding: 0.75rem 0.8rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-weight: 500;
        }
        .order-items-list {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .order-item-card {
            display: grid;
            grid-template-columns: 72px 1fr;
            gap: 1rem;
            padding: 1rem;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 18px;
        }
        .order-item-card img {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 16px;
            background: #f4f6fb;
        }
        .order-item-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0.35rem;
        }
        .order-item-info h3 {
            margin: 0;
            font-size: 1rem;
        }
        .order-item-info p {
            margin: 0;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .order-total-line {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            font-weight: 600;
        }
        .order-total-line.total-final {
            border-bottom: none;
            margin-top: 0.5rem;
            font-size: 1.05rem;
        }
        .payment-methods-panel {
            display: grid;
            gap: 0.75rem;
            margin: 1.5rem 0;
        }
        .payment-methods-panel h3 {
            margin: 0;
            font-size: 1rem;
            color: var(--accent-primary);
        }
        .payment-option {
            display: flex;
            align-items: center;
            padding: 0.95rem 1rem;
            gap: 0.8rem;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: border-color .2s ease, background .2s ease;
        }
        .payment-option:hover {
            border-color: var(--accent-primary);
            background: rgba(0, 212, 255, 0.08);
        }
        .payment-option input {
            accent-color: var(--accent-primary);
        }
        .btn-primary,
        .btn-secondary {
            border: none;
            border-radius: 999px;
            font-weight: 600;
            cursor: pointer;
            padding: 0.95rem 1.35rem;
            transition: transform .2s ease, opacity .2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #7b2ff7);
            color: white;
        }
        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }
        .payment-note {
            margin-top: 1rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }
        .checkout-message {
            margin-top: 1rem;
        }
        @media (max-width: 980px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
            .shipping-form .form-row,
            .order-total-line {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body data-user-logged-in="<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>">
    <?php require_once 'navbar.php'; ?>

    <main class="checkout-page">
        <div class="checkout-container">
            <div class="checkout-breadcrumbs">
                <span>Panier</span>
                <span>›</span>
                <span>Paiement Sécurisé</span>
                <span>›</span>
                <span>Commande terminée</span>
            </div>

            <div class="checkout-layout">
                <section class="checkout-column checkout-col-left">
                    <div class="checkout-card">
                        <div class="checkout-card-header">
                            <div>
                                <h1>Adresse d'expédition</h1>
                                <p>Remplissez vos informations pour que le vendeur livre correctement.</p>
                            </div>
                            <button type="button" class="btn-secondary" id="editCartBtn">Modifier</button>
                        </div>

                        <form id="shippingForm" class="shipping-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Adresse E-mail*</label>
                                    <input type="email" id="email" name="email" placeholder="votre@email.com" required>
                                </div>
                                <div class="form-group checkbox-group">
                                    <label><input type="checkbox" id="defaultAddress"> Définir par défaut</label>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Prénom*</label>
                                    <input type="text" id="firstName" name="firstName" placeholder="Prénom" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Nom*</label>
                                    <input type="text" id="lastName" name="lastName" placeholder="Nom" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="quartier">Quartier*</label>
                                    <input type="text" id="quartier" name="quartier" placeholder="Quartier" required>
                                </div>
                                <div class="form-group">
                                    <label for="ville">Ville*</label>
                                    <select id="ville" name="ville" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Douala">Douala</option>
                                        <option value="Yaoundé">Yaoundé</option>
                                        <option value="Bafoussam">Bafoussam</option>
                                        <option value="Bertoua">Bertoua</option>
                                        <option value="Garoua">Garoua</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="streetAddress">Adresse de la rue*</label>
                                <input type="text" id="streetAddress" name="streetAddress" placeholder="N° de maison, rue" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Numéro de téléphone*</label>
                                    <div class="phone-group">
                                        <span class="phone-prefix">+237</span>
                                        <input type="tel" id="phone" name="phone" placeholder="6XXXXXXXX" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="zipcode">Zipcode</label>
                                    <input type="text" id="zipcode" name="zipcode" placeholder="Zipcode">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="whatsapp">Numéro de WhatsApp</label>
                                <input type="tel" id="whatsapp" name="whatsapp" placeholder="+237 6XXXXXXX">
                            </div>
                        </form>
                    </div>
                </section>

                <aside class="checkout-column checkout-col-right">
                    <div class="checkout-card summary-card">
                        <div class="checkout-card-header">
                            <div>
                                <h2>Ma commande</h2>
                            </div>
                            <button type="button" class="btn-secondary" id="modifyOrderBtn">Modifier</button>
                        </div>

                        <div id="orderSummary" class="order-items-list"></div>

                        <div class="order-total-line">
                            <span>Sous-total du panier</span>
                            <strong id="orderTotal">FCFA 0</strong>
                        </div>
                        <div class="order-total-line">
                            <span>Expédition</span>
                            <strong>FCFA 0</strong>
                        </div>
                        <div class="order-total-line total-final">
                            <span>Total</span>
                            <strong id="orderGrandTotal">FCFA 0</strong>
                        </div>

                        <div class="payment-methods-panel">
                            <h3>Mode de paiement</h3>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Carte bancaire" checked>
                                <span>Carte bancaire</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Mobile Money">
                                <span>Mobile Money</span>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="Paiement à la livraison">
                                <span>Paiement à la livraison</span>
                            </label>
                        </div>

                        <button class="btn-primary" id="confirmCheckout">PASSER COMMANDE</button>
                        <p class="payment-note">J'ai lu et j'accepte les Conditions d'utilisation et la Politique de confidentialité.</p>
                        <div class="checkout-message" id="checkoutMessage"></div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <?php require_once 'footer.php'; ?>

    <script>
        // Sauvegarder les données du formulaire quand l'utilisateur tape
        function saveFormData() {
            const formData = {
                email: document.getElementById('email').value,
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                quartier: document.getElementById('quartier').value,
                ville: document.getElementById('ville').value,
                streetAddress: document.getElementById('streetAddress').value,
                phone: document.getElementById('phone').value,
                zipcode: document.getElementById('zipcode').value,
                whatsapp: document.getElementById('whatsapp').value,
                defaultAddress: document.getElementById('defaultAddress').checked,
                payment_method: document.querySelector('input[name="payment_method"]:checked')?.value
            };
            localStorage.setItem('checkoutFormData', JSON.stringify(formData));
        }

        // Restaurer les données du formulaire
        function restoreFormData() {
            const saved = localStorage.getItem('checkoutFormData');
            if (saved) {
                const formData = JSON.parse(saved);
                document.getElementById('email').value = formData.email || '';
                document.getElementById('firstName').value = formData.firstName || '';
                document.getElementById('lastName').value = formData.lastName || '';
                document.getElementById('quartier').value = formData.quartier || '';
                document.getElementById('ville').value = formData.ville || '';
                document.getElementById('streetAddress').value = formData.streetAddress || '';
                document.getElementById('phone').value = formData.phone || '';
                document.getElementById('zipcode').value = formData.zipcode || '';
                document.getElementById('whatsapp').value = formData.whatsapp || '';
                document.getElementById('defaultAddress').checked = formData.defaultAddress || false;
                if (formData.payment_method) {
                    const radioBtn = document.querySelector(`input[name="payment_method"][value="${formData.payment_method}"]`);
                    if (radioBtn) radioBtn.checked = true;
                }
            }
        }

        async function initCheckout() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const orderSummary = document.getElementById('orderSummary');
            const orderTotal = document.getElementById('orderTotal');
            const checkoutMessage = document.getElementById('checkoutMessage');
            const editCartBtn = document.getElementById('editCartBtn');
            const modifyOrderBtn = document.getElementById('modifyOrderBtn');
            const confirmCheckout = document.getElementById('confirmCheckout');

            if (!cart.length) {
                window.location.href = 'shop.php';
                return;
            }

            let total = 0;
            orderSummary.innerHTML = '';

            await Promise.all(cart.map(async item => {
                const response = await fetch(`get_product.php?id=${item.id}`);
                const product = await response.json();
                const subtotal = product.price * item.quantity;
                total += subtotal;

                const itemCard = document.createElement('div');
                itemCard.className = 'order-item-card';
                itemCard.innerHTML = `
                    <img src="uploads/${product.image || 'default.jpg'}" alt="${product.name}" onerror="this.src='https://placehold.co/72x72/1a1a2e/ffffff?text=?'">
                    <div class="order-item-info">
                        <h3>${product.name}</h3>
                        <p>Qté: ${item.quantity}</p>
                        <p>${(product.price * 655.96).toLocaleString('fr-FR')} FCFA</p>
                    </div>
                `;
                orderSummary.appendChild(itemCard);
            }));

            const totalFCFA = (total * 655.96).toLocaleString('fr-FR');
            orderTotal.textContent = `FCFA ${totalFCFA}`;
            document.getElementById('orderGrandTotal').textContent = `FCFA ${totalFCFA}`;

            editCartBtn?.addEventListener('click', () => {
                window.location.href = 'cart.php';
            });

            modifyOrderBtn.addEventListener('click', () => {
                // Sauvegarder avant de quitter
                saveFormData();
                window.location.href = 'cart.php';
            });

            editCartBtn.addEventListener('click', () => {
                // Sauvegarder avant de quitter
                saveFormData();
                window.location.href = 'cart.php';
            });

            // Ajouter des écouteurs pour sauvegarder les données en temps réel
            document.getElementById('email').addEventListener('input', saveFormData);
            document.getElementById('firstName').addEventListener('input', saveFormData);
            document.getElementById('lastName').addEventListener('input', saveFormData);
            document.getElementById('quartier').addEventListener('input', saveFormData);
            document.getElementById('ville').addEventListener('change', saveFormData);
            document.getElementById('streetAddress').addEventListener('input', saveFormData);
            document.getElementById('phone').addEventListener('input', saveFormData);
            document.getElementById('zipcode').addEventListener('input', saveFormData);
            document.getElementById('whatsapp').addEventListener('input', saveFormData);
            document.getElementById('defaultAddress').addEventListener('change', saveFormData);
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', saveFormData);
            });

            confirmCheckout.addEventListener('click', async () => {
                const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
                const paymentMethod = paymentMethodInput ? paymentMethodInput.value : 'Inconnu';
                const email = document.getElementById('email').value.trim();
                const firstName = document.getElementById('firstName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();
                const quartier = document.getElementById('quartier').value.trim();
                const ville = document.getElementById('ville').value;
                const streetAddress = document.getElementById('streetAddress').value.trim();
                const phone = document.getElementById('phone').value.trim();

                if (!email || !firstName || !lastName || !quartier || !ville || !streetAddress || !phone) {
                    checkoutMessage.innerHTML = `<div class="notification error">Veuillez remplir tous les champs obligatoires.</div>`;
                    return;
                }

                confirmCheckout.disabled = true;
                confirmCheckout.textContent = 'En cours...';
                checkoutMessage.textContent = '';

                try {
                    const response = await fetch('checkout.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            cart,
                            payment_method: paymentMethod,
                            shipping_info: {
                                email,
                                firstName,
                                lastName,
                                quartier,
                                ville,
                                streetAddress,
                                phone,
                                zipcode: document.getElementById('zipcode').value.trim(),
                                whatsapp: document.getElementById('whatsapp').value.trim(),
                                defaultAddress: document.getElementById('defaultAddress').checked,
                            }
                        })
                    });
                    const result = await response.json();

                    if (response.ok && result.success) {
                        localStorage.removeItem('cart');
                        localStorage.removeItem('checkoutFormData');
                        checkoutMessage.innerHTML = `<div class="notification success">✅ Commande validée avec le mode '${paymentMethod}'. Numéro de commande #${result.order_id}.</div>`;
                        confirmCheckout.textContent = 'Commande validée';
                    } else {
                        checkoutMessage.innerHTML = `<div class="notification error">Erreur : ${result.message || 'Impossible d’enregistrer la commande.'}</div>`;
                        confirmCheckout.disabled = false;
                        confirmCheckout.textContent = 'PASSER COMMANDE';
                    }
                } catch (error) {
                    checkoutMessage.innerHTML = `<div class="notification error">Erreur réseau : veuillez réessayer.</div>`;
                    confirmCheckout.disabled = false;
                    confirmCheckout.textContent = 'PASSER COMMANDE';
                }
            });
        }

        // Restaurer les données du formulaire au chargement de la page
        restoreFormData();
        initCheckout();
    </script>
    <script src="js/script.js"></script>
</body>
</html>
