const loginForm = document.getElementById('loginForm');
const errorMessage = document.getElementById('errorMessage');
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('loginPassword');
const signinBtn = document.getElementById('signin-btn');

document.addEventListener('DOMContentLoaded', function() {
    if (errorMessage && errorMessage.textContent.trim() !== '') {
        errorMessage.style.display = 'block';
    }
        if (window.history.replaceState && window.location.search) {
        const cleanUrl = window.location.origin + window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);
    }
});

togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    const eyeIcon = this.querySelector('i');
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});

loginForm.addEventListener('submit', function(e) {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    if (errorMessage) {
        errorMessage.style.display = 'none';
        errorMessage.textContent = '';
    }
    
    if (!email || !password) {
        e.preventDefault();
        showError('Please fill in all fields..');
        return;
    }
    
    if (!isValidEmail(email)) {
        e.preventDefault();
        showError('Please enter a valid email address..');
        return;
    }
    
      signinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
      signinBtn.disabled = true;
      setTimeout(() => {
        signinBtn.innerHTML = 'Sign In';
        signinBtn.disabled = false;
        window.location.href = '../View/dashboard.php';
    }, 1000);
});

function isValidEmail(email) {
    if (email.indexOf('@') === -1) {
        return false;
    }
    
    const parts = email.split('@');
    const localPart = parts[0];
    const domain = parts[1];
    
    if (!localPart || !domain) {
        return false;
    }
    
    if (domain.indexOf('.') === -1) {
        return false;
    }
    
    const domainParts = domain.split('.');
    if (domainParts.length < 2 || !domainParts[0] || !domainParts[1]) {
        return false;
    }
    
    if (email.indexOf(' ') !== -1) {
        return false;
    }
    
    return true;
}

function showError(message) {
    if (errorMessage) {
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';
    }
}

function resetButton() {
    if (signinBtn) {
        signinBtn.innerHTML = 'Sign In';
        signinBtn.disabled = false;
    }
}

window.addEventListener('beforeunload', function() {
    resetButton();
});