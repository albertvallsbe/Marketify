const toggleButton = document.querySelector('.toggle-button');
const menuList = document.querySelector('.menu-list');

toggleButton.addEventListener('click', () => {
  if (menuList.style.display === 'none') {
    menuList.style.display = 'block';
  } else {
    menuList.style.display = 'none';
  }
});