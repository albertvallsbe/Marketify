//FUNCTIONS

function getCountCart() {
    let value = localStorage.getItem("cart");
    let number = document.getElementById('cart-count');
    value = JSON.parse(value);

    if (value) {
        if (value.length != 0) {
            number.style.display = "block";
            number.textContent = value.length;
        } else {
            number.style.display = "none";
        }
    }
}

function addToLocalStorage(productId) {
    let productCart = localStorage.getItem("cart");

    if (!productCart) {
        productCart = [];
    } else {
        productCart = JSON.parse(productCart);
    }
    productCart.push(productId);
    localStorage.setItem("cart", JSON.stringify(productCart));
}

function addToCart(){
    let cart = localStorage.getItem("cart");
if (cart) {
    cart = JSON.parse(cart);
    const queryString = cart.map(product => {
        return `${product}`;
    }).join(',');

    let linkPlaceholder = document.getElementById('cart-link');
    if (linkPlaceholder) {
        linkPlaceholder.href = "/cart?id=" + queryString;
    }
}}

function clickButtonAction(productId, button) {

    if (button.innerText == "Add to cart") {
        addToLocalStorage(productId);
        button.innerText = "Remove from cart";
    } else {
        let cart = localStorage.getItem("cart");
        cart = JSON.parse(cart);

        cart.forEach(function (productCart) {
            if (productCart == productId) {
                cart.splice(cart.indexOf(productId), 1);
                localStorage.removeItem('cart');
                localStorage.setItem("cart", JSON.stringify(cart));
            }
        });
        button.innerText = "Add to cart";
    }
    getCountCart();
}

function changeButton() {
    let cart = localStorage.getItem("cart");
    if (cart) {
        cart = JSON.parse(cart);
        cart.forEach(function (productCart) {
            const button = document.querySelector(`[data-product-id="${productCart}"]`);
            if(button){
                button.innerText = "Remove from cart";
            }
        });
    }
}

//CODE
document.addEventListener("DOMContentLoaded", function () {
    getCountCart();
    changeButton();
    addToCart();
    const buttons = document.getElementsByClassName('btn-cart');

    Array.from(buttons).forEach(button => {
        button.addEventListener('click', event => {
            const productId = button.dataset.productId;
            clickButtonAction(productId, button);
            addToCart();
        });
    });
});