// dashboard.js
const financeSidebarContainer = document.getElementById('sidebarContainer');
const financeMobileOverlay = document.getElementById('mobileOverlay');
const financeMenuToggleButton = document.getElementById('menuToggleButton');
const financeTopNavigationTitle = document.getElementById('topNavigationTitle');
const financeSidebarMenuLinks = document.querySelectorAll('.finance-sidebar-menu-item-link');
const financeContentSections = document.querySelectorAll('.finance-content-section');
const financeLogoutLink = document.getElementById('logoutLink');
const financeLogoutModal = document.getElementById('logoutModal');
const financeCancelLogout = document.getElementById('cancelLogout');
const financeConfirmLogout = document.getElementById('confirmLogout');

financeMenuToggleButton.addEventListener('click', function(event){
  event.stopPropagation();
  financeSidebarContainer.classList.toggle('active-sidebar');
  financeMobileOverlay.classList.toggle('active-overlay');
});

document.addEventListener('click', function(event){
  if (financeSidebarContainer.classList.contains('active-sidebar') && !financeSidebarContainer.contains(event.target) && event.target !== financeMenuToggleButton){
    financeSidebarContainer.classList.remove('active-sidebar');
    financeMobileOverlay.classList.remove('active-overlay');
  }
});

financeMobileOverlay.addEventListener('click', function(){
  financeSidebarContainer.classList.remove('active-sidebar');
  financeMobileOverlay.classList.remove('active-overlay');
});

let financeCurrentlyActiveMenuLink = document.querySelector('.finance-sidebar-menu-item-link.active-menu-link');

financeSidebarMenuLinks.forEach(function(link){
  link.addEventListener('click', function(event){
    event.preventDefault();
    
    // If this is the logout link, show confirmation modal
    if (this.id === 'logoutLink') {
      financeLogoutModal.style.display = 'flex';
      return;
    }

    // Remove previous active menu link
    if(financeCurrentlyActiveMenuLink) financeCurrentlyActiveMenuLink.classList.remove('active-menu-link');
    link.classList.add('active-menu-link');
    financeCurrentlyActiveMenuLink = link;

    // Update top navigation title
    const menuName = link.querySelector('span').textContent;
    financeTopNavigationTitle.textContent = menuName;

    // Show corresponding content section
    const targetSectionId = link.getAttribute('data-section');
    
    // Hide all sections first
    financeContentSections.forEach(function(section){
      section.classList.remove('active-content-section');
    });
    
    // Show the target section
    if(targetSectionId){
      const targetSection = document.getElementById(targetSectionId);
      if (targetSection) {
        targetSection.classList.add('active-content-section');
        
        // If it's the income section, initialize it
        if (targetSectionId === 'income-section') {
          initIncomeSection();
        }
        // If it's the expense section, initialize it
        else if (targetSectionId === 'expenses-section') {
          initExpenseSection();
        }
        // If it's the dashboard section, initialize charts
        else if (targetSectionId === 'dashboard-section') {
          initDashboardCharts();
          loadRecentTransactions();
        }
      }
    }
  });
});

// Logout confirmation functionality
financeLogoutLink.addEventListener('click', function(e) {
  e.preventDefault();
  financeLogoutModal.style.display = 'flex';
});

financeCancelLogout.addEventListener('click', function() {
  financeLogoutModal.style.display = 'none';
});

financeConfirmLogout.addEventListener('click', function() {
  window.location.href = '../Controller/logout.php';
});

// Close modal if clicked outside
window.addEventListener('click', function(event) {
  if (event.target === financeLogoutModal) {
    financeLogoutModal.style.display = 'none';
  }
});

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
  initDashboardCharts();
  loadRecentTransactions();
});