<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    if (isset($_COOKIE['remember_me']) && isset($_COOKIE['user_id'])) {
        include '../Model/db_connection.php';
        $user_id = $_COOKIE['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$user_id' AND status = 'active'";
        $result = mysqli_query($con, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            
            mysqli_close($con);
        } else {
            mysqli_close($con);
            header('location: login.php?error=badrequest');
            exit();
        }
    } else {
        header('location: login.php?error=badrequest');
        exit();
    }
}

include '../Model/db_connection.php';

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_close($con);
    session_unset();
    session_destroy();
    
    setcookie('remember_me', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
    
    header('location: login.php?error=invalid_user');
    exit();
}

$user = mysqli_fetch_assoc($result);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Finance Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="stylesheet" href="../Asset/dashboard.css">
  <link rel="stylesheet" href="../Asset/dashboard-section.css">
  <link rel="stylesheet" href="../Asset/profile-section.css">
  <link rel="stylesheet" href="../Asset/income-section.css">
  <link rel="stylesheet" href="../Asset/expense-section.css">
  <link rel="stylesheet" href="../Asset/budget-section.css">
  <link rel="stylesheet" href="../Asset/billreminder-section.css">
  <link rel="stylesheet" href="../Asset/report-section.css">
  <link rel="stylesheet" href="../Asset/savings-section.css">
  <link rel="stylesheet" href="../Asset/debt-section.css">
  <link rel="stylesheet" href="../Asset/tax-section.css">
  <link rel="stylesheet" href="../Asset/exports-section.css?v=1.0.0">
  <link rel="stylesheet" href="../Asset/settings-section.css">
</head>

<body class="finance-body-default-style">
  <div class="finance-overlay-background" id="mobileOverlay"></div>

  <div class="finance-logout-modal" id="logoutModal">
    <div class="finance-logout-modal-content">
      <h5>Confirm Logout</h5>
      <p>Are you sure you want to logout?</p>
      <div class="finance-logout-modal-buttons">
        <button type="button" class="finance-cancel-btn" id="cancelLogout">Cancel</button>
        <button type="button" class="finance-confirmlogout-btn" id="confirmLogout">Logout</button>
      </div>
    </div>
  </div>




  <!-- Sidebar -->
  <div class="finance-sidebar-container" id="sidebarContainer">
    <div class="finance-sidebar-header-container">
      <h3 class="finance-sidebar-header-title">
        <i class="fas fa-wallet"></i> 
        <span>Finance Tracker</span>
      </h3>
    </div>

    <div class="finance-sidebar-menu-container">
  <a href="#" class="finance-sidebar-menu-item-link active-menu-link" data-section="dashboard-section">
    <i class="fas fa-home finance-sidebar-menu-item-icon"></i> <span>Dashboard</span>
  </a>
   <a href="#" class="finance-sidebar-menu-item-link " data-section="profile-section">
    <i class="fas fa-user finance-sidebar-menu-item-icon"></i> <span>Profile</span>
  </a>
  
  <a href="#" class="finance-sidebar-menu-item-link" data-section="income-section">
    <i class="fas fa-money-bill-wave finance-sidebar-menu-item-icon"></i> <span>Income</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="expenses-section">
    <i class="fas fa-credit-card finance-sidebar-menu-item-icon"></i> <span>Expenses</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="budget-section">
    <i class="fas fa-chart-pie finance-sidebar-menu-item-icon"></i> <span>Budget</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="bill-reminders-section">
    <i class="fas fa-calendar-alt finance-sidebar-menu-item-icon"></i> <span>Bill Reminders</span>
  </a>
  
  <a href="#" class="finance-sidebar-menu-item-link" data-section="savings-goals-section">
    <i class="fas fa-piggy-bank finance-sidebar-menu-item-icon"></i> <span>Savings Goals</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="debt-tracking-section">
    <i class="fas fa-landmark finance-sidebar-menu-item-icon"></i> <span>Debt Tracking</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="tax-categories-section">
    <i class="fas fa-file-invoice-dollar finance-sidebar-menu-item-icon"></i> <span>Tax Categories</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="export-data-section">
    <i class="fas fa-download finance-sidebar-menu-item-icon"></i> <span>Export Data</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" data-section="settings-section">
    <i class="fas fa-cog finance-sidebar-menu-item-icon"></i> <span>Settings</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link " data-section="dashboard-section">
    <i class="fas fa-user finance-sidebar-menu-item-icon"></i> <span>Profile</span>
  </a>
  <a href="#" class="finance-sidebar-menu-item-link" id="logoutLink">
    <i class="fas fa-sign-out-alt finance-sidebar-menu-item-icon"></i> <span>Logout</span>
  </a>
</div>
  </div>

  <!-- Main Content -->
  <div class="finance-main-content-container">
    <div class="finance-top-navigation-bar">
      <div class="finance-nav-left">
        <div class="finance-menu-toggle-button" id="menuToggleButton">
          <i class="fas fa-bars"></i>
        </div>
        <h5 class="finance-top-navigation-heading" id="topNavigationTitle">Dashboard</h5>
      </div>

      <div class="finance-nav-right">
        <div class="finance-notification-badge-container">
          <i class="fas fa-bell"></i>
          <span class="finance-notification-badge-dot">3</span>
        </div>
        <div class="finance-user-profile-container">
          <span class="finance-user-welcome-text">
           Welcome, <?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'User'; ?></br> 
           Email: <?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'User'; ?>
          </span>
        </div>
      </div>
    </div>

    <div class="finance-page-content-container">
   <!-- Dashboard Section final-->
 <div id="dashboard-section" class="finance-content-section active-content-section">
    <div class="finance-dashboard-header">
        <h2>Dashboard</h2>
        <p>Overview of your financial health</p>
    </div>

    <div class="finance-summary-cards-container">
        <div class="finance-summary-card">
            <div class="finance-card-icon finance-savings-icon"><i class="fas fa-piggy-bank"></i></div>
            <div class="finance-card-content">
                <div class="finance-card-value" id="balanceDisplay">$0.00</div>
                <div class="finance-card-label">Balance</div>
            </div>
        </div>

        <div class="finance-summary-card">
            <div class="finance-card-icon finance-income-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="finance-card-content">
                <div class="finance-card-value" id="totalIncomeDisplay">$0.00</div>
                <div class="finance-card-label">Total Income</div>
            </div>
        </div>

        <div class="finance-summary-card">
            <div class="finance-card-icon finance-expenses-icon"><i class="fas fa-credit-card"></i></div>
            <div class="finance-card-content">
                <div class="finance-card-value" id="totalExpensesDisplay">$0.00</div>
                <div class="finance-card-label">Total Expenses</div>
            </div>
        </div>
    </div>

    <div class="finance-charts-container">
        <div class="finance-chart-card">
            <div class="finance-chart-header">
                <h3>Income vs Expenses</h3>
                <select class="finance-chart-period-selector" id="incomeExpensePeriod">
                    <option>Last 7 Days</option>
                    <option selected>Last 30 Days</option>
                    <option>Last 90 Days</option>
                </select>
            </div>
            <canvas id="incomeExpenseChart"></canvas>
        </div>
    </div>
    
    <div class="finance-recent-transactions-container">
        <div class="finance-section-header">
            <h3>Recent Transactions</h3>
            <button class="finance-view-all-button">View All</button>
        </div>
        <div class="finance-recent-transactions finance-card">
            <div class="finance-recent-transactions-table-container">
                <table class="finance-recent-transactions-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody id="recentTransactionsTableBody">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
 </div>
   
  <!-- profile Section -->
<div id="profile-section" class="finance-content-section">

    <div class="finance-profile-card">
        <h4 class="finance-card-header">Your Profile</h4>
        <div class="finance-profile-details">
            <div class="finance-profile-avatar">
                <img src="" alt="User Avatar" id="profileAvatar">
            </div>
            <p><strong>Name:</strong> <span id="profileName"><?php echo isset($user['username']) ? htmlspecialchars($user['username']) : 'User'; ?></span></p>
            <p><strong>Email:</strong> <span id="profileEmail"><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : 'User'; ?></span></p>
            
        </div>
        <button class="finance-edit-btn">Edit Profile</button>
    </div>

    <div class="finance-profile-card finance-hidden" id="editProfileSection">
        <h4 class="finance-card-header">Edit Personal Details</h4>
        <form id="editProfileForm">
            <div class="finance-form-group">
                <label for="editName">Full Name</label>
                <input type="text" id="editName" class="finance-form-input">
            </div>
            <div class="finance-form-group">
                <label for="editEmail">Email Address</label>
                <input type="email" id="editEmail" class="finance-form-input">
            </div>
            <button type="submit" class="finance-addexpense-btn">Save Changes</button>
            <button type="button" class="finance-cancel-btn">Cancel</button>
        </form>
    </div>

    <div class="finance-profile-card">
        <h4 class="finance-card-header">Change Profile Picture</h4>
        <form id="changeAvatarForm">
            <div class="finance-form-group">
                <input type="file" id="avatarUpload" accept="image/*" class="finance-form-input">
            </div>
            <button type="submit" class="finance-addexpense-btn">Upload New Picture</button>
        </form>
    </div>

    <div class="finance-profile-card">
        <h4 class="finance-card-header">Update Password</h4>
        <form id="updatePasswordForm">
            <div class="finance-form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" class="finance-form-input">
            </div>
            <div class="finance-form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" class="finance-form-input">
            </div>
            <div class="finance-form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" id="confirmPassword" class="finance-form-input">
            </div>
            <button type="submit" class="finance-addexpense-btn">Change Password</button>
        </form>
    </div>
</div>




    
   <!-- income section -->
       <div id="income-section" class="finance-content-section">
        <div class="finance-income-section-container">
          <h4 class="finance-income-section-header">Income Management</h4>
          <div class="finance-income-form-container">
            <div class="finance-income-form-column">
              <div class="finance-income-form-card">
                <div class="finance-income-form-card-header">
                  <h5>Add Income</h5>
                </div>
                <div class="finance-income-form-card-body">
                  <form id="incomeForm">
                    <div class="finance-income-form-group">
                      <label for="incomeAmount" class="finance-income-form-label">Amount</label>
                      <div class="finance-income-input-group">
                        <span class="finance-income-input-prefix">$</span>
                        <input type="number" step="0.01" class="finance-income-form-input" id="incomeAmount" placeholder="0.00" required>
                      </div>
                    </div>
                    <div class="finance-income-form-group">
                      <label for="incomeDescription" class="finance-income-form-label">Description</label>
                      <input type="text" class="finance-income-form-input" id="incomeDescription" placeholder="Salary, Freelance, etc." required>
                    </div>
                    <div class="finance-income-form-group">
                      <label for="incomeCategory" class="finance-income-form-label">Category</label>
                      <select class="finance-income-form-input" id="incomeCategory" required>
                        <option value="">Select Category</option>
                        <option value="Salary">Salary</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Investment">Investment</option>
                        <option value="Gift">Gift</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="finance-income-form-group">
                      <label for="incomeDate" class="finance-income-form-label">Date</label>
                      <input type="date" class="finance-income-form-input" id="incomeDate" required>
                    </div>
                    <button type="button" class="finance-addincome-btn" id="saveIncome">Add Income</button>
                  </form>
                  <div id="incomeMessage" class="finance-income-message"></div>
                </div>
              </div>
            </div>

            <div class="finance-income-form-column">
              <div class="finance-income-form-card">
                <div class="finance-income-form-card-header">
                  <h5>Income History</h5>
                </div>
                <div class="finance-income-form-card-body">
                  <div class="finance-income-history-table-container">
                    <table class="finance-income-history-table">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Category</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody id="incomeHistory">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    <!-- Expenses Section -->
      <div id="expenses-section" class="finance-content-section">
        <div class="finance-expense-section-container">
          <h4 class="finance-expense-section-header">Expense Management</h4>
          <div class="finance-expense-form-container">
            <div class="finance-expense-form-column">
              <div class="finance-expense-form-card">
                <div class="finance-expense-form-card-header">
                  <h5>Add Expense</h5>
                </div>
                <div class="finance-expense-form-card-body">
                  <form id="expenseForm">
                    <div class="finance-expense-form-group">
                      <label for="expenseAmount" class="finance-expense-form-label">Amount</label>
                      <div class="finance-expense-input-group">
                        <span class="finance-expense-input-prefix">$</span>
                        <input type="number" step="0.01" class="finance-expense-form-input" id="expenseAmount" placeholder="0.00" required>
                      </div>
                    </div>
                    <div class="finance-expense-form-group">
                      <label for="expenseDescription" class="finance-expense-form-label">Description</label>
                      <input type="text" class="finance-expense-form-input" id="expenseDescription" placeholder="Groceries, Rent, etc." required>
                    </div>
                    <div class="finance-expense-form-group">
                      <label for="expenseCategory" class="finance-expense-form-label">Category</label>
                      <select class="finance-expense-form-input" id="expenseCategory" required>
                        <option value="">Select Category</option>
                        <option value="Food">Food & Dining</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Housing">Housing</option>
                        <option value="Utilities">Utilities</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                    <div class="finance-expense-form-group">
                      <label for="expenseDate" class="finance-expense-form-label">Date</label>
                      <input type="date" class="finance-expense-form-input" id="expenseDate" required>
                    </div>
                    <button type="button" class="finance-addexpense-btn" id="saveExpense">Add Expense</button>
                  </form>
                  <div id="expenseMessage" class="finance-expense-message"></div>
                </div>
              </div>
            </div>

            <div class="finance-expense-form-column">
              <div class="finance-expense-form-card">
                <div class="finance-expense-form-card-header">
                  <h5>Expense History</h5>
                </div>
                <div class="finance-expense-form-card-body">
                  <div class="finance-expense-history-table-container">
                    <table class="finance-expense-history-table">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Description</th>
                          <th>Category</th>
                          <th>Amount</th>
                        </tr>
                      </thead>
                      <tbody id="expenseHistory">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

 <!-- Budget Section -->
     <div id="budget-section" class="finance-content-section" style="padding: 2rem; background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    <div class="finance-budget-section-container">
        <h4 class="finance-budget-section-header" style="color: #343a40; margin-bottom: 2rem; font-weight: 700;">Budget Management</h4>
        
        <div class="finance-budget-cards-container" style="display: flex; flex-wrap: wrap; gap: 2rem;">
            <div class="finance-budget-card" style="flex: 1; min-width: 300px; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <div class="finance-budget-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h5 style="font-weight: 600; color: #007bff; margin: 0;">Monthly Budget</h5>
                    <span class="finance-budget-card-amount" id="totalBudgetAmount" style="font-size: 1.5rem; font-weight: 700; color: #28a745;">$0.00</span>
                </div>
                <div class="finance-budget-progress" style="margin-bottom: 0.5rem;">
                    <div class="finance-budget-progress-bar" style="background-color: #e9ecef; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div class="finance-budget-progress-fill" style="width: 0%; height: 100%; background-color: #007bff; transition: width 0.3s ease-in-out;"></div>
                    </div>
                    <div class="finance-budget-progress-text" id="budgetProgressText" style="text-align: center; margin-top: 0.5rem; font-size: 0.8rem; color: #6c757d;">0% used</div>
                </div>
                <div class="finance-budget-card-footer" style="text-align: right;">
                    <span class="finance-budget-remaining" id="budgetRemaining" style="font-size: 0.9rem; font-weight: 600; color: #dc3545;">$0.00 remaining</span>
                </div>
            </div>

            <div class="finance-budget-card" style="flex: 1; min-width: 300px; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                <div class="finance-budget-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <h5 style="font-weight: 600; color: #007bff; margin: 0;">Set New Budget</h5>
                </div>
                <div class="finance-budget-form">
                    <div class="finance-budget-form-group" style="margin-bottom: 1rem;">
                        <label class="finance-budget-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Budget Amount</label>
                        <div class="finance-budget-input-group" style="display: flex; align-items: center;">
                            <span class="finance-budget-input-prefix" style="background-color: #e9ecef; padding: 0.75rem 1rem; border: 1px solid #ced4da; border-right: none; border-radius: 8px 0 0 8px;">$</span>
                            <input type="number" step="0.01" class="finance-budget-form-input" id="budgetAmount" placeholder="0.00" required style="flex-grow: 1; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0 8px 8px 0; font-size: 1rem;">
                        </div>
                    </div>
                    <div class="finance-budget-form-group" style="margin-bottom: 1.5rem;">
                        <label class="finance-budget-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Category</label>
                        <select class="finance-budget-form-input" id="budgetCategory" required style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
                            <option value="">Select Category</option>
                            <option value="Food & Dining">Food & Dining</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Housing">Housing</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                        </select>
                    </div>
                    <button class="finance-setbudget-btn" id="setBudgetBtn" type="button" style="width: 100%; padding: 0.75rem; background-color: #28a745; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Set Budget</button>
                </div>
            </div>
        </div>

        <div class="finance-budget-categories-container" style="background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); margin-top: 2rem;">
            <h5 style="font-weight: 600; color: #007bff; margin-bottom: 1.5rem;">Budget by Category</h5>
            <div class="finance-budget-categories-list" id="budgetCategoriesList">
                </div>
        </div>
    </div>
</div>




    <!-- Bill Reminders Section -->
     <div id="bill-reminders-section" class="finance-content-section" style="padding: 2rem; background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
    <div class="finance-billreminder-section-container">
        <h4 class="finance-billreminder-section-header" style="color: #343a40; margin-bottom: 2rem; font-weight: 700;">Bill Reminders</h4>
        <div class="finance-billreminder-content">
            <div class="finance-billreminder-cards-container" style="display: flex; flex-wrap: wrap; gap: 2rem;">
                <div class="finance-billreminder-card" style="flex: 1; min-width: 300px; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                    <div class="finance-billreminder-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">
                        <h5 style="font-weight: 600; color: #007bff;">Upcoming Bills</h5>
                    </div>
                    <div class="finance-billreminder-list">
                        </div>
                </div>

                <div class="finance-billreminder-card" style="flex: 1; min-width: 300px; background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);">
                    <div class="finance-billreminder-card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">
                        <h5 style="font-weight: 600; color: #007bff;">Add New Bill</h5>
                    </div>
                    <div class="finance-billreminder-form">
                        <div class="finance-billreminder-form-group" style="margin-bottom: 1rem;">
                            <label class="finance-billreminder-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Bill Name</label>
                            <input type="text" id="billName" class="finance-billreminder-form-input" placeholder="e.g., Internet Bill" required style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div class="finance-billreminder-form-group" style="margin-bottom: 1rem;">
                            <label class="finance-billreminder-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Amount</label>
                            <div class="finance-billreminder-input-group" style="display: flex; align-items: center;">
                                <span class="finance-billreminder-input-prefix" style="background-color: #e9ecef; padding: 0.75rem 1rem; border: 1px solid #ced4da; border-right: none; border-radius: 8px 0 0 8px;">$</span>
                                <input type="number" step="0.01" id="billAmount" class="finance-billreminder-form-input" placeholder="0.00" required style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 0 8px 8px 0; font-size: 1rem;">
                            </div>
                        </div>
                        <div class="finance-billreminder-form-group" style="margin-bottom: 1rem;">
                            <label class="finance-billreminder-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Due Date</label>
                            <input type="date" id="billDate" class="finance-billreminder-form-input" required style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
                        </div>
                        <div class="finance-billreminder-form-group" style="margin-bottom: 1.5rem;">
                            <label class="finance-billreminder-form-label" style="font-weight: 500; color: #495057; display: block; margin-bottom: 0.5rem;">Recurrence</label>
                            <select id="billRecurrence" class="finance-billreminder-form-input" required style="width: 100%; padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
                                <option value="once">One Time</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                        <button id="saveBillBtn" class="finance-savebill-btn" style="width: 100%; padding: 0.75rem; background-color: #28a745; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Save Bill</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // In-memory array to store bills
    let bills = [];

    // Function to save bills to localStorage
    const saveBills = () => {
        localStorage.setItem('bills', JSON.stringify(bills));
    };

    // Function to load bills from localStorage
    const loadBills = () => {
        const storedBills = localStorage.getItem('bills');
        if (storedBills) {
            bills = JSON.parse(storedBills);
        }
    };

    // Function to render the bills on the page
    const renderBills = () => {
        const billList = document.querySelector('.finance-billreminder-list');
        billList.innerHTML = '';

        if (bills.length === 0) {
            billList.innerHTML = '<p style="text-align: center; color: #6c757d; margin-top: 1rem;">No upcoming bills.</p>';
            return;
        }

        bills.forEach(bill => {
            const billItem = document.createElement('div');
            billItem.classList.add('bill-item');
            billItem.style.cssText = "display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #e9ecef;";
            billItem.innerHTML = `
                <div>
                    <strong style="color: #495057;">${bill.name}</strong>
                    <span style="display: block; font-size: 0.8rem; color: #6c757d;">Due: ${bill.date}</span>
                </div>
                <div style="text-align: right;">
                    <span style="font-weight: 600; color: #dc3545;">$${bill.amount.toFixed(2)}</span>
                    <button class="delete-bill-btn" data-id="${bill.id}" style="background: none; border: none; color: #dc3545; cursor: pointer; margin-left: 0.5rem;"><i class="fas fa-trash"></i></button>
                </div>
            `;
            billList.appendChild(billItem);
        });

        // Add event listeners for delete buttons
        document.querySelectorAll('.delete-bill-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const billId = parseInt(e.currentTarget.dataset.id);
                deleteBill(billId);
            });
        });
    };

    // Function to handle saving a new bill
    const handleSaveBill = () => {
        const name = document.getElementById('billName').value;
        const amount = parseFloat(document.getElementById('billAmount').value);
        const date = document.getElementById('billDate').value;
        const recurrence = document.getElementById('billRecurrence').value;

        if (name && amount && date) {
            const newBill = {
                id: Date.now(),
                name: name,
                amount: amount,
                date: date,
                recurrence: recurrence,
            };
            bills.push(newBill);
            saveBills();
            renderBills();
            document.querySelector('.finance-billreminder-form').reset();
        } else {
            alert('Please fill out all fields.');
        }
    };

    // Function to delete a bill
    const deleteBill = (id) => {
        bills = bills.filter(bill => bill.id !== id);
        saveBills();
        renderBills();
    };

    // Initialize the bill section on page load
    document.addEventListener('DOMContentLoaded', () => {
        loadBills();
        renderBills();

        const saveBillButton = document.getElementById('saveBillBtn');
        if (saveBillButton) {
            saveBillButton.addEventListener('click', handleSaveBill);
        }
    });
</script>
</div>


     


        <!-- Savings Goals Section -->
        
 <div id="savings-goals-section" class="finance-content-section">
  <div class="finance-savings-section-container">
    <h4 class="finance-savings-section-header">Savings Goals</h4>
    <div class="finance-savings-content">
      <div class="finance-savings-cards-container">
        <!-- Create New Goal Card -->
        <div class="finance-savings-card">
          <div class="finance-savings-card-header">
            <h5>Create New Goal</h5>
          </div>
          <div class="finance-savings-form">
            <div class="finance-savings-form-group">
              <label class="finance-savings-form-label">Goal Name</label>
              <input type="text" class="finance-savings-form-input" id="goal-name-input" placeholder="e.g., New Laptop">
            </div>
            <div class="finance-savings-form-group">
              <label class="finance-savings-form-label">Target Amount</label>
              <div class="finance-savings-input-group">
                <span class="finance-savings-input-prefix">$</span>
                <input type="number" step="0.01" class="finance-savings-form-input" id="target-amount-input" placeholder="0.00">
              </div>
            </div>
            <div class="finance-savings-form-group">
              <label class="finance-savings-form-label">Target Date</label>
              <input type="date" class="finance-savings-form-input" id="target-date-input">
            </div>
            <div class="finance-savings-form-group">
              <label class="finance-savings-form-label">Initial Amount</label>
              <div class="finance-savings-input-group">
                <span class="finance-savings-input-prefix">$</span>
                <input type="number" step="0.01" class="finance-savings-form-input" id="initial-amount-input" placeholder="0.00">
              </div>
            </div>
            <button class="finance-creategoal-btn" id="create-goal-btn">Create Goal</button>
          </div>
        </div>

        <!-- Savings Summary Card -->
        <div class="finance-savings-card">
          <div class="finance-savings-card-header">
            <h5>Savings Summary</h5>
          </div>
          <div class="finance-savings-summary">
            <div class="finance-savings-summary-item">
              <span class="finance-savings-summary-label">Total Goals</span>
              <span class="finance-savings-summary-value" id="total-goals-value">0</span>
            </div>
            <div class="finance-savings-summary-item">
              <span class="finance-savings-summary-label">Total Saved</span>
              <span class="finance-savings-summary-value" id="total-saved-value">$0.00</span>
            </div>
            <div class="finance-savings-summary-item">
              <span class="finance-savings-summary-label">Total Target</span>
              <span class="finance-savings-summary-value" id="total-target-value">$0.00</span>
            </div>
            <div class="finance-savings-summary-item">
              <span class="finance-savings-summary-label">Overall Progress</span>
              <span class="finance-savings-summary-value" id="overall-progress-value">0%</span>
            </div>
          </div>
          <div class="finance-savings-overall-progress">
            <div class="finance-savings-progress-bar">
              <div class="finance-savings-progress-fill" id="overall-progress-fill" style="width: 0%"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Your Savings Goals List -->
      <div class="finance-savings-goals-container" id="goals-list-container">
        <h5>Your Savings Goals</h5>
        <div class="finance-savings-goals-list" id="savings-goals-list">
        </div>
      </div>
    </div>
  </div>
</div>




      <!-- Debt Tracking Section -->
       <div id="debt-tracking-section" class="finance-content-section">
    <div class="finance-debt-section-container">
        <h4 class="finance-debt-section-header">Debt Tracking</h4>
        <div class="finance-debt-content">
            <div class="finance-debt-cards-container">
                <div class="finance-debt-card">
                    <div class="finance-debt-card-header">
                        <h5>Add New Debt</h5>
                    </div>
                    <div class="finance-debt-form">
                        <div class="finance-debt-form-group">
                            <label class="finance-debt-form-label">Debt Name</label>
                            <input type="text" class="finance-debt-form-input" placeholder="e.g., Credit Card">
                        </div>
                        <div class="finance-debt-form-group">
                            <label class="finance-debt-form-label">Initial Amount</label>
                            <div class="finance-debt-input-group">
                                <span class="finance-debt-input-prefix">$</span>
                                <input type="number" step="0.01" class="finance-debt-form-input" placeholder="0.00">
                            </div>
                        </div>
                        <div class="finance-debt-form-group">
                            <label class="finance-debt-form-label">Current Balance</label>
                            <div class="finance-debt-input-group">
                                <span class="finance-debt-input-prefix">$</span>
                                <input type="number" step="0.01" class="finance-debt-form-input" placeholder="0.00">
                            </div>
                        </div>
                        <div class="finance-debt-form-group">
                            <label class="finance-debt-form-label">Interest Rate</label>
                            <div class="finance-debt-input-group">
                                <input type="number" step="0.01" class="finance-debt-form-input" placeholder="0.00">
                                <span class="finance-debt-input-suffix">%</span>
                            </div>
                        </div>
                        <div class="finance-debt-form-group">
                            <label class="finance-debt-form-label">Minimum Payment</label>
                            <div class="finance-debt-input-group">
                                <span class="finance-debt-input-prefix">$</span>
                                <input type="number" step="0.01" class="finance-debt-form-input" placeholder="0.00">
                            </div>
                        </div>
                        <button class="finance-adddebt-btn">Add Debt</button>
                    </div>
                </div>

                <div class="finance-debt-card">
                    <div class="finance-debt-card-header">
                        <h5>Debt Summary</h5>
                    </div>
                    <div class="finance-debt-summary">
                        </div>
                    <div class="finance-debt-payoff-chart">
                        <canvas id="debtPayoffChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="finance-debt-list-container">
                <h5>Your Debts</h5>
                <div class="finance-debt-list">
                    </div>
            </div>
        </div>
    </div>
</div>

     <!-- Tax Categories Section -->
<div id="tax-categories-section" class="finance-content-section" style="padding: 2rem; background-color: #f8f9fa; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
  <div class="finance-tax-section-container">
    <h4 class="finance-tax-section-header" style="color: #343a40; margin-bottom: 2rem; font-weight: 700;">Tax Categories</h4>
    <div class="finance-tax-content">
      <div class="finance-tax-deduction-manager-container" style="background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); margin-bottom: 2rem;">
        <h5 style="font-weight: 600; color: #007bff; margin-bottom: 1.5rem; border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem;">Deductible Expenses Manager</h5>
        <form id="taxExpenseForm" class="finance-tax-form" style="display: flex; flex-wrap: wrap; gap: 1.5rem; margin-bottom: 2rem; align-items: flex-end;">
          <div class="finance-tax-form-group" style="display: flex; flex-direction: column; flex: 1; min-width: 200px;">
            <label for="taxExpenseDescription" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">Description</label>
            <input type="text" id="taxExpenseDescription" placeholder="e.g., Office Supplies" required style="padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
          </div>
          <div class="finance-tax-form-group" style="display: flex; flex-direction: column; flex: 1; min-width: 200px;">
            <label for="taxExpenseAmount" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">Amount</label>
            <input type="number" id="taxExpenseAmount" placeholder="0.00" step="0.01" required style="padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
          </div>
          <div class="finance-tax-form-group" style="display: flex; flex-direction: column; flex: 1; min-width: 200px;">
            <label for="taxExpenseDate" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">Date</label>
            <input type="date" id="taxExpenseDate" required style="padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
          </div>
          <div class="finance-tax-form-group" style="display: flex; flex-direction: column; flex: 1; min-width: 200px;">
            <label for="taxExpenseCategory" style="font-weight: 500; color: #495057; margin-bottom: 0.5rem;">Category</label>
            <select id="taxExpenseCategory" required style="padding: 0.75rem; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem;">
              <option value="">Select Category</option>
              <option value="Home Office">Home Office</option>
              <option value="Business Travel">Business Travel</option>
              <option value="Charitable Donation">Charitable Donation</option>
              <option value="Supplies">Supplies</option>
            </select>
          </div>
          <button type="submit" class="finance-add-tax-expense-btn" style="padding: 0.75rem 1.5rem; background-color: #28a745; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Add Expense</button>
        </form>

        <div class="finance-tax-deductions-list">
          <h6 style="font-weight: 600; color: #495057; margin-bottom: 1.5rem;">Deduction History</h6>
          <table class="finance-tax-table" style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
            <thead>
              <tr style="background-color: #e9ecef; font-weight: 600; color: #495057;">
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Date</th>
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Description</th>
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Category</th>
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Amount</th>
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Tax Savings</th>
                <th style="padding: 1rem; border-bottom: 1px solid #dee2e6;">Actions</th>
              </tr>
            </thead>
            <tbody id="taxDeductionHistory">
              </tbody>
          </table>
        </div>
      </div>

      <div class="finance-tax-export-container" style="background-color: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); margin-top: 2rem;">
        <h5 style="font-weight: 600; color: #007bff; margin-bottom: 1.5rem;">Export Tax Data</h5>
        <div class="finance-tax-export-options" style="display: flex; gap: 1rem; flex-wrap: wrap;">
          <button class="finance-exportcsv-btn" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 1px solid #20c997; cursor: pointer; font-weight: 600; background-color: #20c997; color: #fff;">Export to CSV</button>
          <button class="finance-exportpdf-btn" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 1px solid #dc3545; cursor: pointer; font-weight: 600; background-color: #dc3545; color: #fff;">Export to PDF</button>
          <button class="finance-exportcpa-btn" style="padding: 0.75rem 1.5rem; border-radius: 8px; border: 1px solid #ffc107; cursor: pointer; font-weight: 600; background-color: #ffc107; color: #212529;">CPA Report</button>
        </div>
        <div style="margin-top: 1.5rem; font-size: 1.2rem; font-weight: 700; color: #28a745;">
          Total Tax : <span id="totalTaxSavings">$0.00</span>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // ------------------- In-Memory Tax Deductions -------------------
  let taxDeductions = [];

  // Define tax rates for each category (as a decimal percentage)
  const taxRates = {
    'Home Office': 0.35,
    'Business Travel': 0.25,
    'Charitable Donation': 0.15,
    'Supplies': 0.20,
    'Other': 0.18
  };

  // --- Data Persistence Functions ---
  const saveDeductions = () => {
    localStorage.setItem('taxDeductions', JSON.stringify(taxDeductions));
  };

  const loadDeductions = () => {
    const savedDeductions = localStorage.getItem('taxDeductions');
    if (savedDeductions) {
      taxDeductions = JSON.parse(savedDeductions);
    }
  };

  // --- Rendering Functions ---
  const renderDeductionHistory = () => {
    const taxDeductionHistory = document.getElementById('taxDeductionHistory');
    const totalTaxSavingsElement = document.getElementById('totalTaxSavings');
    taxDeductionHistory.innerHTML = ''; // Clear the table first
    let totalSavings = 0;

    if (taxDeductions.length === 0) {
      const row = document.createElement('tr');
      row.innerHTML = `<td colspan="6" class="text-center">No deductible expenses recorded.</td>`;
      taxDeductionHistory.appendChild(row);
      totalTaxSavingsElement.textContent = '$0.00';
      return;
    }

    taxDeductions.forEach((deduction) => {
      const taxRate = taxRates[deduction.category] || taxRates['Other']; // Default to 'Other' if category not found
      const savings = deduction.amount * taxRate;
      totalSavings += savings;

      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${deduction.date}</td>
        <td>${deduction.description}</td>
        <td>${deduction.category}</td>
        <td>$${deduction.amount.toFixed(2)}</td>
        <td>$${savings.toFixed(2)}</td>
        <td><button class="finance-delete-btn" data-id="${deduction.id}">Delete</button></td>
      `;
      taxDeductionHistory.appendChild(row);
    });

    totalTaxSavingsElement.textContent = `$${totalSavings.toFixed(2)}`;

    // Add event listeners to newly created delete buttons
    document.querySelectorAll('.finance-delete-btn').forEach(button => {
      button.addEventListener('click', (e) => {
        const deductionId = e.target.getAttribute('data-id');
        deleteTaxDeduction(deductionId);
      });
    });
  };

  // --- Deletion Function ---
  const deleteTaxDeduction = (id) => {
    taxDeductions = taxDeductions.filter(deduction => deduction.id != id);
    saveDeductions();
    renderDeductionHistory();
  };

  // --- Initialization and Event Handling ---
  document.addEventListener('DOMContentLoaded', () => {
    loadDeductions();
    renderDeductionHistory();

    const taxExpenseForm = document.getElementById('taxExpenseForm');
    taxExpenseForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const newDeduction = {
        id: Date.now(), // Unique ID for each deduction
        description: document.getElementById('taxExpenseDescription').value,
        amount: parseFloat(document.getElementById('taxExpenseAmount').value),
        date: document.getElementById('taxExpenseDate').value,
        category: document.getElementById('taxExpenseCategory').value,
      };

      taxDeductions.push(newDeduction);
      taxExpenseForm.reset();
      saveDeductions();
      renderDeductionHistory();
    });
  });
</script>


      <!-- Export Data Section -->
<div id="export-data-section" class="finance-content-section">
  <div class="finance-export-section-container">
    <h4 class="finance-export-section-header"></h4>
            <!-- Export Data section code here -->
              <div class="container">
        <div class="section-card">
            <h4>Export Data</h4>
            <div class="card-grid">
                <div class="card-item">
                    <h5>Export Options</h5>
                    <div class="option-item" data-format="CSV">
                        <div class="option-icon"><i class="fas fa-file-csv"></i></div>
                        <div class="option-info">
                            <h6>CSV Export</h6>
                            <p>Comma-separated values for spreadsheets</p>
                        </div>
                        <button class="export-btn">Export</button>
                    </div>
                    <div class="option-item" data-format="PDF">
                        <div class="option-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="option-info">
                            <h6>PDF Report</h6>
                            <p>Formatted PDF document for printing</p>
                        </div>
                        <button class="export-btn">Export</button>
                    </div>
                    <div class="option-item" data-format="QuickBooks">
                        <div class="option-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="option-info">
                            <h6>QuickBooks Format</h6>
                            <p>QBO file for accounting software</p>
                        </div>
                        <button class="export-btn">Export</button>
                    </div>
                </div>
                <div class="card-item">
                    <h5>Export Settings</h5>
                    <div class="setting-group">
                        <label class="setting-label">Date Range</label>
                        <select class="setting-input" id="date-range-select">
                            <option value="">Select a date range</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                            <option>This Year</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <div class="setting-group">
                        <label class="setting-label">Data Type</label>
                        <select class="setting-input" id="data-type-select">
                            <option value="">Select data type</option>
                            <option>All Transactions</option>
                            <option>Income Only</option>
                            <option>Expenses Only</option>
                            <option>Tax-Related Only</option>
                        </select>
                    </div>
                    <div class="setting-group">
                        <label class="setting-label">Include</label>
                        <div class="checkbox-group">
                            <label class="checkbox-label"><input type="checkbox"> Categories</label>
                            <label class="checkbox-label"><input type="checkbox"> Tags</label>
                            <label class="checkbox-label"><input type="checkbox"> Notes</label>
                        </div>
                    </div>
                    <div class="setting-group">
                        <label class="setting-label">Encryption</label>
                        <select class="setting-input" id="encryption-select">
                            <option value="">Select encryption type</option>
                            <option>None</option>
                            <option>Password Protection</option>
                            <option>Encrypt File</option>
                        </select>
                    </div>
                    <button class="export-btn" id="export-settings-btn" style="align-self: flex-end;">Export with Settings</button>
                </div>
            </div>
        </div>

        <div class="section-card">
            <h5>Export History</h5>
            <div class="history-list" id="export-history-list">
                </div>
        </div>

        <div class="section-card">
            <h5>Scheduled Exports</h5>
            <div class="schedule-form">
                <div class="setting-group">
                    <label class="setting-label">Schedule Type</label>
                    <select class="setting-input" id="schedule-type-select">
                        <option value="">Select schedule type</option>
                        <option>Weekly</option>
                        <option>Monthly</option>
                        <option>Quarterly</option>
                    </select>
                </div>
                <div class="setting-group">
                    <label class="setting-label">Format</label>
                    <select class="setting-input" id="schedule-format-select">
                        <option value="">Select format</option>
                        <option>CSV</option>
                        <option>PDF</option>
                    </select>
                </div>
                <div class="setting-group">
                    <label class="setting-label">Email to</label>
                    <input type="email" class="setting-input" id="schedule-email-input" placeholder="email@example.com">
                </div>
                <button class="save-schedule-btn">Save Schedule</button>
            </div>
            <div id="scheduled-list-container" class="schedule-list">
                </div>
        </div>
    </div>
  </div>
</div>


     <!-- Settings Section -->
<div id="settings-section" class="finance-content-section">
  <div class="finance-settings-section-container">
    <h4 class="finance-settings-section-header">
    Settings
    </h4>
    <!-- Settings section code here -->
  </div>
  </div>


  <script>
  window.financeData = {
    balance: <?php echo $_SESSION['financeData']['balance']; ?>,
    totalIncome: <?php echo $_SESSION['financeData']['totalIncome']; ?>,
    totalExpenses: <?php echo $_SESSION['financeData']['totalExpenses']; ?>,
    transactions: <?php echo json_encode($_SESSION['financeData']['transactions']); ?>,
    lastMonthData: <?php echo json_encode($_SESSION['financeData']['lastMonthData']); ?>
};
</script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../Asset/dashboard.js"></script>
  <script src="../Asset/dashboard-section.js"></script>
  <script src="../Asset/profile-section.js"></script>

  <script src="../Asset/income-section.js"></script>
  <script src="../Asset/expense-section.js"></script>
  <script src="../Asset/budget-section.js"></script>
  <script src="../Asset/billreminder-section.js"></script>
  <script src="../Asset/report-section.js"></script>
  <script src="../Asset/report-section.js"></script>
  
  <script src="../Asset/savings-section.js"></script>
  <script src="../Asset/debt-section.js"></script>
  <script src="../Asset/tax-section.js"></script>
  <script src="../Asset/exports-section.js"></script>
  <script src="../Asset/settings-section.js"></script>

</body>
</html>