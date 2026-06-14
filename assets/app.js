// ===== IMPORTS =====
import './stimulus_bootstrap.js';
import './styles/app.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';

// ===== FONCTIONS =====

// --- Boutons Customer/Artist ---
function initRoleButtons() {
    const roleInputs = document.querySelectorAll('[data-role-label] input');
    if (roleInputs.length === 0) return;

    function updateRoleButtons() {
        document.querySelectorAll('[data-role-label]').forEach(label => {
            const input = label.querySelector('input');
            if (input) {
                label.classList.toggle('active', input.checked);
            }
        });
    }

    updateRoleButtons();
    roleInputs.forEach(input => {
        input.addEventListener('change', updateRoleButtons);
    });
}

// --- Carrousel ---
function initCarousel() {
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    if (!track || !prevBtn || !nextBtn) return;

    const items = track.querySelectorAll('.ks-carousel-item');
    const itemsPerView = 4;
    let currentIndex = 0;
    const maxIndex = Math.max(0, items.length - itemsPerView);

    function updateCarousel() {
        const itemWidth = items[0].offsetWidth + 16;
        track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex < maxIndex) { currentIndex++; updateCarousel(); }
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) { currentIndex--; updateCarousel(); }
    });

    window.addEventListener('resize', updateCarousel);
}

// --- Page produit ---
function initProductPage() {
    const templateOptions = document.querySelectorAll('.ks-template-option');
    const productButtons = document.getElementById('product-buttons');
    const priceDisplay = document.getElementById('product-price');
    const btnBuy = document.getElementById('btn-buy');
    if (!templateOptions.length || !productButtons) return;

    function loadTemplate(templateEl) {
        templateOptions.forEach(t => t.classList.remove('active'));
        templateEl.classList.add('active');

        const products = JSON.parse(templateEl.dataset.products);
        productButtons.innerHTML = '';

        products.forEach((product, index) => {
            const btn = document.createElement('button');
            btn.className = 'ks-product-btn-support' + (index === 0 ? ' active' : '');
            btn.textContent = product.type.charAt(0).toUpperCase() + product.type.slice(1);
            btn.dataset.productId = product.id;
            btn.dataset.price = product.price;

            btn.addEventListener('click', () => {
                productButtons.querySelectorAll('.ks-product-btn-support').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                priceDisplay.textContent = parseFloat(product.price).toFixed(0) + ' €';
                btnBuy.href = '/cart/add/' + product.id;
            });

            productButtons.appendChild(btn);

            if (index === 0) {
                priceDisplay.textContent = parseFloat(product.price).toFixed(0) + ' €';
                btnBuy.href = '/cart/add/' + product.id;
            }
        });
    }

    loadTemplate(templateOptions[0]);
    templateOptions.forEach(templateEl => {
        templateEl.addEventListener('click', () => loadTemplate(templateEl));
    });
}

// --- Menu mobile ---
function initMobileMenu() {
    const burger = document.getElementById('mobileMenuOpen');
    const menu = document.getElementById('mobileMenu');

    if (!burger || !menu) return;

    burger.addEventListener('click', () => {
        const isOpen = menu.classList.toggle('open');
        burger.classList.toggle('open', isOpen);
    });

    // Ferme le menu au clic sur un lien
    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.remove('open');
            burger.classList.remove('open');
        });
    });
}

// ===== DÉCLENCHEMENT =====
document.addEventListener('DOMContentLoaded', () => {
    initRoleButtons();
    initCarousel();
    initProductPage();
    initMobileMenu();
});