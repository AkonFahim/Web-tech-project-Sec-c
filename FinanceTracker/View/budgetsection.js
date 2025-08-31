// budgetsection.js

document.addEventListener("DOMContentLoaded", () => {
  const budgetForm = document.getElementById("budgetForm");
  const budgetTableBody = document.querySelector("#budgetTable tbody");

  // Initialize budgets from session data if available
  if (!window.financeData.budgets) {
    window.financeData.budgets = [];
  } else {
    window.financeData.budgets.forEach(budget => addBudgetRow(budget));
  }

  // Handle form submit
  budgetForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const category = document.getElementById("budgetCategory").value.trim();
    const amount = document.getElementById("budgetAmount").value.trim();

    if (category && amount) {
      const budget = { category, amount: parseFloat(amount) };

      window.financeData.budgets.push(budget);
      addBudgetRow(budget);

      budgetForm.reset();
    }
  });

  // Function to add a row in the table
  function addBudgetRow(budget) {
    const row = document.createElement("tr");

    row.innerHTML = `
      <td>${budget.category}</td>
      <td>${budget.amount.toFixed(2)}</td>
      <td><button class="btn btn-danger btn-sm delete-budget">Delete</button></td>
    `;

    // Delete handler
    row.querySelector(".delete-budget").addEventListener("click", () => {
      row.remove();
      window.financeData.budgets = window.financeData.budgets.filter(
        (b) => !(b.category === budget.category && b.amount === budget.amount)
      );
    });

    budgetTableBody.appendChild(row);
  }
});
