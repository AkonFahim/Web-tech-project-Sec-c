    const registerForm = document.getElementById('registerForm');
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordStrength = document.getElementById('passwordStrength');

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

    registerForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const fullName = document.getElementById('fullName').value;
      const regEmail = document.getElementById('regEmail').value;
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      
      errorMessage.style.display = 'none';
      successMessage.style.display = 'none';
      
      if (!fullName || !regEmail || !password || !confirmPassword) {
        showError(errorMessage, 'Please fill in all fields');
        return;
      }
      
      if (!isValidregEmail(regEmail)) {
        showError(errorMessage, 'Please enter a valid Email address');
        return;
      }
      
      if (password.length < 6) {
        showError(errorMessage, 'Password must be at least 6 characters');
        return;
      }
      
      if (password !== confirmPassword) {
        showError(errorMessage, 'Passwords do not match');
        return;
      }
      
      showSuccess(successMessage, 'Account created successfully! Redirecting to login...');
      
      setTimeout(() => {
        registerForm.reset();
        passwordStrength.style.display = 'none';
        window.location.href = "../view/login.php";
      }, 2000);
    });

    function isValidregEmail(regEmail) {
      if (regEmail.indexOf('@') === -1) {
        return false;
      }
      
      const parts = regEmail.split('@');
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
      
      if (regEmail.indexOf(' ') !== -1) {
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


    window.addEventListener('pageshow', function(event) {
    const signinBtn = document.getElementById('signin-btn');
    if (signinBtn) {
        signinBtn.innerHTML = 'Sign In';
        signinBtn.disabled = false;
    }
  
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    
    if (errorMessage) errorMessage.style.display = 'none';
    if (successMessage) successMessage.style.display = 'none';
    
    document.getElementById('loginForm').reset();
  });

