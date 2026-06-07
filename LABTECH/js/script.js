// ==================== DOM ELEMENTS ====================
// Déclarer les variables ici et les (ré)lire après le chargement du DOM
let hamburger;
let navMenu;
let navOverlay;
let searchBtn;
let searchOverlay;
let closeSearch;
let cartIcon;
let cartSidebar;
let cartOverlay;
let closeCart;
let cartItemsContainer;
let cartTotal;
let cartCount;
let searchInput;

// -------------------- Search overlay helpers --------------------
async function openSearchOverlay(initialQuery = '') {
    if (!searchOverlay) return;
    const inner = document.getElementById('searchOverlayInner');
    if (inner && inner.innerHTML.trim() === '') {
        try {
            const url = initialQuery ? `${window.APP_BASE}/search.php?overlay=1&q=${encodeURIComponent(initialQuery)}` : `${window.APP_BASE}/search.php?overlay=1`;
            const res = await fetch(url, { cache: 'no-store' });
            const html = await res.text();
            inner.innerHTML = html;
        } catch (err) {
            console.error('Erreur chargement fragment recherche', err);
            inner.innerHTML = '<p>Impossible de charger la recherche pour le moment.</p>';
        }
    }

    searchOverlay.classList.add('active');
    searchOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    initOverlaySearch();
}

function closeSearchOverlay() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('active');
    searchOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    const inner = document.getElementById('searchOverlayInner');
    if (inner) inner.innerHTML = '';
}

function initOverlaySearch() {
    const mainInput = document.getElementById('mainSearchInput');
    const suggestionsBox = document.getElementById('searchSuggestions');
    if (!mainInput) return;

    let timeout;
    mainInput.addEventListener('input', (e) => {
        clearTimeout(timeout);
        const q = e.target.value.trim();
        timeout = setTimeout(() => {
            if (q.length >= 2) fetchSearchSuggestions(q);
            else if (suggestionsBox) suggestionsBox.classList.remove('active');
        }, 250);
    });

    mainInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            const q = mainInput.value.trim();
            if (q) window.location.href = `/search.php?q=${encodeURIComponent(q)}`;
        }
    });

    // close button inside injected fragment
    const closeBtn = document.querySelector('#searchOverlayContent .close-search');
    if (closeBtn) closeBtn.addEventListener('click', closeSearchOverlay);

    // backdrop click
    const backdrop = document.getElementById('searchOverlayBackdrop');
    if (backdrop) backdrop.addEventListener('click', closeSearchOverlay);
}

// Les écouteurs liés à l'overlay de recherche sont attachés après DOMContentLoaded

// ---------------------------------------------------------------

// ==================== THEME MANAGER ====================
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
        
        if (this.themeToggle) {
            this.themeToggle.style.transform = 'scale(1.1)';
            setTimeout(() => {
                if (this.themeToggle) this.themeToggle.style.transform = 'scale(1)';
            }, 200);
        }
    }

    applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        const icon = this.themeToggle?.querySelector('.theme-icon');
        if (icon) {
            icon.innerHTML = theme === 'dark' ? '<i class="fa-solid fa-moon fa-rotate-by fa-xl" style="color: rgb(255, 212, 59); --fa-rotate-angle: 220deg;"></i>' : '<i class="fa-solid fa-sun fa-xl" style="color: rgb(255, 212, 59);"></i>';    //cette ligne 
        }
    }
}

// ==================== STOCK MANAGEMENT ====================
let productStock = {};

async function loadProductStock() {
    try {
        const response = await fetch('/get_all_products.php');
        const products = await response.json();
        products.forEach(product => {
            productStock[product.id] = {
                stock: product.stock,
                name: product.name,
                price: product.price,
                image: product.image
            };
        });
        console.log('✅ Stocks chargés:', productStock);
        updateAllCartButtons();
    } catch(error) {
        console.error('❌ Erreur chargement stocks:', error);
    }
}

function getProductStock(productId) {
    return productStock[productId]?.stock || 0;
}

// ==================== CART SYSTEM ====================
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartUI();
    updateAllCartButtons();
}

async function submitOrder(paymentMethod = 'Paiement à la livraison') {
    const currentCart = JSON.parse(localStorage.getItem('cart') || '[]');
    if (currentCart.length === 0) {
        showNotification('Votre panier est vide', 'error');
        return;
    }

    try {
        const response = await fetch('/checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart: currentCart, payment_method: paymentMethod })
        });

        const result = await response.json();
        if (!response.ok || !result.success) {
            showNotification(result.message || 'Impossible de passer la commande.', 'error');
            return;
        }

        cart = [];
        saveCart();
        showNotification('✅ Commande envoyée au vendeur !', 'success');
    } catch (error) {
        console.error('Erreur checkout:', error);
        showNotification('Erreur lors de l’envoi de la commande.', 'error');
    }
}

function updateCartUI() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCount) cartCount.textContent = totalItems;
    
    if (!cartItemsContainer) return;
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="cart-empty">
                <span>🛒</span>
                <p>Votre panier est vide</p>
            </div>
        `;
        if (cartTotal) cartTotal.textContent = '0 FCFA';
        return;
    }
    
    let itemsHtml = '';
    let total = 0;
    
    for (const item of cart) {
        const subtotal = item.price * item.quantity;
        total += subtotal;
        const maxStock = getProductStock(item.id);
        
        itemsHtml += `
            <div class="cart-item" data-id="${item.id}">
                <img src="uploads/${item.image || 'default.jpg'}" alt="${item.name}" class="cart-item-img" onerror="this.src='uploads/default.jpg'">
                <div class="cart-item-info">
                    <h4 class="cart-item-title">${escapeHtml(item.name)}</h4>
                    <p class="cart-item-price">${item.price.toLocaleString('fr-FR')} FCFA</p>
                    <div class="cart-item-quantity">
                        <button class="decrease-qty" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                        <input type="number" class="cart-qty-input" value="${item.quantity}" min="1" max="${maxStock}" data-id="${item.id}" style="width: 60px; text-align: center;">
                        <button class="increase-qty" ${item.quantity >= maxStock ? 'disabled' : ''}>+</button>
                        <button class="remove-item">🗑️</button>
                    </div>
                    ${item.quantity >= maxStock ? '<span class="stock-warning">⚠️ Stock maximum atteint</span>' : ''}
                </div>
            </div>
        `;
    }
    
    cartItemsContainer.innerHTML = itemsHtml;
    if (cartTotal) cartTotal.textContent = `${total.toLocaleString('fr-FR')} FCFA`;
    
    // Événements pour les inputs de quantité
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        input.addEventListener('change', (e) => {
            const id = parseInt(e.target.dataset.id);
            let newQty = parseInt(e.target.value);
            const maxStock = getProductStock(id);
            
            if (isNaN(newQty) || newQty < 1) newQty = 1;
            if (newQty > maxStock) {
                newQty = maxStock;
                showNotification(`Stock limité à ${maxStock} exemplaires`, 'warning');
            }
            
            const item = cart.find(i => i.id === id);
            if (item) {
                item.quantity = newQty;
                saveCart();
            }
        });
    });
    
    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const cartItem = btn.closest('.cart-item');
            const id = parseInt(cartItem.dataset.id);
            const item = cart.find(i => i.id === id);
            if (item && item.quantity > 1) {
                item.quantity--;
                saveCart();
            }
        });
    });
    
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const cartItem = btn.closest('.cart-item');
            const id = parseInt(cartItem.dataset.id);
            const item = cart.find(i => i.id === id);
            const maxStock = getProductStock(id);
            if (item && item.quantity < maxStock) {
                item.quantity++;
                saveCart();
            } else {
                showNotification(`Stock limité à ${maxStock} exemplaires`, 'warning');
            }
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const cartItem = btn.closest('.cart-item');
            const id = parseInt(cartItem.dataset.id);
            cart = cart.filter(i => i.id !== id);
            saveCart();
            showNotification('Produit retiré du panier', 'success');
        });
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function isInCart(productId) {
    return cart.some(item => item.id === productId);
}

function getCartQuantity(productId) {
    const item = cart.find(item => item.id === productId);
    return item ? item.quantity : 0;
}

function updateAllCartButtons() {
    const allButtons = document.querySelectorAll('.add-to-cart');
    
    allButtons.forEach(button => {
        const productId = parseInt(button.dataset.id);
        const stock = parseInt(button.dataset.stock) || getProductStock(productId);
        const inCart = isInCart(productId);
        const cartQty = getCartQuantity(productId);
        
        if (stock <= 0) {
            button.textContent = '📦 Rupture de stock';
            button.classList.add('out-of-stock');
            button.disabled = true;
            button.style.opacity = '0.5';
        } else if (inCart) {
            button.textContent = `✓ Déjà dans le panier (${cartQty}/${stock})`;
            button.classList.add('in-cart');
            button.disabled = true;
            button.style.opacity = '0.7';
        } else {
            button.textContent = `🛒 Ajouter au panier (${stock} dispo)`;
            button.classList.remove('in-cart', 'out-of-stock');
            button.disabled = false;
            button.style.opacity = '1';
        }
    });
}

// Ajouter cette fonction dans script.js si elle n'existe pas déjà
function addToCart(id, name, price, image = 'default.jpg', stock) {
    // Vérifier si déjà dans le panier
    if (isInCart(id)) {
        showNotification(`${name} est déjà dans votre panier !`, 'info');
        return;
    }
    
    // Vérifier le stock
    const availableStock = stock || getProductStock(id);
    if (availableStock <= 0) {
        showNotification(`Désolé, ${name} n'est plus en stock !`, 'error');
        return;
    }
    
    // Ajouter au panier
    cart.push({ id, name, price, quantity: 1, image });
    saveCart();
    showNotification(`${name} ajouté au panier !`, 'success');
    
    // Mettre à jour le bouton
    updateAllCartButtons();
}

function showNotification(message, type) {
    const oldNotifications = document.querySelectorAll('.notification');
    oldNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    let bgColor = 'linear-gradient(135deg, #00d4ff, #7b2ff7)';
    if (type === 'success') bgColor = 'linear-gradient(135deg, #00ff88, #00cc66)';
    else if (type === 'error') bgColor = 'linear-gradient(135deg, #ff4444, #cc0000)';
    else if (type === 'warning') bgColor = 'linear-gradient(135deg, #ffaa00, #cc8800)';
    
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${bgColor};
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// ==================== PAGE PRODUIT - CHARGEMENT DES DÉTAILS ====================
async function loadProductDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    
    if (!productId) return;
    
    try {
        const response = await fetch(`/get_product.php?id=${productId}`);
        const product = await response.json();
        
        if (!product) {
            window.location.href = 'shop.php';
            return;
        }
        
        // Mettre à jour la page
        document.title = `${product.name} - DATALAB-TECH`;
        
        const detailContainer = document.querySelector('.product-detail');
        if (detailContainer) {
            detailContainer.innerHTML = `
                <div class="product-detail-image">
                    <img src="uploads/${product.image}" alt="${escapeHtml(product.name)}" onerror="this.src='https://placehold.co/600x600/1a1a2e/00d4ff?text=Product'">
                </div>
                <div class="product-detail-info">
                    <h1>${escapeHtml(product.name)}</h1>
                    <p class="product-category">📁 Catégorie : ${escapeHtml(product.category)}</p>
                    <p class="product-detail-price">${formatPrice(product.price)}</p>
                    <p class="stock-info" style="color: ${product.stock > 0 ? '#00ff88' : '#ff4444'}">
                        ${product.stock > 0 ? `✅ En stock (${product.stock} unités)` : '❌ Rupture de stock'}
                    </p>
                    <div class="product-description">
                        <h3>Description</h3>
                        <p>${escapeHtml(product.description).replace(/\n/g, '<br>')}</p>
                    </div>
                    ${product.stock > 0 ? 
                        `<button class="add-to-cart detail-add-btn" data-id="${product.id}" data-name="${escapeHtml(product.name)}" data-price="${product.price * 655.96}" data-image="${product.image}" data-stock="${product.stock}">
                            🛒 Ajouter au panier (${product.stock} dispo)
                        </button>` : 
                        `<button class="out-of-stock detail-add-btn" disabled>Rupture de stock</button>`
                    }
                </div>
            `;
            
            // Réattacher l'événement au nouveau bouton
            const newButton = document.querySelector('.detail-add-btn');
            if (newButton && !newButton.disabled) {
                newButton.addEventListener('click', (e) => {
                    const id = parseInt(newButton.dataset.id);
                    const name = newButton.dataset.name;
                    const price = parseInt(newButton.dataset.price);
                    const image = newButton.dataset.image;
                    const stock = parseInt(newButton.dataset.stock);
                    addToCart(id, name, price, image, stock);
                });
            }
        }
    } catch(error) {
        console.error('Erreur chargement produit:', error);
    }
}

function formatPrice(priceInEuro) {
    const priceInXaf = priceInEuro * 655.96;
    return priceInXaf.toLocaleString('fr-FR') + ' FCFA';
}

// ==================== FILTRAGE DES PRODUITS ====================
function filterProducts(category) {
    const products = document.querySelectorAll('.product-card');
    
    let visibleCount = 0;
    
    products.forEach(product => {
        let productCategory = product.dataset.category;
        let isVisible = false;
        
        switch(category) {
            case 'all':
                isVisible = true;
                break;
            case 'audio':
                isVisible = (productCategory === 'audio' || productCategory === 'ecouteurs');
                break;
            case 'power':
                isVisible = (productCategory === 'power' || productCategory === 'powerbanks' || productCategory === 'chargeurs');
                break;
            case 'smart':
                isVisible = (productCategory === 'smart' || productCategory === 'accessoires');
                break;
            case 'accessories':
                isVisible = (productCategory === 'accessories' || productCategory === 'accessoires');
                break;
            default:
                isVisible = (productCategory === category);
        }
        
        if (isVisible) {
            product.style.display = '';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });
    
    const productsGrid = document.getElementById('productsGrid');
    let noResultsMsg = document.getElementById('noResultsMessage');
    
    if (visibleCount === 0) {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.id = 'noResultsMessage';
            noResultsMsg.style.cssText = 'text-align: center; padding: 3rem; grid-column: 1 / -1;';
            noResultsMsg.innerHTML = '<p style="color: var(--text-secondary);">⚠️ Aucun produit trouvé dans cette catégorie.</p>';
            if (productsGrid) productsGrid.appendChild(noResultsMsg);
        }
        noResultsMsg.style.display = 'block';
    } else {
        if (noResultsMsg) noResultsMsg.style.display = 'none';
    }
}

// ==================== INITIALISATION DES FILTRES ====================
function initFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    filterBtns.forEach(btn => {
        btn.removeEventListener('click', handleFilterClick);
        btn.addEventListener('click', handleFilterClick);
    });
}

function handleFilterClick(e) {
    const filter = this.dataset.filter;
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    this.classList.add('active');
    
    filterProducts(filter);
}

// ==================== GESTION DES CATÉGORIES ====================
function initCategoryClick() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        card.removeEventListener('click', handleCategoryClick);
        card.addEventListener('click', handleCategoryClick);
    });
}

function handleCategoryClick(e) {
    e.preventDefault();
    const category = this.dataset.category;
    window.location.href = `shop.php?category=${category}`;
}

// ==================== RECHERCHE ====================
function initSearch() {
    const searchInputDynamic = document.getElementById('searchInput');
    if (searchInputDynamic) {
        searchInputDynamic.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                const title = product.querySelector('.product-title')?.textContent.toLowerCase() || '';
                if (title.includes(searchTerm)) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    }
}

// ==================== SLIDER ====================
function initSlider() {
    if (document.getElementById('slider') && document.querySelectorAll('.slide').length > 0) {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const slider = document.getElementById('slider');

        function updateSlider() {
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            document.querySelectorAll('.dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        const dotsContainer = document.querySelector('.slider-dots');
        if (dotsContainer && dotsContainer.children.length === 0) {
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentSlide = i;
                    updateSlider();
                    resetAutoplay();
                });
                dotsContainer.appendChild(dot);
            }
        }

        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');

        if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetAutoplay(); });
        if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetAutoplay(); });

        let autoplay = setInterval(nextSlide, 5000);
        
        function resetAutoplay() {
            clearInterval(autoplay);
            autoplay = setInterval(nextSlide, 5000);
        }
        
        const sliderContainer = document.querySelector('.slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => clearInterval(autoplay));
            sliderContainer.addEventListener('mouseleave', () => {
                autoplay = setInterval(nextSlide, 5000);
            });
        }
        
        updateSlider();
    }
}

// ==================== SCROLL ANIMATIONS ====================
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-up').forEach(el => {
        observer.observe(el);
    });
}

// ==================== INITIALISATION GLOBALE ====================
document.addEventListener('DOMContentLoaded', async () => {
    // Récupérer les éléments du DOM maintenant que la page est chargée
    hamburger = document.getElementById('hamburger');
    navMenu = document.getElementById('navMenu');
    navOverlay = document.getElementById('navOverlay');
    searchBtn = document.getElementById('searchBtn');
    searchOverlay = document.getElementById('searchOverlay');
    closeSearch = document.getElementById('closeSearch');
    cartIcon = document.getElementById('cartIcon');
    cartSidebar = document.getElementById('cartSidebar');
    cartOverlay = document.getElementById('cartOverlay');
    closeCart = document.getElementById('closeCart');
    cartItemsContainer = document.getElementById('cartItems');
    cartTotal = document.getElementById('cartTotal');
    cartCount = document.getElementById('cartCount');
    searchInput = document.getElementById('searchInput');

    console.log('🚀 Initialisation du site...');

    // ==================== Overlay Search Listeners ====================
    if (searchBtn) {
        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openSearchOverlay();
        });
    }

    if (closeSearch) {
        closeSearch.addEventListener('click', (e) => {
            e.preventDefault();
            closeSearchOverlay();
        });
    }

    if (searchOverlay) {
        // allow closing with Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) closeSearchOverlay();
        });
    }
    
    // Charger les stocks
    await loadProductStock();
    
    // Thème
    new ThemeManager();
    
    // Panier
    updateCartUI();
    updateAllCartButtons();
    
    // Charger les détails du produit si on est sur product.php
    if (window.location.pathname.includes('product.php')) {
        await loadProductDetails();
    }
    
    // Filtres
    initFilters();
    
    // Catégories
    initCategoryClick();
    
    // Recherche
    initSearch();
    
    // Slider
    initSlider();
    
    // Animations
    initScrollAnimations();
    
    // Événements d'ajout au panier
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = parseInt(btn.dataset.id);
            const name = btn.dataset.name;
            const price = parseInt(btn.dataset.price);
            const image = btn.dataset.image || 'default.jpg';
            const stock = parseInt(btn.dataset.stock);
            addToCart(id, name, price, image, stock);
        });
    });
    
    // Panier
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            cartSidebar?.classList.add('open');  // cette ligne permet d'ouvrir le menu latéral du panier en ajoutant une classe "open" qui déclenche les styles CSS associés pour faire glisser le panier depuis la droite de l'écran
            cartOverlay?.classList.add('active');  // cette ligne ajoute une superposition sombre derrière le panier pour mettre en avant le menu du panier et empêcher les interactions avec le reste de la page
        });
    }
    
    if (closeCart) {
        closeCart.addEventListener('click', () => {
            cartSidebar?.classList.remove('open');
            cartOverlay?.classList.remove('active');
        });
    }
    
    if (cartOverlay) {
        cartOverlay.addEventListener('click', () => {
            cartSidebar?.classList.remove('open');
            cartOverlay.classList.remove('active');
        });
    }
    
    // Menu hamburger
    const closeNavMenu = () => {
        hamburger?.classList.remove('active');
        navMenu?.classList.remove('active');
        navOverlay?.classList.remove('active');
        document.body.classList.remove('no-scroll');
    };

    const openNavMenu = () => {
        hamburger?.classList.add('active');
        navMenu?.classList.add('active');
        navOverlay?.classList.add('active');
        document.body.classList.add('no-scroll');
    };

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            if (navMenu?.classList.contains('active')) {
                closeNavMenu();
            } else {
                openNavMenu();
            }
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener('click', () => {
            closeNavMenu();
        });
    }

    window.addEventListener('scroll', () => {
        if (navMenu?.classList.contains('active')) {
            closeNavMenu();
        }
    });

    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            closeNavMenu();
        });
    });
    
    // Quick view
    document.querySelectorAll('.quick-view').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const productCard = btn.closest('.product-card');
            const productName = productCard.querySelector('.product-title')?.textContent;
            showNotification(`Aperçu de ${productName}`, 'info');
        });
    });
    
    // Checkout
    document.body.addEventListener('click', (event) => {
        const checkoutButton = event.target.closest('.checkout-btn');
        if (!checkoutButton) return;
        event.preventDefault();
        window.location.href = '/checkout.php';
    });
    
    console.log('✅ Initialisation terminée');
});

// Ajout des styles d'animation
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .stock-warning {
        display: block;
        color: #ffaa00;
        font-size: 0.7rem;
        margin-top: 5px;
    }
    .cart-qty-input {
        background: var(--bg-input);
        border: 1px solid var(--border-color);
        border-radius: 5px;
        color: var(--text-primary);
        padding: 4px;
        text-align: center;
    }
    .cart-qty-input:focus {
        outline: none;
        border-color: var(--accent-primary);
    }
`;
document.head.appendChild(styleSheet);

// ==================== RECHERCHE EN DIRECT AVEC SUGGESTIONS ====================
let searchTimeout;

async function fetchSearchSuggestions(query) {
    if (query.length < 2) {
        document.getElementById('searchSuggestions')?.remove();
        return;
    }
    
    try {
        const response = await fetch(`/search_suggestions.php?q=${encodeURIComponent(query)}`);
        const suggestions = await response.json();
        showSearchSuggestions(suggestions);
    } catch(error) {
        console.error('Erreur suggestions:', error);
    }
}

function showSearchSuggestions(suggestions) {
    let suggestionsBox = document.getElementById('searchSuggestions');
    
    if (!suggestionsBox) {
        suggestionsBox = document.createElement('div');
        suggestionsBox.id = 'searchSuggestions';
        suggestionsBox.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            margin-top: 5px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
        `;
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.style.position = 'relative';
            searchContainer.appendChild(suggestionsBox);
        }
    }
    
    if (suggestions.length === 0) {
        suggestionsBox.innerHTML = '<div style="padding: 10px; color: var(--text-muted);">Aucun produit trouvé</div>';
        return;
    }
    
    suggestionsBox.innerHTML = suggestions.map(product => `
        <a href="/product.php?id=${product.id}" style="display: flex; align-items: center; gap: 10px; padding: 10px; text-decoration: none; color: var(--text-primary); border-bottom: 1px solid var(--border-color); transition: background var(--transition-fast);">
            <img src="/uploads/${product.image}" alt="${product.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px;" onerror="this.src='https://placehold.co/40x40/1a1a2e/00d4ff?text=?'">
            <div style="flex: 1;">
                <strong>${product.name}</strong>
                <span style="display: block; font-size: 0.75rem; color: var(--text-muted);">${product.price.toLocaleString('fr-FR')} FCFA</span>
            </div>
            <span style="color: var(--accent-primary);">→</span>
        </a>
    `).join('');
}

// searchInput listeners are initialized in initSearch() during DOMContentLoaded