import "../app.js";
import { updateCartServerStorage, getCountCart } from "../cartFunctions.js";

const ordersButton = document.querySelector(".btn-order");

ordersButton.addEventListener("click", function () {
    let arrayCart = "[]";
    let cart = "[]";
    localStorage.removeItem("arrayCart");
    localStorage.removeItem("cart");
    localStorage.setItem("arrayCart", arrayCart);
    localStorage.setItem("cart", cart);
    updateCartServerStorage();
    getCountCart();
});
