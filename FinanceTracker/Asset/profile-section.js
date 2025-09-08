

document.addEventListener('DOMContentLoaded', () => {
    // --- DOM Element References ---
    const editProfileBtn = document.querySelector('.finance-edit-btn');
    const profileViewCard = document.querySelector('.finance-profile-card:first-of-type');
    const editProfileSection = document.getElementById('editProfileSection');
    const cancelEditBtn = editProfileSection.querySelector('.finance-cancel-btn');

    // Forms
    const editProfileForm = document.getElementById('editProfileForm');
    const updatePasswordForm = document.getElementById('updatePasswordForm');
    const changeAvatarForm = document.getElementById('changeAvatarForm');

    // Display Elements
    const profileNameSpan = document.getElementById('profileName');
    const profileEmailSpan = document.getElementById('profileEmail');
    const profileAvatarImg = document.getElementById('profileAvatar');

    // Input Fields
    const editNameInput = document.getElementById('editName');
    const editEmailInput = document.getElementById('editEmail');
    const avatarUploadInput = document.getElementById('avatarUpload');
    const currentPasswordInput = document.getElementById('currentPassword');
    const newPasswordInput = document.getElementById('newPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');

   
    const userData = {
        name: '',
        email: '',
        avatar: '',
    };
    const updateProfileUI = () => {
        profileNameSpan.textContent = userData.name;
        profileEmailSpan.textContent = userData.email;
        profileAvatarImg.src = userData.avatar;
    };

    
    updateProfileUI();

    editProfileBtn.addEventListener('click', () => {
        editProfileSection.classList.remove('finance-hidden');
        profileViewCard.classList.add('finance-hidden');
      
        editNameInput.value = userData.name;
        editEmailInput.value = userData.email;
    });

    cancelEditBtn.addEventListener('click', () => {
        editProfileSection.classList.add('finance-hidden');
        profileViewCard.classList.remove('finance-hidden');
    });

    editProfileForm.addEventListener('submit', (event) => {
        event.preventDefault(); 
        
        const newName = editNameInput.value.trim();
        const newEmail = editEmailInput.value.trim();
        
        if (newName && newEmail) {
            userData.name = newName;
            userData.email = newEmail;
            
            updateProfileUI();
            
            console.log('Profile details updated successfully.');
            
            editProfileSection.classList.add('finance-hidden');
            profileViewCard.classList.remove('finance-hidden');
        } else {
            console.log('Please fill in both name and email fields.');
        }
    });

    changeAvatarForm.addEventListener('submit', (event) => {
        event.preventDefault();
        
        const file = avatarUploadInput.files[0];
        
        if (file) {
            
            const reader = new FileReader();
            reader.onload = (e) => {
                userData.avatar = e.target.result;
                updateProfileUI();
                console.log('Profile picture updated successfully.');
            };
            reader.readAsDataURL(file);
        } else {
            console.log('Please select a file to upload.');
        }
    });

    updatePasswordForm.addEventListener('submit', (event) => {
        event.preventDefault();
        
        const currentPassword = currentPasswordInput.value;
        const newPassword = newPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (newPassword.length < 6) {
            console.log('New password must be at least 6 characters.');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            console.log('New passwords do not match!');
            return;
        }
        
      
        console.log('Password updated successfully.');
        updatePasswordForm.reset(); 
    });
});
