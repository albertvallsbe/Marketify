const toggleButton = document.querySelector('.toggle-button');
const menuList = document.querySelector('.menu-list');
const logoutButton = document.querySelector('.logout');

function menuListHide(){
  if (toggleButton) {
    toggleButton.addEventListener('click', () => {
      if (menuList.style.display === 'none') {
        menuList.style.display = 'block';
      } else if (menuList.style.display === 'block') {
        menuList.style.display = 'none';
      } else {
        menuList.style.display = 'block';
      }
    });
  }
}

function deleteCart(){
  if(logoutButton){
    logoutButton.addEventListener('click', () => {
      localStorage.removeItem('cart');
    });
  }
}
menuListHide();
deleteCart();