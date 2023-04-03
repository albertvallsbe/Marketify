const toggleButton = document.querySelector('.toggle-button');
const menuList = document.querySelector('.menu-list');
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