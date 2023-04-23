//Seleccionar cookies
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

//Carret del servidor 
let arrayCart = getCookie("arrayCart");  

//Quan vas cap enrere recarga la pàgina
window.addEventListener('pageshow', function (event) {
  if (event.persisted) {
    location.reload();
  }
});

//Si hi ha un usuari iniciat s'actualitza el carret de localStorage
function updateLocalStorage() {
    if (arrayCart !== "[]") {
        localStorage.removeItem('cart');
        localStorage.setItem('cart', arrayCart);
        getCountCart();        
    }else{
    }
}

//Contador de productes en el carret
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

//Puja el carret al servidor
function addToServerCart() {
  try {
    const cart = localStorage.getItem("cart") || '[]';
    fetch('/cart', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ cart })
    }).then(() => { });
  } catch (error) {
    console.log(error);
  }
}


//Comprova si hi ha una sessió iniciada
const updateServerStorage = () => {
  try {
    fetch('/auth')
      .then(response => response.json())
      .then(data => {
        if (data.authenticated) {
          addToServerCart();
        }
      })
  } catch (error) {
    console.error(error);
  }
}

//Crea la queryString per l'enllaç al carret
function addToCart() {
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
  updateServerStorage();
}
export { getCountCart };
export { addToCart };
export { updateServerStorage };

updateLocalStorage();
addToCart();