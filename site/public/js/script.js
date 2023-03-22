function getCountCart() {
    let totalCount = 0;
    for (let i = 0; i < localStorage.length; i++) {
        let value = localStorage.getItem(localStorage.key(i));
        totalCount += parseInt(value);
    }
    document.getElementById('cart-count').textContent = totalCount;
}

function addToCart(productId) {
    let productCount = localStorage.getItem(productId);
    if (!productCount) {
        productCount = 0;
    }
    productCount++;
    getCountCart();
    localStorage.setItem(productId, productCount);
}

document.addEventListener("DOMContentLoaded", function () {
    getCountCart();
    const buttons = document.getElementsByClassName('btn-cart');
    Array.from(buttons).forEach(button => {
        button.addEventListener('click', event => {
            const productId = button.dataset.productId;
            addToCart(productId);
        });
    });
});