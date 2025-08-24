const card = document.getElementById("card");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");
const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
const showLoginFromReset = document.getElementById('showLoginFromReset');

showRegister.addEventListener("click", function() {
    card.classList.add("flip");
});

showLogin.addEventListener("click", function() {
    card.classList.remove("flip");
});

function login() {
    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;
    const errorMsg = document.getElementById("errorMsg");

    errorMsg.textContent = '';

    if (email === "admin" && password === "admin") {
        window.location.href = "home.html";
    } else {
        errorMsg.textContent = "Invalid email or password!";
        errorMsg.style.color = "red";
    }
}

function resetPassword() {
    const email = document.getElementById('resetEmail').value;
    const successMsg = document.getElementById('resetSuccessMsg');
    const errorMsg = document.getElementById('resetErrorMsg');

    successMsg.textContent = '';
    errorMsg.textContent = '';

    if (!email) {
        errorMsg.textContent = 'Please enter your email.';
        return;
    }

    setTimeout(() => {
        if (email.includes('@')) {
            successMsg.textContent = 'Password reset link sent to your email!';
        } else {
            errorMsg.textContent = 'Invalid email address.';
        }
    }, 500);
}



forgotPasswordBtn.addEventListener("click", function() {
    document.getElementById('loginform').style.display = 'none';
    card.classList.remove("flip");
    document.getElementById('resetForm').style.display = 'block';
});

showLoginFromReset.addEventListener("click", function() {
    document.getElementById('resetForm').style.display = 'none';
    document.getElementById('loginform').style.display = 'block';
});

showLoginFromReset.addEventListener("click", function() {
    document.getElementById('resetForm').style.display = 'none';
    document.querySelector('.loginform').style.display = 'block';
});
