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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<link rel="stylesheet" href="../asset/dashboard.css">

</head>
<body class="body-default-style">
  <div class="overlay-background" id="mobileOverlay"></div>

  <!-- Logout Modal -->
  <div class="logout-modal" id="logoutModal">
    <div class="logout-modal-content">
      <h5>Confirm Logout</h5>
      <p>Are you sure you want to logout?</p>
      <div class="logout-modal-buttons">
        <button type="button" class="btn btn-secondary" id="cancelLogout">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar-container" id="sidebarContainer">
    <div class="sidebar-header-container">
      <h3 class="sidebar-header-title">
        <i class="fas fa-wallet"></i> 
        <span>Finance Tracker</span>
      </h3>
    </div>
    <div class="sidebar-menu-container">
      <a href="#" class="sidebar-menu-item-link active-menu-link" data-content="dashboard-section">
        <i class="fas fa-home sidebar-menu-item-icon"></i> <span>Dashboard</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="income-section">
        <i class="fas fa-money-bill-wave sidebar-menu-item-icon"></i> <span>Income</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="expenses-section">
        <i class="fas fa-credit-card sidebar-menu-item-icon"></i> <span>Expenses</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="budget-section">
        <i class="fas fa-chart-pie sidebar-menu-item-icon"></i> <span>Budget</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="bill-reminders-section">
        <i class="fas fa-calendar-alt sidebar-menu-item-icon"></i> <span>Bill Reminders</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="reports-section">
        <i class="fas fa-chart-line sidebar-menu-item-icon"></i> <span>Reports</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="savings-goals-section">
        <i class="fas fa-piggy-bank sidebar-menu-item-icon"></i> <span>Savings Goals</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="debt-tracking-section">
        <i class="fas fa-landmark sidebar-menu-item-icon"></i> <span>Debt Tracking</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="tax-categories-section">
        <i class="fas fa-file-invoice-dollar sidebar-menu-item-icon"></i> <span>Tax Categories</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" data-content="settings-section">
        <i class="fas fa-cog sidebar-menu-item-icon"></i> <span>Settings</span>
      </a>
      <a href="#" class="sidebar-menu-item-link" id="logoutLink">
        <i class="fas fa-sign-out-alt sidebar-menu-item-icon"></i> <span>Logout</span>
      </a>
    </div>
  </div>

  

  <!-- Main Content -->
  <div class="main-content-container">
    <div class="top-navigation-bar d-flex justify-content-between align-items-center px-3 py-2">
      <div class="d-flex align-items-center">
        <div class="menu-toggle-button me-3" id="menuToggleButton">
          <i class="fas fa-bars"></i>
        </div>
        <h5 class="top-navigation-heading mb-0" id="topNavigationTitle">Dashboard 2</h5>
      </div>

      <div class="d-flex align-items-center">
        <div class="notification-badge-container me-3">
          <i class="fas fa-bell"></i>
          <span class="notification-badge-dot">3</span>
        </div>


        <div class="user-profile-container d-flex align-items-center">
          <span class="user-welcome-text me-2 d-none d-md-inline">
            Welcome, <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'User'; ?>
          </span>

          
        <!-- Search Bar -->
        <div class="search-container me-3">
          <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
        </div>
        </div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="page-content-container">
      <!-- Dashboard Section -->
      <div id="dashboard-section" class="content-section active-content-section">
        <div class="dashboard-header">
          <h2>Financial Dashboard</h2>
          <p>Overview of your financial health</p>
        </div>
        
        <!-- Summary Cards -->
         <div class="summary-cards-container">
          <div class="summary-card">
              <div class="card-icon savings-icon"><i class="fas fa-piggy-bank"></i></div>
              <div class="card-content">
                <div class="card-value" id="balanceDisplay">$<?php echo number_format($_SESSION['financeData']['totalIncome'] - $_SESSION['financeData']['totalExpenses'], 2); ?></div>
                <div class="card-label">Balance</div>
              </div>
            </div>

        
          <div class="summary-card">
            <div class="card-icon income-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="card-content">
              <div class="card-value" id="totalIncomeDisplay">$<?php echo number_format($_SESSION['financeData']['totalIncome'], 2); ?></div>
              <div class="card-label">Total Income</div>
            </div>
          </div>
          
          <div class="summary-card">
            <div class="card-icon expenses-icon"><i class="fas fa-credit-card"></i></div>
            <div class="card-content">
              <div class="card-value" id="totalExpensesDisplay">$<?php echo number_format($_SESSION['financeData']['totalExpenses'], 2); ?></div>
              <div class="card-label">Total Expenses</div>
            </div>
          </div>
          
         
          
          <div class="summary-card">
            <div class="card-icon budget-icon"><i class="fas fa-chart-pie"></i></div>
            <div class="card-content">
              <div class="card-value">82%</div>
              <div class="card-label">Budget Goal Progress</div>
            </div>
          </div>
        </div>
        
        <!-- Charts and Visualizations -->
        <div class="charts-container">
          <div class="chart-card">
            <div class="chart-header">
              <h3>Income vs Expenses</h3>
              <select class="chart-period-selector" id="incomeExpensePeriod">
                <option>Last 7 Days</option>
                <option selected>Last 30 Days</option>
                <option>Last 90 Days</option>
              </select>
            </div>
            <canvas id="incomeExpenseChart"></canvas>
          </div>
          
          <div class="chart-card">
            <div class="chart-header">
              <h3>Spending by Category</h3>
              <select class="chart-period-selector" id="spendingCategoryPeriod">
                <option>Last 7 Days</option>
                <option selected>Last 30 Days</option>
                <option>Last 90 Days</option>
              </select>
            </div>
            <canvas id="spendingCategoryChart"></canvas>
          </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="recent-transactions-container">
          <div class="section-header">
            <h3>Recent Transactions</h3>
            <button class="view-all-button">View All</button>
          </div>
          
          <div class="transactions-list" id="recentTransactionsList">
            <p class="text-muted text-center py-3">No recent transactions</p>
          </div>
        </div>
      </div>
      <div id="income-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Income content</p>
      </div>
      <div id="expenses-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Expenses content</p>
      </div>
      <div id="budget-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Budget content</p>
      </div>
      <div id="bill-reminders-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Bill Reminders content</p>
      </div>
      <div id="reports-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Reports content</p>
      </div>
      <div id="savings-goals-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Savings Goals content</p>
      </div>
      <div id="debt-tracking-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Debt Tracking content</p>
      </div>
      <div id="tax-categories-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Tax Categories content</p>
      </div>
      <div id="settings-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Settings content</p>
      </div>
    </div>
  </div>

  <script src="../asset/dashboard.js"></script >

</body>
</html>
