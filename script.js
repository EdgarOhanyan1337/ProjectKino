// Получаем ссылки на формы и меню
const registerForm = document.getElementById('register');
const loginForm = document.getElementById('login');
const registerLink = document.getElementById('registerLink');
const loginLink = document.getElementById('loginLink');

// Функция для переключения форм
function showForm(formToShow) {
    if (formToShow === 'register') {
        registerForm.classList.add('active');
        loginForm.classList.remove('active');
        registerLink.classList.add('active');
        loginLink.classList.remove('active');
    } else if (formToShow === 'login') {
        loginForm.classList.add('active');
        registerForm.classList.remove('active');
        loginLink.classList.add('active');
        registerLink.classList.remove('active');
    }
}

// Слушатели событий для переключения форм
registerLink.addEventListener('click', () => showForm('register'));
loginLink.addEventListener('click', () => showForm('login'));

// Изначально показываем форму регистрации
showForm('register');
