   let nameError = document.getElementById("nameError");
    let emailError = document.getElementById("emailError");
    let messageError = document.getElementById("messageError");
    let captchaError = document.getElementById("captchaError");
    let success = document.getElementById("success");

    function validateName() {
      let name = document.getElementById("name").value.trim();
      let words = name.split(" ").filter(word => word !== "");
      if (name === "") { 
        nameError.textContent = "Name cannot be empty"; 
        nameError.style.display = "block";
        return false; 
      } 
      else if (words.length < 2) { 
        nameError.textContent = "Contains at least two words"; 
        nameError.style.display = "block";
        return false; 
      } 
      else {
        let firstChar = name.charCodeAt(0);
        if (!((firstChar >= 65 && firstChar <= 90) || (firstChar >= 97 && firstChar <= 122))) {
          nameError.textContent = "Name must start with a letter"; 
          nameError.style.display = "block";
          return false;
        }
        for (let i = 0; i < name.length; i++) {
          let ch = name.charCodeAt(i);
          if (ch >= 48 && ch <= 57) { 
            nameError.textContent = "Name cannot contain numbers"; 
            nameError.style.display = "block";
            return false; 
          }
        }
      }
      nameError.style.display = "none"; 
      return true;
    }

    function validateEmail() {
      let email = document.getElementById("email").value.trim();
      if (email === "") { 
        emailError.textContent = "Email cannot be empty"; 
        emailError.style.display = "block";
        return false; 
      }
      let atPos = email.indexOf("@");
      let dotPos = email.lastIndexOf(".");
      if (!email.includes('@') || !email.endsWith('.com') || dotPos !== email.indexOf(".com")) {
        emailError.textContent = "Enter Valid Email"; 
        emailError.style.display = "block";
        return false;
      }
      let domain = email.substring(atPos + 1, dotPos);
      if (domain.length < 1) { 
        emailError.textContent = "Enter Valid Email"; 
        emailError.style.display = "block";
        return false; 
      }
      let firstCom = email.indexOf(".com");
      let lastCom = email.lastIndexOf(".com");
      if (firstCom !== lastCom) { 
        emailError.textContent = "Enter Valid Email"; 
        emailError.style.display = "block";
        return false; 
      }
      emailError.style.display = "none"; 
      return true;
    }

    function validateMessage() {
      let message = document.getElementById("message").value.trim();
      if (message === "") { 
        messageError.textContent = "Message cannot be empty"; 
        messageError.style.display = "block";
        return false; 
      } 
      else if (message.length < 10) { 
        messageError.textContent = "Message must be at least 10 characters"; 
        messageError.style.display = "block";
        return false; 
      } 
      else if (message.length > 300) { 
        messageError.textContent = "Message cannot be more than 300 characters"; 
        messageError.style.display = "block";
        return false; 
      }
      messageError.style.display = "none"; 
      return true;
    }

    let captchaAnswer = "";
    function generateCaptcha() {
      let num1 = Math.floor(Math.random() * 10 + 1);
      let num2 = Math.floor(Math.random() * 10 + 1);
      captchaAnswer = num1 + num2;
      document.getElementById("captcha-question").textContent = `${num1} + ${num2}`;
    }
    
    function validateCaptcha() {
      let userAnswer = document.getElementById("captcha").value.trim();
      if (userAnswer === "") { 
        captchaError.textContent = "Captcha cannot be empty"; 
        captchaError.style.display = "block";
        return false; 
      }
      if (parseInt(userAnswer) !== captchaAnswer) {
        captchaError.textContent = "Incorrect answer. Try again!"; 
        captchaError.style.display = "block";
        generateCaptcha(); 
        return false;
      }
      captchaError.style.display = "none"; 
      return true;
    }

    function validateForm() {
      success.style.display = "none";
      let isNameValid = validateName();
      let isEmailValid = validateEmail();
      let isMessageValid = validateMessage();
      let isCaptchaValid = validateCaptcha();
      
      if (isNameValid && isEmailValid && isMessageValid && isCaptchaValid) {
        success.textContent = "Message sent successfully!";
        success.style.display = "block";
        document.getElementById("name").value = "";
        document.getElementById("email").value = "";
        document.getElementById("message").value = "";
        document.getElementById("captcha").value = "";
        generateCaptcha();
        return false;
      }
      return false;
    }

    document.getElementById("contactForm").addEventListener("submit", function(e) {
      e.preventDefault();
      validateForm();
    });

    window.onload = function() {
      generateCaptcha();
    };