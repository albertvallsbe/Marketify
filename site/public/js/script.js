function getCountCart() {
    let value = localStorage.getItem("Cart");
    value = JSON.parse(value);

    if (value) {
        if (value.length != 0) {
            document.getElementById('cart-count').style.display = "block";
            document.getElementById('cart-count').textContent = value.length;
        } else {
            document.getElementById('cart-count').style.display = "none";
        }
    }
}

function addToCart(productId) {
    let productCart = localStorage.getItem("Cart");
    if (!productCart) {
        productCart = [];
    } else {
        productCart = JSON.parse(productCart);
    }
    productCart.push(productId);
    localStorage.setItem("Cart", JSON.stringify(productCart));
}
function clickButtonAction(productId, button) {

    if (button.innerText == "Add to cart") {
        addToCart(productId);
        button.innerText = "Remove from cart";
    } else {
        let cart = localStorage.getItem("Cart");
        cart = JSON.parse(cart);
        cart.forEach(function (productCart) {
            if (productCart == productId) {
                cart.splice(cart.indexOf(productId), 1);
                localStorage.removeItem('Cart');
                localStorage.setItem("Cart", JSON.stringify(cart));
            }
        });
        button.innerText = "Add to cart";
    }
    getCountCart();
}
function changeButton() {
    let cart = localStorage.getItem("Cart");
    if (cart) {
        cart = JSON.parse(cart);
        cart.forEach(function (productCart) {
            const button = document.querySelector(`[data-product-id="${productCart}"]`);
            button.innerText = "Remove from cart";
        });
    }
}


document.addEventListener("DOMContentLoaded", function () {
    getCountCart();
    changeButton();
    const buttons = document.getElementsByClassName('btn-cart');
    Array.from(buttons).forEach(button => {
        button.addEventListener('click', event => {
            const productId = button.dataset.productId;
            clickButtonAction(productId, button);
            console.log(localStorage)
        });
    });
});