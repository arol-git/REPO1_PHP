// Gestion du panier avec état "déjà dans le panier"
class CartManager {
    constructor() {
        this.cart = this.loadCart();
        this.init();
    }

    loadCart() {
        return JSON.parse(localStorage.getItem('cart') || '[]');
    }

    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.cart));
        this.updateButtons();
        this.updateCartCount();
    }

    addItem(productId) {
        const existing = this.cart.find(item => item.id == productId);
        if (existing) {
            existing.quantity++;
        } else {
            this.cart.push({ id: productId, quantity: 1 });
        }
        this.saveCart();
        this.showNotification('Produit ajouté au panier !', 'success');
    }

    isInCart(productId) {
        return this.cart.some(item => item.id == productId);
    }

    updateButtons() {
        document.querySelectorAll('.add-to-cart').forEach(btn => {
            const id = btn.dataset.id;
            if (this.isInCart(id)) {
                btn.classList.add('in-cart');
                btn.textContent = '✓ Déjà dans le panier';
                btn.disabled = true;
            } else {
                btn.classList.remove('in-cart');
                btn.textContent = 'Ajouter au panier';
                btn.disabled = false;
            }
        });
    }

    updateCartCount() {
        const count = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartCount = document.getElementById('cart-count');
        if (cartCount) cartCount.textContent = count;
    }

    showNotification(message, type) {
        const notif = document.createElement('div');
        notif.className = `notification ${type}`;
        notif.textContent = message;
        document.body.appendChild(notif);
        setTimeout(() => notif.remove(), 3000);
    }

    init() {
        this.updateButtons();
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart') && !e.target.disabled) {
                this.addItem(e.target.dataset.id);
            }
        });
    }
}

// Gestion du menu à trois points
document.addEventListener('DOMContentLoaded', () => {
    new CartManager();

    // Menu dropdown
    document.querySelectorAll('.menu-trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const dropdown = trigger.nextElementSibling;
            document.querySelectorAll('.menu-dropdown').forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });
            dropdown.classList.toggle('show');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.menu-dropdown').forEach(d => d.classList.remove('show'));
    });

    // Quick add
    document.querySelectorAll('.quick-add').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const id = btn.dataset.id;
            const cart = new CartManager();
            cart.addItem(id);
        });
    });

    // Recherche
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name?.toLowerCase() || '';
                card.style.display = name.includes(term) ? '' : 'none';
            });
        });
    }
});

// Slider
if (document.getElementById('slider')) {
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

    setInterval(nextSlide, 5000);
}