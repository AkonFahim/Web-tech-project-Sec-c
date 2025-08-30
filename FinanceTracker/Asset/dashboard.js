    const sidebarContainer = document.getElementById('sidebarContainer');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const menuToggleButton = document.getElementById('menuToggleButton');
    const topNavigationTitle = document.getElementById('topNavigationTitle');
    const sidebarMenuLinks = document.querySelectorAll('.sidebar-menu-item-link');
    const contentSections = document.querySelectorAll('.content-section');
    const logoutLink = document.getElementById('logoutLink');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');

    menuToggleButton.addEventListener('click', function(event){
      event.stopPropagation();
      sidebarContainer.classList.toggle('active-sidebar');
      mobileOverlay.classList.toggle('active-overlay');
    });

    document.addEventListener('click', function(event){
      if (sidebarContainer.classList.contains('active-sidebar') && !sidebarContainer.contains(event.target) && event.target !== menuToggleButton){
        sidebarContainer.classList.remove('active-sidebar');
        mobileOverlay.classList.remove('active-overlay');
      }
    });

    mobileOverlay.addEventListener('click', function(){
      sidebarContainer.classList.remove('active-sidebar');
      mobileOverlay.classList.remove('active-overlay');
    });

    let currentlyActiveMenuLink = document.querySelector('.sidebar-menu-item-link.active-menu-link');

    sidebarMenuLinks.forEach(function(link){
      link.addEventListener('click', function(event){
        event.preventDefault();
        
        // If this is the logout link, show confirmation modal
        if (this.id === 'logoutLink') {
          logoutModal.style.display = 'flex';
          return;
        }

        // Remove previous active menu link
        if(currentlyActiveMenuLink) currentlyActiveMenuLink.classList.remove('active-menu-link');
        link.classList.add('active-menu-link');
        currentlyActiveMenuLink = link;

        // Update top navigation title
        const menuName = link.querySelector('span').textContent;
        topNavigationTitle.textContent = menuName;

        // Show corresponding content section
        const targetContentSectionId = link.getAttribute('data-content');
        contentSections.forEach(function(section){
          section.classList.remove('active-content-section');
        });
        if(targetContentSectionId){
          document.getElementById(targetContentSectionId).classList.add('active-content-section');
        }
      });
    });

    // Logout confirmation functionality
    logoutLink.addEventListener('click', function(e) {
      e.preventDefault();
      logoutModal.style.display = 'flex';
    });

    cancelLogout.addEventListener('click', function() {
      logoutModal.style.display = 'none';
    });

    confirmLogout.addEventListener('click', function() {
   window.location.href = '../Controller/logout.php';
});

    // Close modal if clicked outside
    window.addEventListener('click', function(event) {
      if (event.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });
