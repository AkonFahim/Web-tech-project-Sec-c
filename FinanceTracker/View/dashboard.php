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
  <link rel="stylesheet" href="../Asset/income-section.css">
  <link rel="stylesheet" href="../Asset/expense-section.css">
  <link rel="stylesheet" href="../Asset/budget-section.css">
  <link rel="stylesheet" href="../Asset/billreminder-section.css">
  <link rel="stylesheet" href="../Asset/report-section.css">
  <link rel="stylesheet" href="../Asset/savings-section.css">
  <link rel="stylesheet" href="../Asset/debt-section.css">
  <link rel="stylesheet" href="../Asset/tax-section.css">
  <link rel="stylesheet" href="../Asset/export-section.css?v=1.0.0">
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
  <a href="#" class="finance-sidebar-menu-item-link" data-section="reports-section">
    <i class="fas fa-chart-line finance-sidebar-menu-item-icon"></i> <span>Reports</span>
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
      <!-- Dashboard Section -->
      <div id="dashboard-section" class="finance-content-section active-content-section">
        <div class="finance-dashboard-header">
          <p>Overview of your financial health</p>
        </div>
        
        <!-- Summary Cards -->
        <div class="finance-summary-cards-container">
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-savings-icon"><i class="fas fa-piggy-bank"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value" id="balanceDisplay"></div>
              <div class="finance-card-label">Balance</div>
            </div>
          </div>
        
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-income-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value" id="totalIncomeDisplay"></div>
              <div class="finance-card-label">Total Income</div>
            </div>
          </div>
          
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-expenses-icon"><i class="fas fa-credit-card"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value" id="totalExpensesDisplay"></div>
              <div class="finance-card-label">Total Expenses</div>
            </div>
          </div>
          
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-budget-icon"><i class="fas fa-chart-pie"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value">82%</div>
              <div class="finance-card-label">Budget Goal Progress</div>
            </div>
          </div>
        </div>
        
        <!-- Charts and Visualizations -->
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
          
          <div class="finance-chart-card">
            <div class="finance-chart-header">
              <h3>Spending by Category</h3>
              <select class="finance-chart-period-selector" id="spendingCategoryPeriod">
                <option>Last 7 Days</option>
                <option selected>Last 30 Days</option>
                <option>Last 90 Days</option>
              </select>
            </div>
            <canvas id="spendingCategoryChart"></canvas>
          </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="finance-recent-transactions-container">
          <div class="finance-section-header">
            <h3>Recent Transactions</h3>
            <button class="finance-view-all-button">View All</button>
          </div>
          
          <div class="finance-transactions-list" id="recentTransactionsList">
            <!-- Transactions will be loaded via JavaScript -->
            <p class="finance-no-transactions-text">No recent transactions</p>
          </div>
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
      <div id="budget-section" class="finance-content-section">
        <div class="finance-budget-section-container">
          <h4 class="finance-budget-section-header">Budget Management</h4>
          <!-- Budget section code here -->
        </div>
      </div>



      <!-- Bill Reminders Section -->
      <div id="bill-reminders-section" class="finance-content-section">
        <div class="finance-billreminder-section-container">
          <h4 class="finance-billreminder-section-header">Bill Reminders</h4>
          <!-- Bill Reminder section code here -->
        </div>
      </div>


      <!-- Reports Section -->
      <div id="reports-section" class="finance-content-section">
        <div class="finance-report-section-container">
          <h4 class="finance-report-section-header">Financial Reports</h4>
          <!-- Reports section code here -->
        </div>
      </div>



      <!-- Savings Goals Section -->
      <div id="savings-goals-section" class="finance-content-section">
        <div class="finance-savings-section-container">
          <h4 class="finance-savings-section-header">Savings Goals</h4>
          <!-- Savings Goals section code here -->
        </div>
      </div>


      <!-- Debt Tracking Section -->
      <div id="debt-tracking-section" class="finance-content-section">
        <div class="finance-debt-section-container">
          <h4 class="finance-debt-section-header">Debt Tracking</h4>
          <!-- Debt Tracking section code here -->
        </div>
      </div>

      <!-- Tax Categories Section -->
      <div id="tax-categories-section" class="finance-content-section">
        <div class="finance-tax-section-container">
          <h4 class="finance-tax-section-header">Tax Categories</h4>
          <!-- Tax Categories section code here -->
        </div>
      </div>


      <!-- Export Data Section -->
<div id="export-data-section" class="finance-content-section">
  <div class="finance-export-section-container">
    <h4 class="finance-export-section-header"></h4>
    <!-- Export Data section code here -->
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
  <script src="../Asset/income-section.js"></script>
  <script src="../Asset/expense-section.js"></script>
  <script src="../Asset/budget-section.js"></script>
  <script src="../Asset/billreminder-section.js"></script>
  <script src="../Asset/report-section.js"></script>
  <script src="../Asset/savings-section.js"></script>
  <script src="../Asset/debt-section.js"></script>
  <script src="../Asset/tax-section.js"></script>
  <script src="../Asset/export-section.js"></script>
  <script src="../Asset/settings-section.js"></script>

</body>
</html>