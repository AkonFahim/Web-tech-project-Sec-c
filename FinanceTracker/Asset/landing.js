  
      const featureBoxes = document.querySelectorAll('.feature-box');
      const landingLoginBtn = document.getElementById("landing-login-btn");
      const landingRegisterBtn = document.getElementById("landing-register-btn");
      
      function checkVisibility() {
        featureBoxes.forEach(box => {
          const position = box.getBoundingClientRect();
          if(position.top < window.innerHeight - 100) {
            box.classList.add('visible');
          }
        });
      }

      landingRegisterBtn.addEventListener('click',function(){
         window.location.href = "register.html";
      });
      
      landingLoginBtn.addEventListener('click', () => {
        landingRegisterBtn.classList.remove('active');
        landingLoginBtn.classList.add('active');
      });


      checkVisibility();
     window.addEventListener('scroll', checkVisibility);