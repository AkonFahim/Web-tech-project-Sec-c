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
  <link rel="stylesheet" href="dashboardsection.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
</head>
<body class="body-default-style">
  <div class="overlay-background" id="mobileOverlay"></div>

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
    <div class="top-navigation-bar">
      <div class="d-flex align-items-center">
        <div class="menu-toggle-button me-3" id="menuToggleButton">
          <i class="fas fa-bars"></i>
        </div>
        <h5 class="top-navigation-heading" id="topNavigationTitle">Dashboard</h5>
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
        </div>
      </div>
    </div>

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
        
        <!-- Charts -->
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

      <!-- Income Section -->
      <div id="income-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <h4>Income Management</h4>
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0">Add Income</h5>
              </div>
              <div class="card-body">
                <form id="incomeForm">
                  <div class="mb-3">
                    <label for="incomeAmount" class="form-label">Amount</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" step="0.01" class="form-control" id="incomeAmount" placeholder="0.00" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="incomeDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="incomeDescription" placeholder="Salary, Freelance, etc." required>
                  </div>
                  <div class="mb-3">
                    <label for="incomeCategory" class="form-label">Category</label>
                    <select class="form-select" id="incomeCategory" required>
                      <option value="">Select Category</option>
                      <option value="Salary">Salary</option>
                      <option value="Freelance">Freelance</option>
                      <option value="Investment">Investment</option>
                      <option value="Gift">Gift</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="incomeDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="incomeDate" required>
                  </div>
                  <button type="button" class="btn btn-success" id="saveIncome">Add Income</button>
                </form>
                <div id="incomeMessage" class="py-2"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-success text-white">
                <h5 class="mb-0">Income History</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
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

      <!-- Expenses Section -->
      <div id="expenses-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <h4>Expense Management</h4>
        <div class="row mt-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Add Expense</h5>
              </div>
              <div class="card-body">
                <form id="expenseForm">
                  <div class="mb-3">
                    <label for="expenseAmount" class="form-label">Amount</label>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="number" step="0.01" class="form-control" id="expenseAmount" placeholder="0.00" required>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="expenseDescription" class="form-label">Description</label>
                    <input type="text" class="form-control" id="expenseDescription" placeholder="Groceries, Rent, etc." required>
                  </div>
                  <div class="mb-3">
                    <label for="expenseCategory" class="form-label">Category</label>
                    <select class="form-select" id="expenseCategory" required>
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
                  <div class="mb-3">
                    <label for="expenseDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="expenseDate" required>
                  </div>
                  <button type="button" class="btn btn-danger" id="saveExpense">Add Expense</button>
                </form>
                <div id="expenseMessage" class="py-2"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-danger text-white">
                <h5 class="mb-0">Expense History</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
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

      <!-- ✅ New Budget Section -->
      <div id="budget-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
        <h5 class="mb-3">Manage Budgets</h5>

        <!-- Budget Form -->
        <form id="budgetForm" class="mb-3 d-flex flex-wrap gap-2">
          <input type="text" id="budgetCategory" class="form-control w-auto" placeholder="Budget Category (e.g. Food)" required>
          <input type="number" id="budgetAmount" class="form-control w-auto" placeholder="Amount" required>
          <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <!-- Budget Table -->
        <h6 class="mb-2">Your Budgets:</h6>
        <table class="table table-bordered table-striped" id="budgetTable">
          <thead class="table-light">
            <tr>
              <th>Category</th>
              <th>Amount (৳)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Budget rows will appear here -->
          </tbody>
        </table>
      </div>

      <div id="bill-reminders-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
  <h5 class="mb-3">Bill Reminders</h5>

  <!-- Add Bill Form -->
  <form id="billForm" class="mb-3 d-flex flex-wrap gap-2">
    <input type="text" id="billName" class="form-control w-auto" placeholder="Bill Name (e.g. Electricity)" required>
    <input type="date" id="billDueDate" class="form-control w-auto" required>
    <label class="form-check-label ms-2">
      <input type="checkbox" id="autoPay" class="form-check-input"> Auto-Pay
    </label>
    <button type="submit" class="btn btn-primary">Add</button>
  </form>

  <!-- Bill Table -->
  <h6 class="mb-2">Your Bills:</h6>
  <table class="table table-bordered table-striped" id="billTable">
    <thead class="table-light">
      <tr>
        <th>Bill Name</th>
        <th>Due Date</th>
        <th>Auto-Pay</th>
        <th>Alert</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Bill rows will appear here -->
    </tbody>
  </table>
</div>
   <div id="reports-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
  <h5 class="mb-3">Reports & Graphs</h5>

  <!-- Date Range Filter -->
  <form id="reportFilterForm" class="mb-3 d-flex flex-wrap gap-2">
    <label>From: <input type="date" id="reportStartDate" class="form-control"></label>
    <label>To: <input type="date" id="reportEndDate" class="form-control"></label>
    <button type="submit" class="btn btn-primary">Generate</button>
  </form>

  <!-- Charts -->
  <div class="mb-4">
    <h6>Spending Trends</h6>
    <canvas id="spendingTrendsChart"></canvas>
  </div>

  <div class="mb-4">
    <h6>Income vs Expense</h6>
    <canvas id="incomeExpenseChart"></canvas>
  </div>

  <div class="mb-4">
    <h6>Net Worth</h6>
    <canvas id="netWorthChart"></canvas>
  </div>

  <button id="exportChartsBtn" class="btn btn-success">Export Charts</button>
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
  </script>
  <script src="../asset/dashboard.js"></script>
  <script src="dashboardsection.js"></script>
  <script src="incomesection.js"></script>
  <script src="expensesection.js"></script>
  <script src="budgetsection.js"></script>
  <script src="billreminderssection.js"></script>
  <script src="reportsection.js"></script>


</body>
</html>
