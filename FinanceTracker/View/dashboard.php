<?php 
session_start(); 
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
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
      <div id="dashboard-section" class="content-section active-content-section p-3 bg-white rounded-3 shadow-sm">
        <p class="content-text text-muted mb-0">Dashboard content</p>
      </div>
  </div>

  <div id="income-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
  <h5 class="mb-3">Add Income Source</h5>

  <!-- Income Form -->
  <form id="incomeForm" class="mb-3 d-flex flex-wrap gap-2">
    <input type="text" id="incomeSource" class="form-control w-auto" placeholder="Source (e.g. Salary)" required>
    <input type="number" id="incomeAmount" class="form-control w-auto" placeholder="Amount" required>
    <button type="submit" class="btn btn-success">Add</button>
  </form>

  <!-- Income Table -->
  <h6 class="mb-2">Your Income Sources:</h6>
  <table class="table table-bordered table-striped" id="incomeTable">
    <thead class="table-light">
      <tr>
        <th>Source</th>
        <th>Amount (৳)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Income rows will appear here -->
    </tbody>
  </table>
</div>


     <div id="expenses-section" class="content-section p-3 bg-white rounded-3 shadow-sm">
  <h5 class="mb-3">Add Expense</h5>

  <!-- Expense Form -->
  <form id="expenseForm" class="mb-3 d-flex flex-wrap gap-2">
    <input type="text" id="expenseSource" class="form-control w-auto" placeholder="Expense Name (e.g. Rent)" required>
    <input type="number" id="expenseAmount" class="form-control w-auto" placeholder="Amount" required>
    <button type="submit" class="btn btn-danger">Add</button>
  </form>

  <!-- Expense Table -->
  <h6 class="mb-2">Your Expenses:</h6>
  <table class="table table-bordered table-striped" id="expenseTable">
    <thead class="table-light">
      <tr>
        <th>Expense</th>
        <th>Amount (৳)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <!-- Expense rows will appear here -->
    </tbody>
  </table>
</div>







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

  

  <script src="../asset/dashboard.js"></script>
</body>
</html>
