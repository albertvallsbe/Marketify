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
export { getCountCart };
getCountCart();