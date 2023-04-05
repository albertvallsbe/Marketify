window.addEventListener('pageshow', function (event) {
  if (event.persisted) {
    location.reload();
  }
});

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

function addToServerCart() {
  let cart = localStorage.getItem("cart");
  if (cart) {
    fetch('/cart', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ cart: cart })
    }).then(function (response) {
    });
  }
}

function addToClientCart() {
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
  }
  addToServerCart();
}
export { getCountCart };
export { addToClientCart };
getCountCart();
addToClientCart();