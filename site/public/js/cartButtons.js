import { getCountCart } from "./cartFunctions.js";
import { addToCart } from "./cartFunctions.js";
import { updateServerStorage } from "./cartFunctions.js";
import words from "./dictionaryWords.js";
//FUNCTIONS

//Afegeix el producte al localstorage
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

//Canvia l'estat dels botons quan són clicats
function clickButtonAction(productId, button) {
    if (button.innerText == words.buttons.add) {
        // if (button.innerText == "Add to carttsz") {
        addToLocalStorage(productId);
        button.innerText = words.buttons.remove;
    } else {
        let cart = localStorage.getItem("cart");
        cart = JSON.parse(cart);

        cart.forEach(function (productCart) {
            if (productCart == productId) {
                cart.splice(cart.indexOf(productId), 1);
                localStorage.removeItem("cart");
                localStorage.setItem("cart", JSON.stringify(cart));
            }
        });
        button.innerText = words.buttons.add;
    }
    updateServerStorage();
    getCountCart();
}

//Canvia l'estat de tots els botons
function changeButton() {
    let cart = localStorage.getItem("cart");
    if (cart) {
        cart = JSON.parse(cart);
        cart.forEach(function (productCart) {
            const button = document.querySelector(
                `[data-product-id="${productCart}"]`
            );
            if (button) {
                button.innerText = words.buttons.remove;
            }
        });
    }
}

//CODE
document.addEventListener("DOMContentLoaded", function () {
    changeButton();
    getCountCart();
    const buttons = document.getElementsByClassName("btn-cart");

    Array.from(buttons).forEach((button) => {
        // button.innerText = words.buttons.add;
        button.addEventListener("click", (event) => {
            const productId = button.dataset.productId;
            clickButtonAction(productId, button);
            addToCart();
        });
    });
});
