window.addEventListener('pageshow', function(event) {
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
export { getCountCart };
export { addToCart };
getCountCart();
addToCart();