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
      //window.location.href = '../controller/logout.php';
      window.location.href = '../view/login.php';
    });

    // Close modal if clicked outside
    window.addEventListener('click', function(event) {
      if (event.target === logoutModal) {
        logoutModal.style.display = 'none';
      }
    });

    
document.addEventListener("DOMContentLoaded", () => {
  const incomeForm = document.getElementById("incomeForm");
  const incomeTableBody = document.querySelector("#incomeTable tbody");

  if (incomeForm) {
    // Add income row
    incomeForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const source = document.getElementById("incomeSource").value.trim();
      const amount = document.getElementById("incomeAmount").value.trim();

      if (source && amount) {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${source}</td>
          <td>৳${parseFloat(amount).toFixed(2)}</td>
          <td><button class="btn btn-danger btn-sm delete-income-btn">Delete</button></td>
        `;
        incomeTableBody.appendChild(row);

        // Clear inputs
        document.getElementById("incomeSource").value = "";
        document.getElementById("incomeAmount").value = "";
      }
    });

    // Delete income row using event delegation
    incomeTableBody.addEventListener("click", (e) => {
      if (e.target.classList.contains("delete-income-btn")) {
        const row = e.target.closest("tr");
        row.remove();
      }
    });
  }
});



document.addEventListener("DOMContentLoaded", () => {
  const expenseForm = document.getElementById("expenseForm");
  const expenseTableBody = document.querySelector("#expenseTable tbody");

  expenseForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const expenseInput = document.getElementById("expenseSource");
    const amountInput = document.getElementById("expenseAmount");

    const expense = expenseInput.value.trim();
    const amount = parseFloat(amountInput.value);

    if (expense && !isNaN(amount)) {
      const newRow = document.createElement("tr");

      newRow.innerHTML = `
        <td>${expense}</td>
        <td>৳${amount.toFixed(2)}</td>
        <td><button class="btn btn-danger btn-sm delete-expense-btn">Delete</button></td>
      `;

      expenseTableBody.appendChild(newRow);

      // Clear form
      expenseInput.value = "";
      amountInput.value = "";
    }
  });

  // Delete functionality
  expenseTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete-expense-btn")) {
      const row = e.target.closest("tr");
      row.remove();
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const budgetForm = document.getElementById("budgetForm");
  const budgetTableBody = document.querySelector("#budgetTable tbody");

  budgetForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const categoryInput = document.getElementById("budgetCategory");
    const amountInput = document.getElementById("budgetAmount");

    const category = categoryInput.value.trim();
    const amount = parseFloat(amountInput.value);

    if (category && !isNaN(amount)) {
      const newRow = document.createElement("tr");

      newRow.innerHTML = `
        <td>${category}</td>
        <td>৳${amount.toFixed(2)}</td>
        <td><button class="btn btn-danger btn-sm delete-budget-btn">Delete</button></td>
      `;

      budgetTableBody.appendChild(newRow);

      // Clear form
      categoryInput.value = "";
      amountInput.value = "";
    }
  });

  // Delete functionality
  budgetTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete-budget-btn")) {
      const row = e.target.closest("tr");
      row.remove();
    }
  });
});


document.addEventListener("DOMContentLoaded", () => {
  const billForm = document.getElementById("billForm");
  const billTableBody = document.querySelector("#billTable tbody");

  billForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const billName = document.getElementById("billName").value.trim();
    const dueDate = document.getElementById("billDueDate").value;
    const autoPay = document.getElementById("autoPay").checked;

    if (billName && dueDate) {
      const due = new Date(dueDate);
      const today = new Date();
      const diffDays = Math.ceil((due - today) / (1000 * 60 * 60 * 24));
      let alertText = "";

      if (diffDays > 0 && diffDays <= 3) {
        alertText = "⚠️ Due Soon!";
      } else if (diffDays < 0) {
        alertText = "❌ Overdue!";
      } else {
        alertText = "✅ On Time";
      }

      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>${billName}</td>
        <td>${dueDate}</td>
        <td>${autoPay ? "Yes" : "No"}</td>
        <td>${alertText}</td>
        <td><button class="btn btn-danger btn-sm delete-bill-btn">Delete</button></td>
      `;

      billTableBody.appendChild(newRow);

      // Clear form
      document.getElementById("billName").value = "";
      document.getElementById("billDueDate").value = "";
      document.getElementById("autoPay").checked = false;
    }
  });

  // Delete functionality
  billTableBody.addEventListener("click", (e) => {
    if (e.target.classList.contains("delete-bill-btn")) {
      const row = e.target.closest("tr");
      row.remove();
    }
  });
});




document.addEventListener("DOMContentLoaded", () => {
  const reportForm = document.getElementById("reportFilterForm");
  const exportBtn = document.getElementById("exportChartsBtn");

  const spendingTrendsCtx = document.getElementById("spendingTrendsChart").getContext("2d");
  const incomeExpenseCtx = document.getElementById("incomeExpenseChart").getContext("2d");
  const netWorthCtx = document.getElementById("netWorthChart").getContext("2d");

  // Initialize charts
  const spendingTrendsChart = new Chart(spendingTrendsCtx, {
    type: "line",
    data: { labels: [], datasets: [{ label: "Expenses", data: [], borderColor: "red", backgroundColor: "rgba(255,0,0,0.2)" }] }
  });

  const incomeExpenseChart = new Chart(incomeExpenseCtx, {
    type: "bar",
    data: { labels: [], datasets: [{ label: "Income", data: [], backgroundColor: "green" }, { label: "Expenses", data: [], backgroundColor: "red" }] }
  });

  const netWorthChart = new Chart(netWorthCtx, {
    type: "line",
    data: { labels: [], datasets: [{ label: "Net Worth", data: [], borderColor: "blue", backgroundColor: "rgba(0,0,255,0.2)" }] }
  });

  reportForm.addEventListener("submit", (e) => {
    e.preventDefault();

    // Read date range
    const startDateInput = document.getElementById("reportStartDate").value;
    const endDateInput = document.getElementById("reportEndDate").value;
    if (!startDateInput || !endDateInput) return alert("Please select valid dates.");

    const startDate = new Date(startDateInput);
    const endDate = new Date(endDateInput);

    // Get income data from table
    const incomeRows = document.querySelectorAll("#incomeTable tbody tr");
    const incomeData = [];
    incomeRows.forEach(row => {
      const amountText = row.children[1].textContent.replace('৳', '');
      const amount = parseFloat(amountText);
      incomeData.push({ amount, date: new Date() }); // Default today, can extend to input date
    });

    // Get expense data from table
    const expenseRows = document.querySelectorAll("#expenseTable tbody tr");
    const expenseData = [];
    expenseRows.forEach(row => {
      const amountText = row.children[1].textContent.replace('৳', '');
      const amount = parseFloat(amountText);
      expenseData.push({ amount, date: new Date() }); // Default today
    });

    // Aggregate daily data within date range
    const labels = [];
    const dailyIncome = [];
    const dailyExpense = [];
    const netWorth = [];
    let runningNetWorth = 0;

    const dayCount = Math.ceil((endDate - startDate) / (1000*60*60*24)) + 1;
    for (let i = 0; i < dayCount; i++) {
      const day = new Date(startDate);
      day.setDate(startDate.getDate() + i);
      const label = `${day.getFullYear()}-${day.getMonth()+1}-${day.getDate()}`;
      labels.push(label);

      // Sum income & expenses for this day (all set to today for now)
      const dailyInc = incomeData.reduce((sum, inc) => sum + inc.amount, 0);
      const dailyExp = expenseData.reduce((sum, exp) => sum + exp.amount, 0);

      dailyIncome.push(dailyInc);
      dailyExpense.push(dailyExp);

      runningNetWorth += (dailyInc - dailyExp);
      netWorth.push(runningNetWorth);
    }

    // Update charts
    spendingTrendsChart.data.labels = labels;
    spendingTrendsChart.data.datasets[0].data = dailyExpense;
    spendingTrendsChart.update();

    incomeExpenseChart.data.labels = labels;
    incomeExpenseChart.data.datasets[0].data = dailyIncome;
    incomeExpenseChart.data.datasets[1].data = dailyExpense;
    incomeExpenseChart.update();

    netWorthChart.data.labels = labels;
    netWorthChart.data.datasets[0].data = netWorth;
    netWorthChart.update();
  });

  exportBtn.addEventListener("click", () => {
    const charts = [spendingTrendsChart, incomeExpenseChart, netWorthChart];
    charts.forEach((chart, idx) => {
      const link = document.createElement("a");
      link.href = chart.toBase64Image();
      link.download = `chart_${idx+1}.png`;
      link.click();
    });
  });
});





