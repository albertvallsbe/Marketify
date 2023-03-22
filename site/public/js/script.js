function getCountCart() {
    let totalCount = 0;
    for (let i = 0; i < localStorage.length; i++) {
        let value = localStorage.getItem(localStorage.key(i));
        totalCount += parseInt(value);
    }
    if(totalCount != 0){
    document.getElementById('cart-count').style.display = "block";
    }
    document.getElementById('cart-count').textContent = totalCount;
}

function addToCart(productId) {
    let productCount = localStorage.getItem(productId);
    if (!productCount) {
        productCount = 0;
    }
    productCount++;
    localStorage.setItem(productId, productCount);
    getCountCart();
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