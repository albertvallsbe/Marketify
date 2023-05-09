import "../app.js";
import { updateOrdersServerStorage } from "../orderFunctions.js";

const ordersButton = document.querySelector(".btn-order");

ordersButton.addEventListener("click", function () {
    updateOrdersServerStorage();
    window.location.href = "/orders";
});
