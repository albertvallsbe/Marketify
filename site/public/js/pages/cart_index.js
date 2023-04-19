import '../app.js';
import { updateServerStorage } from '../app.js';


const removeButtons = document.querySelectorAll('.btn-remove');
const clearButton = document.querySelector('.btn-empty');

//FUNCTIONS

//Treu el producte desde el carret
function removeFromLocalCart(id) {
  let cart = JSON.parse(localStorage.getItem("cart"));
  let index = cart.indexOf(id);
  if (index > -1) {
    cart.splice(index, 1);
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  location.reload();
}

//Suma del preu total
function showTotalPrice() {
  const product_names = document.querySelectorAll('.product__name');
  const product_prices = document.querySelectorAll('.product__price');
  const totalPrice = document.querySelector('.total');
  const pricesDIV = document.querySelector('.individual_prices');
  let total = 0;
  let pricesList = 0;
  for (let i = 0; i < product_names.length; i++) {
    const nameText = product_names[i].textContent;
    const priceText = product_prices[i].textContent;
    total += parseFloat(priceText);
    let individualPrices = document.createElement('h4');
    individualPrices.innerText = `${nameText}: ${priceText}`;
    individualPrices.classList.add('individual_price');
    individualPrices.style.textAlign = "right";
    pricesDIV.appendChild(individualPrices);
  }
  if (totalPrice) {
    totalPrice.textContent = `Total: ${total}.00 €`;
  }
}

//CODE
removeButtons.forEach(function (button) {
  button.addEventListener('click', function () {
    const productId = button.dataset.productId;
    removeFromLocalCart(productId);
    let searchParams = new URLSearchParams(window.location.search);
    let productIdArray = searchParams.get('id') ? searchParams.get('id').split(',') : [];
    const index = productIdArray.indexOf(productId);
    if (index > -1) {
      productIdArray.splice(index, 1);
    }
    const newQueryString = productIdArray.length ? 'id=' + productIdArray.join(',') : '';
    window.history.replaceState(null, '', '?' + newQueryString);
    window.location.reload();
  });
});

clearButton.addEventListener('click', function () {
  localStorage.removeItem('cart');
  updateServerStorage();
  window.location.href = '/cart';
});
showTotalPrice();