import { getCountCart } from './app.js';
import { addToCart } from './app.js';

//FUNCTIONS
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
    changeButton();
    const buttons = document.getElementsByClassName('btn-cart');

    Array.from(buttons).forEach(button => {
        button.addEventListener('click', event => {
            const productId = button.dataset.productId;
            clickButtonAction(productId, button);
            addToCart();
        });
    });
});