 document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('forgotPasswordForm');
      const errorMessage = document.getElementById('errorMessage');
      const successMessage = document.getElementById('successMessage');
      const submitBtn = document.getElementById('submit-btn');
      
      if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
          const email = document.getElementById('resetEmail').value;
          
          if (errorMessage) errorMessage.style.display = 'none';
          if (successMessage) successMessage.style.display = 'none';
          
          if (!email) {
            e.preventDefault();
            showError('Please enter your email address');
            return;
          }
          
          if (!isValidEmail(email)) {
            e.preventDefault();
            showError('Please enter a valid email address');
            return;
          }
          
          submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
          submitBtn.disabled = true;
          setTimeout(function() {
            submitBtn.innerHTML = 'Send Reset Instructions';
            submitBtn.disabled = false;
          }, 1000);

          });
      }
      
      function showError(message) {
        if (errorMessage) {
          errorMessage.textContent = message;
          errorMessage.style.display = 'block';
        }
      }
      
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
    });