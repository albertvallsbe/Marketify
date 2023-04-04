import '../app.js';

const avatar = document.getElementById('avatar');
const btnAvatar = document.getElementById('btn-avatar');

//FUNCTIONS
avatar.addEventListener('change', function () {
    if (avatar.value) {
        btnAvatar.removeAttribute('disabled');
    } else {
        btnAvatar.setAttribute('disabled', true);
    }
});