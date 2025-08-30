const registerForm = document.getElementById('registerForm');
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');
const togglePassword = document.getElementById('togglePassword');
const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirmPassword');
const passwordStrength = document.getElementById('passwordStrength');

// Don't reset form if there are PHP errors
function shouldResetForm() {
    return errorMessage.textContent.trim() === '';
}

function resetFormState() {
    if (shouldResetForm()) {
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';
        registerForm.reset();
        passwordStrength.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Show error messages from PHP
    if (errorMessage.textContent.trim() !== '') {
        errorMessage.style.display = 'block';
    }
});

togglePassword.addEventListener('click', function() {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    const eyeIcon = this.querySelector('i');
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});

toggleConfirmPassword.addEventListener('click', function() {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);
    
    const eyeIcon = this.querySelector('i');
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});

passwordInput.addEventListener('input', function() {
    const password = this.value;
    let strength = '';
    let strengthClass = '';
    
    if (password.length === 0) {
        passwordStrength.style.display = 'none';
        return;
    }
    
    if (password.length < 6) {
        strength = 'Weak';
        strengthClass = 'password-weak';
    } else if (password.length < 10) {
        strength = 'Medium';
        strengthClass = 'password-medium';
    } else {
        strength = 'Strong';
        strengthClass = 'password-strong';
    }
    
    passwordStrength.textContent = `Password strength: ${strength}`;
    passwordStrength.className = `password-strength ${strengthClass}`;
    passwordStrength.style.display = 'block';
});

// Email validation without regex (matches PHP validation)
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
    
    // Check if TLD is at least 2 characters
    const tld = domainParts[domainParts.length - 1];
    if (tld.length < 2) {
        return false;
    }
    
    return true;
}

registerForm.addEventListener('submit', function(e) {
    const fullName = document.getElementById('fullName').value;
    const regEmail = document.getElementById('regEmail').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';
    
    // Client-side validation
    if (!fullName || !regEmail || !password || !confirmPassword) {
        e.preventDefault();
        showError(errorMessage, 'Please fill in all fields');
        return;
    }
    
    if (!isValidEmail(regEmail)) {
        e.preventDefault();
        showError(errorMessage, 'Please enter a valid email address');
        return;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        showError(errorMessage, 'Password must be at least 6 characters');
        return;
    }
    
    if (password !== confirmPassword) {
        e.preventDefault();
        showError(errorMessage, 'Passwords do not match');
        return;
    }
    
    // If validation passes, allow form submission to PHP
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Account...';
    submitBtn.disabled = true;
});

function showError(element, message) {
    element.textContent = message;
    element.style.display = 'block';
}

function showSuccess(element, message) {
    element.textContent = message;
    element.style.display = 'block';
}

window.addEventListener('pageshow', function(event) {
    resetFormState();
});