// Toggle password visibility
const eyeIcon = document.querySelector('.fa-eye-slash');
const passwordInput = document.getElementById('password');

eyeIcon.addEventListener('click', function() {
    if (passwordInput.getAttribute('type') === 'password') {
        passwordInput.setAttribute('type', 'text');
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        passwordInput.setAttribute('type', 'password');
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
    }
});

//Close
const closeButton = document.querySelector('.fa-xmark');

closeButton.addEventListener('click', function() {
    document.querySelector('.login_container').style.display = 'none';
    document.querySelector('.background').style.filter = 'none';
    document.querySelector('.background').style.backgroundColor = 'transparent';
    document.querySelector('body').style.overflow = 'visible';
});

//Catergory 
const dropdownBtn = document.querySelector('.dropBtn');
const categories = document.querySelector('.categories');

dropdownBtn.addEventListener('click', function() {
    const isVisible = categories.style.visibility === 'visible';
    categories.style.visibility = isVisible ? 'hidden' : 'visible';
});

window.addEventListener('click', function(event) {
    const target = event.target;
    if (!dropdownBtn.contains(target)) {
        categories.style.visibility = 'hidden';
    }
});
