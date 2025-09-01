<?php
session_start();

// Check if user is logged in
if(!isset($_COOKIE['status']) || $_COOKIE['status'] !== 'true') {
    header('location: login.php?error=badrequest');
    exit();
}

// Check if user email is set in session
if(!isset($_SESSION['email'])) {
    header('location: login.php?error=badrequest');
    exit();
}

$_SESSION['financeData'] = isset($_SESSION['financeData']) ? $_SESSION['financeData'] : [
    'balance' => 0.0,
    'totalIncome' => 0.0,
    'totalExpenses' => 0.0,
    'transactions' => [],
    'lastMonthData' => [
        'income' => 4500,
        'expenses' => 2750
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Finance Tracker</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
  <link rel="stylesheet" href="../Asset/export-section.css">
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
    <!-- Replace the entire sidebar menu container with this updated version -->
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
            Welcome, <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'User'; ?>
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
              <div class="finance-card-value" id="balanceDisplay">$<?php echo number_format($_SESSION['financeData']['totalIncome'] - $_SESSION['financeData']['totalExpenses'], 2); ?></div>
              <div class="finance-card-label">Balance</div>
            </div>
          </div>
        
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-income-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value" id="totalIncomeDisplay">$<?php echo number_format($_SESSION['financeData']['totalIncome'], 2); ?></div>
              <div class="finance-card-label">Total Income</div>
            </div>
          </div>
          
          <div class="finance-summary-card">
            <div class="finance-card-icon finance-expenses-icon"><i class="fas fa-credit-card"></i></div>
            <div class="finance-card-content">
              <div class="finance-card-value" id="totalExpensesDisplay">$<?php echo number_format($_SESSION['financeData']['totalExpenses'], 2); ?></div>
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

      <!-- Income Section -->
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
          <div class="finance-budget-content">
            <div class="finance-budget-cards-container">
              <div class="finance-budget-card">
                <div class="finance-budget-card-header">
                  <h5>Monthly Budget</h5>
                  <span class="finance-budget-card-amount">$2,500.00</span>
                </div>
                <div class="finance-budget-progress">
                  <div class="finance-budget-progress-bar">
                    <div class="finance-budget-progress-fill" style="width: 65%"></div>
                  </div>
                  <div class="finance-budget-progress-text">65% used</div>
                </div>
                <div class="finance-budget-card-footer">
                  <span class="finance-budget-remaining">$875.00 remaining</span>
                </div>
              </div>
              
              <div class="finance-budget-card">
                <div class="finance-budget-card-header">
                  <h5>Set New Budget</h5>
                </div>
                <div class="finance-budget-form">
                  <div class="finance-budget-form-group">
                    <label class="finance-budget-form-label">Budget Amount</label>
                    <div class="finance-budget-input-group">
                      <span class="finance-budget-input-prefix">$</span>
                      <input type="number" step="0.01" class="finance-budget-form-input" placeholder="0.00">
                    </div>
                  </div>
                  <div class="finance-budget-form-group">
                    <label class="finance-budget-form-label">Category</label>
                    <select class="finance-budget-form-input">
                      <option value="">Select Category</option>
                      <option value="Food">Food & Dining</option>
                      <option value="Transportation">Transportation</option>
                      <option value="Housing">Housing</option>
                      <option value="Utilities">Utilities</option>
                      <option value="Entertainment">Entertainment</option>
                    </select>
                  </div>
                  <button class="finance-setbudget-btn">Set Budget</button>
                </div>
              </div>
            </div>
            
            <div class="finance-budget-categories-container">
              <h5>Budget by Category</h5>
              <div class="finance-budget-categories-list">
                <div class="finance-budget-category-item">
                  <div class="finance-budget-category-info">
                    <span class="finance-budget-category-name">Food & Dining</span>
                    <span class="finance-budget-category-amount">$600 / $800</span>
                  </div>
                  <div class="finance-budget-category-progress">
                    <div class="finance-budget-category-progress-bar">
                      <div class="finance-budget-category-progress-fill" style="width: 75%"></div>
                    </div>
                  </div>
                </div>
                
                <div class="finance-budget-category-item">
                  <div class="finance-budget-category-info">
                    <span class="finance-budget-category-name">Transportation</span>
                    <span class="finance-budget-category-amount">$200 / $300</span>
                  </div>
                  <div class="finance-budget-category-progress">
                    <div class="finance-budget-category-progress-bar">
                      <div class="finance-budget-category-progress-fill" style="width: 67%"></div>
                    </div>
                  </div>
                </div>
                
                <div class="finance-budget-category-item">
                  <div class="finance-budget-category-info">
                    <span class="finance-budget-category-name">Entertainment</span>
                    <span class="finance-budget-category-amount">$150 / $200</span>
                  </div>
                  <div class="finance-budget-category-progress">
                    <div class="finance-budget-category-progress-bar">
                      <div class="finance-budget-category-progress-fill" style="width: 75%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bill Reminders Section -->
      <div id="bill-reminders-section" class="finance-content-section">
        <div class="finance-billreminder-section-container">
          <h4 class="finance-billreminder-section-header">Bill Reminders</h4>
          <div class="finance-billreminder-content">
            <div class="finance-billreminder-cards-container">
              <div class="finance-billreminder-card">
                <div class="finance-billreminder-card-header">
                  <h5>Upcoming Bills</h5>
                  <button class="finance-addbill-btn">Add Bill</button>
                </div>
                <div class="finance-billreminder-list">
                  <div class="finance-billreminder-item">
                    <div class="finance-billreminder-icon">
                      <i class="fas fa-home"></i>
                    </div>
                    <div class="finance-billreminder-details">
                      <h6>Rent Payment</h6>
                      <p>Due in 3 days</p>
                    </div>
                    <div class="finance-billreminder-amount">
                      $1,200.00
                    </div>
                  </div>
                  
                  <div class="finance-billreminder-item">
                    <div class="finance-billreminder-icon">
                      <i class="fas fa-bolt"></i>
                    </div>
                    <div class="finance-billreminder-details">
                      <h6>Electricity Bill</h6>
                      <p>Due in 5 days</p>
                    </div>
                    <div class="finance-billreminder-amount">
                      $85.50
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="finance-billreminder-card">
                <div class="finance-billreminder-card-header">
                  <h5>Add New Bill</h5>
                </div>
                <div class="finance-billreminder-form">
                  <div class="finance-billreminder-form-group">
                    <label class="finance-billreminder-form-label">Bill Name</label>
                    <input type="text" class="finance-billreminder-form-input" placeholder="e.g., Internet Bill">
                  </div>
                  <div class="finance-billreminder-form-group">
                    <label class="finance-billreminder-form-label">Amount</label>
                    <div class="finance-billreminder-input-group">
                      <span class="finance-billreminder-input-prefix">$</span>
                      <input type="number" step="0.01" class="finance-billreminder-form-input" placeholder="0.00">
                    </div>
                  </div>
                  <div class="finance-billreminder-form-group">
                    <label class="finance-billreminder-form-label">Due Date</label>
                    <input type="date" class="finance-billreminder-form-input">
                  </div>
                  <div class="finance-billreminder-form-group">
                    <label class="finance-billreminder-form-label">Recurrence</label>
                    <select class="finance-billreminder-form-input">
                      <option value="once">One Time</option>
                      <option value="monthly">Monthly</option>
                      <option value="quarterly">Quarterly</option>
                      <option value="yearly">Yearly</option>
                    </select>
                  </div>
                  <button class="finance-savebill-btn">Save Bill</button>
                </div>
              </div>
            </div>
            
            <div class="finance-billreminder-calendar-container">
              <h5>Bill Calendar</h5>
              <div class="finance-billreminder-calendar">
                <!-- Calendar view would be implemented here -->
                <div class="finance-billreminder-calendar-placeholder">
                  Calendar view of upcoming bills
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reports Section -->
      <div id="reports-section" class="finance-content-section">
        <div class="finance-report-section-container">
          <h4 class="finance-report-section-header">Financial Reports</h4>
          <div class="finance-report-filters">
            <div class="finance-report-filter-group">
              <label class="finance-report-filter-label">Report Type</label>
              <select class="finance-report-filter-select">
                <option value="spending">Spending Report</option>
                <option value="income">Income Report</option>
                <option value="networth">Net Worth</option>
                <option value="cashflow">Cash Flow</option>
              </select>
            </div>
            <div class="finance-report-filter-group">
              <label class="finance-report-filter-label">Date Range</label>
              <select class="finance-report-filter-select">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>
            <div class="finance-report-filter-group">
              <label class="finance-report-filter-label">Category</label>
              <select class="finance-report-filter-select">
                <option value="all">All Categories</option>
                <option value="food">Food & Dining</option>
                <option value="transport">Transportation</option>
                <option value="housing">Housing</option>
              </select>
            </div>
            <button class="finance-generatereport-btn">Generate Report</button>
          </div>
          
          <div class="finance-report-charts">
            <div class="finance-report-chart-container">
              <h5>Spending Trends</h5>
              <canvas id="spendingTrendsChart"></canvas>
            </div>
            <div class="finance-report-chart-container">
              <h5>Income vs Expenses</h5>
              <canvas id="incomeVsExpensesChart"></canvas>
            </div>
          </div>
          
          <div class="finance-report-data">
            <h5>Report Data</h5>
            <div class="finance-report-table-container">
              <table class="finance-report-table">
                <thead>
                  <tr>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Percentage</th>
                    <th>Trend</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Food & Dining</td>
                    <td>$600.00</td>
                    <td>30%</td>
                    <td><i class="fas fa-arrow-up finance-trend-up"></i> 5%</td>
                  </tr>
                  <tr>
                    <td>Housing</td>
                    <td>$1,200.00</td>
                    <td>60%</td>
                    <td><i class="fas fa-minus finance-trend-neutral"></i> 0%</td>
                  </tr>
                  <tr>
                    <td>Transportation</td>
                    <td>$200.00</td>
                    <td>10%</td>
                    <td><i class="fas fa-arrow-down finance-trend-down"></i> 3%</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Savings Goals Section -->
      <div id="savings-goals-section" class="finance-content-section">
        <div class="finance-savings-section-container">
          <h4 class="finance-savings-section-header">Savings Goals</h4>
          <div class="finance-savings-content">
            <div class="finance-savings-cards-container">
              <div class="finance-savings-card">
                <div class="finance-savings-card-header">
                  <h5>Create New Goal</h5>
                </div>
                <div class="finance-savings-form">
                  <div class="finance-savings-form-group">
                    <label class="finance-savings-form-label">Goal Name</label>
                    <input type="text" class="finance-savings-form-input" placeholder="e.g., New Laptop">
                  </div>
                  <div class="finance-savings-form-group">
                    <label class="finance-savings-form-label">Target Amount</label>
                    <div class="finance-savings-input-group">
                      <span class="finance-savings-input-prefix">$</span>
                      <input type="number" step="0.01" class="finance-savings-form-input" placeholder="0.00">
                    </div>
                  </div>
                  <div class="finance-savings-form-group">
                    <label class="finance-savings-form-label">Target Date</label>
                    <input type="date" class="finance-savings-form-input">
                  </div>
                  <div class="finance-savings-form-group">
                    <label class="finance-savings-form-label">Initial Amount</label>
                    <div class="finance-savings-input-group">
                      <span class="finance-savings-input-prefix">$</span>
                      <input type="number" step="0.01" class="finance-savings-form-input" placeholder="0.00">
                    </div>
                  </div>
                  <button class="finance-creategoal-btn">Create Goal</button>
                </div>
              </div>
              
              <div class="finance-savings-card">
                <div class="finance-savings-card-header">
                  <h5>Savings Summary</h5>
                </div>
                <div class="finance-savings-summary">
                  <div class="finance-savings-summary-item">
                    <span class="finance-savings-summary-label">Total Goals</span>
                    <span class="finance-savings-summary-value">3</span>
                  </div>
                  <div class="finance-savings-summary-item">
                    <span class="finance-savings-summary-label">Total Saved</span>
                    <span class="finance-savings-summary-value">$2,450.00</span>
                  </div>
                  <div class="finance-savings-summary-item">
                    <span class="finance-savings-summary-label">Total Target</span>
                    <span class="finance-savings-summary-value">$5,000.00</span>
                  </div>
                  <div class="finance-savings-summary-item">
                    <span class="finance-savings-summary-label">Overall Progress</span>
                    <span class="finance-savings-summary-value">49%</span>
                  </div>
                </div>
                <div class="finance-savings-overall-progress">
                  <div class="finance-savings-progress-bar">
                    <div class="finance-savings-progress-fill" style="width: 49%"></div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="finance-savings-goals-container">
              <h5>Your Savings Goals</h5>
              <div class="finance-savings-goals-list">
                <div class="finance-savings-goal-item">
                  <div class="finance-savings-goal-info">
                    <h6>Emergency Fund</h6>
                    <p>Target: $3,000.00 by Dec 2023</p>
                  </div>
                  <div class="finance-savings-goal-progress">
                    <div class="finance-savings-goal-amount">$1,500.00 / $3,000.00</div>
                    <div class="finance-savings-goal-progress-bar">
                      <div class="finance-savings-goal-progress-fill" style="width: 50%"></div>
                    </div>
                    <div class="finance-savings-goal-percentage">50%</div>
                  </div>
                </div>
                
                <div class="finance-savings-goal-item">
                  <div class="finance-savings-goal-info">
                    <h6>New Laptop</h6>
                    <p>Target: $1,200.00 by Oct 2023</p>
                  </div>
                  <div class="finance-savings-goal-progress">
                    <div class="finance-savings-goal-amount">$800.00 / $1,200.00</div>
                    <div class="finance-savings-goal-progress-bar">
                      <div class="finance-savings-goal-progress-fill" style="width: 67%"></div>
                    </div>
                    <div class="finance-savings-goal-percentage">67%</div>
                  </div>
                </div>
                
                <div class="finance-savings-goal-item">
                  <div class="finance-savings-goal-info">
                    <h6>Vacation</h6>
                    <p>Target: $800.00 by Nov 2023</p>
                  </div>
                  <div class="finance-savings-goal-progress">
                    <div class="finance-savings-goal-amount">$150.00 / $800.00</div>
                    <div class="finance-savings-goal-progress-bar">
                      <div class="finance-savings-goal-progress-fill" style="width: 19%"></div>
                    </div>
                    <div class="finance-savings-goal-percentage">19%</div>
                  </div>
                </div>
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
                  <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Total Debt</span>
                    <span class="finance-debt-summary-value">$8,450.00</span>
                  </div>
                  <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Monthly Payments</span>
                    <span class="finance-debt-summary-value">$325.00</span>
                  </div>
                  <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Interest Cost</span>
                    <span class="finance-debt-summary-value">$1,200.00/yr</span>
                  </div>
                  <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Projected Payoff</span>
                    <span class="finance-debt-summary-value">Oct 2025</span>
                  </div>
                </div>
                <div class="finance-debt-payoff-chart">
                  <canvas id="debtPayoffChart"></canvas>
                </div>
              </div>
            </div>
            
            <div class="finance-debt-list-container">
              <h5>Your Debts</h5>
              <div class="finance-debt-list">
                <div class="finance-debt-item">
                  <div class="finance-debt-icon">
                    <i class="fas fa-credit-card"></i>
                  </div>
                  <div class="finance-debt-details">
                    <h6>Credit Card</h6>
                    <p>Interest: 18.9%</p>
                  </div>
                  <div class="finance-debt-amount">
                    <span class="finance-debt-balance">$2,450.00</span>
                    <span class="finance-debt-payment">Min: $75.00</span>
                  </div>
                  <div class="finance-debt-actions">
                    <button class="finance-makepayment-btn">Make Payment</button>
                  </div>
                </div>
                
                <div class="finance-debt-item">
                  <div class="finance-debt-icon">
                    <i class="fas fa-university"></i>
                  </div>
                  <div class="finance-debt-details">
                    <h6>Student Loan</h6>
                    <p>Interest: 5.6%</p>
                  </div>
                  <div class="finance-debt-amount">
                    <span class="finance-debt-balance">$5,200.00</span>
                    <span class="finance-debt-payment">Min: $220.00</span>
                  </div>
                  <div class="finance-debt-actions">
                    <button class="finance-makepayment-btn">Make Payment</button>
                  </div>
                </div>
                
                <div class="finance-debt-item">
                  <div class="finance-debt-icon">
                    <i class="fas fa-car"></i>
                  </div>
                  <div class="finance-debt-details">
                    <h6>Car Loan</h6>
                    <p>Interest: 4.2%</p>
                  </div>
                  <div class="finance-debt-amount">
                    <span class="finance-debt-balance">$800.00</span>
                    <span class="finance-debt-payment">Min: $30.00</span>
                  </div>
                  <div class="finance-debt-actions">
                    <button class="finance-makepayment-btn">Make Payment</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tax Categories Section -->
      <div id="tax-categories-section" class="finance-content-section">
        <div class="finance-tax-section-container">
          <h4 class="finance-tax-section-header">Tax Categories</h4>
          <div class="finance-tax-content">
            <div class="finance-tax-cards-container">
              <div class="finance-tax-card">
                <div class="finance-tax-card-header">
                  <h5>Tax Summary</h5>
                </div>
                <div class="finance-tax-summary">
                  <div class="finance-tax-summary-item">
                    <span class="finance-tax-summary-label">Deductible Expenses</span>
                    <span class="finance-tax-summary-value">$2,450.00</span>
                  </div>
                  <div class="finance-tax-summary-item">
                    <span class="finance-tax-summary-label">Estimated Savings</span>
                    <span class="finance-tax-summary-value">$612.50</span>
                  </div>
                  <div class="finance-tax-summary-item">
                    <span class="finance-tax-summary-label">Quarterly Tax Estimate</span>
                    <span class="finance-tax-summary-value">$1,200.00</span>
                  </div>
                </div>
                <div class="finance-tax-deductible-chart">
                  <canvas id="taxDeductibleChart"></canvas>
                </div>
              </div>
              
              <div class="finance-tax-card">
                <div class="finance-tax-card-header">
                  <h5>Tax Settings</h5>
                </div>
                <div class="finance-tax-settings">
                  <div class="finance-tax-setting-group">
                    <label class="finance-tax-setting-label">Tax Year</label>
                    <select class="finance-tax-setting-input">
                      <option>2023</option>
                      <option>2022</option>
                      <option>2021</option>
                    </select>
                  </div>
                  <div class="finance-tax-setting-group">
                    <label class="finance-tax-setting-label">Tax Bracket</label>
                    <select class="finance-tax-setting-input">
                      <option>22%</option>
                      <option>24%</option>
                      <option>32%</option>
                    </select>
                  </div>
                  <div class="finance-tax-setting-group">
                    <label class="finance-tax-setting-label">Filing Status</label>
                    <select class="finance-tax-setting-input">
                      <option>Single</option>
                      <option>Married Filing Jointly</option>
                      <option>Married Filing Separately</option>
                      <option>Head of Household</option>
                    </select>
                  </div>
                  <button class="finance-savetaxsettings-btn">Save Settings</button>
                </div>
              </div>
            </div>
            
            <div class="finance-tax-categories-container">
              <h5>Tax-Deductible Expenses</h5>
              <div class="finance-tax-categories-list">
                <div class="finance-tax-category-item">
                  <div class="finance-tax-category-info">
                    <h6>Home Office</h6>
                    <p>12 transactions</p>
                  </div>
                  <div class="finance-tax-category-amount">$850.00</div>
                  <div class="finance-tax-category-actions">
                    <button class="finance-viewtransactions-btn">View</button>
                  </div>
                </div>
                
                <div class="finance-tax-category-item">
                  <div class="finance-tax-category-info">
                    <h6>Business Expenses</h6>
                    <p>8 transactions</p>
                  </div>
                  <div class="finance-tax-category-amount">$1,200.00</div>
                  <div class="finance-tax-category-actions">
                    <button class="finance-viewtransactions-btn">View</button>
                  </div>
                </div>
                
                <div class="finance-tax-category-item">
                  <div class="finance-tax-category-info">
                    <h6>Charitable Donations</h6>
                    <p>5 transactions</p>
                  </div>
                  <div class="finance-tax-category-amount">$400.00</div>
                  <div class="finance-tax-category-actions">
                    <button class="finance-viewtransactions-btn">View</button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="finance-tax-export-container">
              <h5>Export Tax Data</h5>
              <div class="finance-tax-export-options">
                <button class="finance-exportcsv-btn">Export to CSV</button>
                <button class="finance-exportpdf-btn">Export to PDF</button>
                <button class="finance-exportcpa-btn">CPA Report</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Export Data Section -->
      <div id="export-data-section" class="finance-content-section">
        <div class="finance-export-section-container">
          <h4 class="finance-export-section-header">Export Data</h4>
          <div class="finance-export-content">
            <div class="finance-export-cards-container">
              <div class="finance-export-card">
                <div class="finance-export-card-header">
                  <h5>Export Options</h5>
                </div>
                <div class="finance-export-options">
                  <div class="finance-export-option">
                    <div class="finance-export-option-icon">
                      <i class="fas fa-file-csv"></i>
                    </div>
                    <div class="finance-export-option-info">
                      <h6>CSV Export</h6>
                      <p>Comma-separated values for spreadsheets</p>
                    </div>
                    <button class="finance-exportoption-btn">Export</button>
                  </div>
                  
                  <div class="finance-export-option">
                    <div class="finance-export-option-icon">
                      <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="finance-export-option-info">
                      <h6>PDF Report</h6>
                      <p>Formatted PDF document for printing</p>
                    </div>
                    <button class="finance-exportoption-btn">Export</button>
                  </div>
                  
                  <div class="finance-export-option">
                    <div class="finance-export-option-icon">
                      <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="finance-export-option-info">
                      <h6>QuickBooks Format</h6>
                      <p>QBO file for accounting software</p>
                    </div>
                    <button class="finance-exportoption-btn">Export</button>
                  </div>
                </div>
              </div>
              
              <div class="finance-export-card">
                <div class="finance-export-card-header">
                  <h5>Export Settings</h5>
                </div>
                <div class="finance-export-settings">
                  <div class="finance-export-setting-group">
                    <label class="finance-export-setting-label">Date Range</label>
                    <select class="finance-export-setting-input">
                      <option>Last 30 Days</option>
                      <option>Last 90 Days</option>
                      <option>This Year</option>
                      <option>Custom Range</option>
                    </select>
                  </div>
                  <div class="finance-export-setting-group">
                    <label class="finance-export-setting-label">Data Type</label>
                    <select class="finance-export-setting-input">
                      <option>All Transactions</option>
                      <option>Income Only</option>
                      <option>Expenses Only</option>
                      <option>Tax-Related Only</option>
                    </select>
                  </div>
                  <div class="finance-export-setting-group">
                    <label class="finance-export-setting-label">Include</label>
                    <div class="finance-export-checkbox-group">
                      <label class="finance-export-checkbox-label">
                        <input type="checkbox" checked> Categories
                      </label>
                      <label class="finance-export-checkbox-label">
                        <input type="checkbox" checked> Tags
                      </label>
                      <label class="finance-export-checkbox-label">
                        <input type="checkbox"> Notes
                      </label>
                    </div>
                  </div>
                  <div class="finance-export-setting-group">
                    <label class="finance-export-setting-label">Encryption</label>
                    <select class="finance-export-setting-input">
                      <option>None</option>
                      <option>Password Protection</option>
                      <option>Encrypt File</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="finance-export-history-container">
              <h5>Export History</h5>
              <div class="finance-export-history-list">
                <div class="finance-export-history-item">
                  <div class="finance-export-history-icon">
                    <i class="fas fa-file-csv"></i>
                  </div>
                  <div class="finance-export-history-details">
                    <h6>financial_data_2023.csv</h6>
                    <p>Exported on Aug 15, 2023</p>
                  </div>
                  <div class="finance-export-history-actions">
                    <button class="finance-downloadagain-btn">Download Again</button>
                  </div>
                </div>
                
                <div class="finance-export-history-item">
                  <div class="finance-export-history-icon">
                    <i class="fas fa-file-pdf"></i>
                  </div>
                  <div class="finance-export-history-details">
                    <h6>Q2_2023_Report.pdf</h6>
                    <p>Exported on Jul 5, 2023</p>
                  </div>
                  <div class="finance-export-history-actions">
                    <button class="finance-downloadagain-btn">Download Again</button>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="finance-export-schedule-container">
              <h5>Scheduled Exports</h5>
              <div class="finance-export-schedule-form">
                <div class="finance-export-schedule-group">
                  <label class="finance-export-schedule-label">Schedule Type</label>
                  <select class="finance-export-schedule-input">
                    <option>Weekly</option>
                    <option>Monthly</option>
                    <option>Quarterly</option>
                  </select>
                </div>
                <div class="finance-export-schedule-group">
                  <label class="finance-export-schedule-label">Format</label>
                  <select class="finance-export-schedule-input">
                    <option>CSV</option>
                    <option>PDF</option>
                  </select>
                </div>
                <div class="finance-export-schedule-group">
                  <label class="finance-export-schedule-label">Email to</label>
                  <input type="email" class="finance-export-schedule-input" placeholder="email@example.com">
                </div>
                <button class="finance-saveschedule-btn">Save Schedule</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Section -->
      <div id="settings-section" class="finance-content-section">
        <div class="finance-settings-section-container">
          <h4 class="finance-settings-section-header">Settings</h4>
          <div class="finance-settings-content">
            <div class="finance-settings-cards-container">
              <div class="finance-settings-card">
                <div class="finance-settings-card-header">
                  <h5>Account Settings</h5>
                </div>
                <div class="finance-settings-form">
                  <div class="finance-settings-form-group">
                    <label class="finance-settings-form-label">Email Address</label>
                    <input type="email" class="finance-settings-form-input" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'User'; ?>">
                  </div>
                  <div class="finance-settings-form-group">
                    <label class="finance-settings-form-label">Currency</label>
                    <select class="finance-settings-form-input">
                      <option>USD ($)</option>
                      <option>EUR (€)</option>
                      <option>GBP (£)</option>
                      <option>JPY (¥)</option>
                    </select>
                  </div>
                  <div class="finance-settings-form-group">
                    <label class="finance-settings-form-label">Date Format</label>
                    <select class="finance-settings-form-input">
                      <option>MM/DD/YYYY</option>
                      <option>DD/MM/YYYY</option>
                      <option>YYYY-MM-DD</option>
                    </select>
                  </div>
                  <div class="finance-settings-form-group">
                    <label class="finance-settings-form-label">Language</label>
                    <select class="finance-settings-form-input">
                      <option>English</option>
                      <option>Spanish</option>
                      <option>French</option>
                      <option>German</option>
                    </select>
                  </div>
                  <button class="finance-saveaccountsettings-btn">Save Changes</button>
                </div>
              </div>
              
              <div class="finance-settings-card">
                <div class="finance-settings-card-header">
                  <h5>Notification Settings</h5>
                </div>
                <div class="finance-settings-notifications">
                  <div class="finance-settings-notification-group">
                    <label class="finance-settings-notification-label">
                      <input type="checkbox" checked> Bill Reminders
                    </label>
                    <p>Get notified before bills are due</p>
                  </div>
                  <div class="finance-settings-notification-group">
                    <label class="finance-settings-notification-label">
                      <input type="checkbox" checked> Budget Alerts
                    </label>
                    <p>Get notified when approaching budget limits</p>
                  </div>
                  <div class="finance-settings-notification-group">
                    <label class="finance-settings-notification-label">
                      <input type="checkbox" checked> Weekly Reports
                    </label>
                    <p>Receive weekly financial summary emails</p>
                  </div>
                  <div class="finance-settings-notification-group">
                    <label class="finance-settings-notification-label">
                      <input type="checkbox"> Security Alerts
                    </label>
                    <p>Get notified of suspicious activity</p>
                  </div>
                  <button class="finance-savenotificationsettings-btn">Save Changes</button>
                </div>
              </div>
            </div>
            
            <div class="finance-settings-privacy-container">
              <h5>Privacy & Security</h5>
              <div class="finance-settings-privacy-options">
                <div class="finance-settings-privacy-option">
                  <h6>Data Export</h6>
                  <p>Download all your data</p>
                  <button class="finance-exportalldata-btn">Export All Data</button>
                </div>
                
                <div class="finance-settings-privacy-option">
                  <h6>Account Deletion</h6>
                  <p>Permanently delete your account and all data</p>
                  <button class="finance-deleteaccount-btn">Delete Account</button>
                </div>
                
                <div class="finance-settings-privacy-option">
                  <h6>Two-Factor Authentication</h6>
                  <p>Add an extra layer of security to your account</p>
                  <button class="finance-setup2fa-btn">Set Up 2FA</button>
                </div>
              </div>
            </div>
            
            <div class="finance-settings-about-container">
              <h5>About Finance Tracker</h5>
              <div class="finance-settings-about-content">
                <p>Version 1.0.0</p>
                <p>© 2023 Finance Tracker. All rights reserved.</p>
                <div class="finance-settings-about-links">
                  <a href="#" class="finance-about-link">Privacy Policy</a>
                  <a href="#" class="finance-about-link">Terms of Service</a>
                  <a href="#" class="finance-about-link">Support</a>
                  <a href="#" class="finance-about-link">Feedback</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
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