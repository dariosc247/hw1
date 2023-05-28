function checkUsername(event) {
    const username = usernameInput.value.trim();
    const pattern = /^[a-zA-Z0-9]+$/;

    if(username === '') {
        usernameError.classList.remove('hidden');
        event.preventDefault();
        usernameError.textContent = "Lo username non può essere vuoto!";
    }
    else if(!pattern.test(username)) {
        usernameError.classList.remove('hidden');
        event.preventDefault();
        usernameError.textContent = "Lo username può contenere solo lettere (maiuscole e/o minuscole) e numeri!";
    }
    else {
        usernameError.classList.add('hidden');
        usernameError.innerHTML = '';
    }
}

function checkPassword(event) {
    const password = passwordInput.value.trim();
    const pattern1 = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
    const pattern2 = /[!@#$%^&*()_+\-=[\]{};\'":\\|,.<>\/?]+/;
    
    if(password === '') {
        passwordError.classList.remove('hidden');
        event.preventDefault();
        passwordError.textContent = "La password non può essere vuota!";
    }
    else if(!pattern1.test(password)) {
        passwordError.classList.remove('hidden');
        event.preventDefault();
        passwordError.textContent = "La password deve contenere una lettera maiuscola, una lettera minuscola e un numero!";
    }
    else if(!pattern2.test(password)) {
        usernameError.classList.remove('hidden');
        event.preventDefault();
        passwordError.textContent = "La password deve contenere un carattere speciale!";
    }
    else {
        passwordError.classList.add('hidden');
        passwordError.innerHTML = '';
    }
}

const usernameInput = document.getElementById("username");
const usernameError = document.getElementById('username-error');
usernameError.classList.add('hidden');
usernameInput.addEventListener('blur', checkUsername);

const passwordInput = document.getElementById('password');
const passwordError = document.getElementById('password-error');
passwordError.classList.add('hidden');
passwordInput.addEventListener('input', checkPassword);