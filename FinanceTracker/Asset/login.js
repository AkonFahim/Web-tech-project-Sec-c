const loginForm = document.getElementById('loginForm');
const forgotPasswordLink = document.getElementById('forgotPasswordLink');
const forgotPasswordModal = document.getElementById('forgotPasswordModal');
const closeModal = document.getElementById('closeModal');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');
const resetErrorMessage = document.getElementById('resetErrorMessage');
const resetSuccessMessage = document.getElementById('resetSuccessMessage');
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('loginPassword');
const signinBtn = document.getElementById('signin-btn');

// Check if we have URL parameters (errors) - don't reset if we do
function shouldResetPage() {
    const urlParams = new URLSearchParams(window.location.search);
    return !urlParams.has('error');
}

function resetPageState() {
    resetButton();
    // Only hide messages if there are no errors in URL
    if (shouldResetPage()) {
        errorMessage.style.display = 'none';
        successMessage.style.display = 'none';
        loginForm.reset();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Don't reset if we have error parameters
    if (shouldResetPage()) {
        resetPageState();
    }
});

window.addEventListener('pageshow', function(event) {
    if (event.persisted && shouldResetPage()) {
        resetPageState();
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
  
  // Only do client-side validation if there are no existing errors
  if (shouldResetPage()) {
    errorMessage.style.display = 'none';
    successMessage.style.display = 'none';
    
    // Client-side validation - only prevent default if validation fails
    if (!email || !password) {
      e.preventDefault();
      showError(errorMessage, 'Please fill in all fields');
      return;
    }
    
    if (!isValidEmail(email)) {
      e.preventDefault();
      showError(errorMessage, 'Please enter a valid email address');
      return;
    }
  }
  
  // If validation passes or we have existing errors, show loading but ALLOW form submission
  signinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
  signinBtn.disabled = true;
});

// Rest of your JavaScript remains the same...
forgotPasswordLink.addEventListener('click', function(e) {
  e.preventDefault();
  forgotPasswordModal.classList.add('show');
});

closeModal.addEventListener('click', function() {
  forgotPasswordModal.classList.remove('show');
  resetErrorMessage.style.display = 'none';
  resetSuccessMessage.style.display = 'none';
  document.getElementById('resetEmail').value = '';
});

window.addEventListener('click', function(e) {
  if (e.target === forgotPasswordModal) {
    forgotPasswordModal.classList.remove('show');
    resetErrorMessage.style.display = 'none';
    resetSuccessMessage.style.display = 'none';
    document.getElementById('resetEmail').value = '';
  }
});

forgotPasswordForm.addEventListener('submit', function(e) {
  e.preventDefault();
  
  const email = document.getElementById('resetEmail').value;
  
  resetErrorMessage.style.display = 'none';
  resetSuccessMessage.style.display = 'none';
  
  if (!email) {
    showError(resetErrorMessage, 'Please enter your email address');
    return;
  }
  
  if (!isValidEmail(email)) {
    showError(resetErrorMessage, 'Please enter a valid email address');
    return;
  }
  
  showSuccess(resetSuccessMessage, 'Password reset link sent to your email!');
  
  setTimeout(() => {
    document.getElementById('resetEmail').value = '';
    setTimeout(() => {
      forgotPasswordModal.classList.remove('show');
      resetSuccessMessage.style.display = 'none';
    }, 500);
  }, 2000);
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

function showError(element, message) {
  element.textContent = message;
  element.style.display = 'block';
}

function showSuccess(element, message) {
  element.textContent = message;
  element.style.display = 'block';
}

function resetButton() {
  if (signinBtn) {
    signinBtn.innerHTML = 'Sign In';
    signinBtn.disabled = false;
  }
}