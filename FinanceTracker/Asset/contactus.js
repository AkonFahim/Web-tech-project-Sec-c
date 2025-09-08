
const incomeTransactions = [];  // In-memory storage for income transactions
const expenseTransactions = []; // In-memory storage for expense transactions


// ------------------- Income Section -------------------
document.addEventListener('DOMContentLoaded', () => {
    const saveIncomeButton = document.getElementById('saveIncome');
    const incomeForm = document.getElementById('incomeForm');
    const incomeMessage = document.getElementById('incomeMessage');
    const incomeHistory = document.getElementById('incomeHistory');

    // Add event listener to "Add Income" button
    saveIncomeButton.addEventListener('click', function() {
        if (!incomeForm.checkValidity()) {
            incomeForm.reportValidity();
            return;
        }

        const amount = parseFloat(document.getElementById('incomeAmount').value);
        const description = document.getElementById('incomeDescription').value;
        const category = document.getElementById('incomeCategory').value;
        const date = document.getElementById('incomeDate').value;

        const income = { id: Date.now().toString(), amount, description, category, date };
        incomeTransactions.push(income);

        incomeMessage.textContent = 'Income added successfully!';
        incomeMessage.className = 'finance-income-message success';

        incomeForm.reset();
        loadIncomeHistory();
        updateDashboardTotals();
        setTimeout(() => {
            incomeMessage.textContent = '';
            incomeMessage.className = 'finance-income-message';
        }, 3000);
    });

    function loadIncomeHistory() {
        incomeHistory.innerHTML = '';

        if (incomeTransactions.length === 0) {
            incomeHistory.innerHTML = '<tr><td colspan="5">No income records found</td></tr>';
            return;
        }

        incomeTransactions.forEach(income => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(income.date).toLocaleDateString()}</td>
                <td>${income.description}</td>
                <td>${income.category}</td>
                <td>$${income.amount.toFixed(2)}</td>
                <td>
                    <button class="finance-delete-btn" data-id="${income.id}">Delete</button>
                </td>
            `;
            incomeHistory.appendChild(row);
        });

        document.querySelectorAll('.finance-delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                deleteIncome(e.target.getAttribute('data-id'));
            });
        });
    }

    function deleteIncome(id) {
        const index = incomeTransactions.findIndex(i => i.id == id);
        if (index !== -1) {
            incomeTransactions.splice(index, 1);  // Remove transaction
            loadIncomeHistory();  // Reload the history table
            updateDashboardTotals();  // Update totals after deleting
        }
    }

    function updateDashboardTotals() {
        const totalIncome = incomeTransactions.reduce((sum, transaction) => sum + transaction.amount, 0);
        const totalExpenses = expenseTransactions.reduce((sum, transaction) => sum + transaction.amount, 0);
        const balance = totalIncome - totalExpenses;

        document.getElementById('totalIncomeDisplay').textContent = `$${totalIncome.toFixed(2)}`;
        document.getElementById('totalExpensesDisplay').textContent = `$${totalExpenses.toFixed(2)}`;
        document.getElementById('balanceDisplay').textContent = `$${balance.toFixed(2)}`;

        updateCharts(totalIncome, totalExpenses);
    }


});

// ------------------- Expense Section -------------------
document.addEventListener('DOMContentLoaded', () => {
    const saveExpenseButton = document.getElementById('saveExpense');
    const expenseForm = document.getElementById('expenseForm');
    const expenseMessage = document.getElementById('expenseMessage');
    const expenseHistory = document.getElementById('expenseHistory');

    // Add event listener to "Add Expense" button
    saveExpenseButton.addEventListener('click', function() {
        if (!expenseForm.checkValidity()) {
            expenseForm.reportValidity();
            return;
        }

        const amount = parseFloat(document.getElementById('expenseAmount').value);
        const description = document.getElementById('expenseDescription').value;
        const category = document.getElementById('expenseCategory').value;
        const date = document.getElementById('expenseDate').value;

        const expense = { id: Date.now().toString(), amount, description, category, date };
        expenseTransactions.push(expense);

        expenseMessage.textContent = 'Expense added successfully!';
        expenseMessage.className = 'finance-expense-message success';

        expenseForm.reset();
        loadExpenseHistory();
        updateDashboardTotals();
        setTimeout(() => {
            expenseMessage.textContent = '';
            expenseMessage.className = 'finance-expense-message';
        }, 3000);
    });

    function loadExpenseHistory() {
        expenseHistory.innerHTML = '';

        if (expenseTransactions.length === 0) {
            expenseHistory.innerHTML = '<tr><td colspan="5">No expense records found</td></tr>';
            return;
        }

        expenseTransactions.forEach(expense => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(expense.date).toLocaleDateString()}</td>
                <td>${expense.description}</td>
                <td>${expense.category}</td>
                <td>$${expense.amount.toFixed(2)}</td>
                <td>
                    <button class="finance-delete-btn" data-id="${expense.id}">Delete</button>
                </td>
            `;
            expenseHistory.appendChild(row);
        });

        document.querySelectorAll('.finance-delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                deleteExpense(e.target.getAttribute('data-id'));
            });
        });
    }

    function deleteExpense(id) {
        const index = expenseTransactions.findIndex(e => e.id == id);
        if (index !== -1) {
            expenseTransactions.splice(index, 1);  // Remove transaction
            loadExpenseHistory();  // Reload the history table
            updateDashboardTotals();  // Update totals after deleting
        }
    }

    function updateDashboardTotals() {
        const totalIncome = incomeTransactions.reduce((sum, transaction) => sum + transaction.amount, 0);
        const totalExpenses = expenseTransactions.reduce((sum, transaction) => sum + transaction.amount, 0);
        const balance = totalIncome - totalExpenses;

        document.getElementById('totalIncomeDisplay').textContent = `$${totalIncome.toFixed(2)}`;
        document.getElementById('totalExpensesDisplay').textContent = `$${totalExpenses.toFixed(2)}`;
        document.getElementById('balanceDisplay').textContent = `$${balance.toFixed(2)}`;

        updateCharts(totalIncome, totalExpenses);
    }

    function updateCharts(totalIncome, totalExpenses) {
        const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
        if (incomeExpenseCtx) {
            new Chart(incomeExpenseCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Current'],
                    datasets: [
                        {
                            label: 'Income',
                            data: [totalIncome],
                            backgroundColor: '#2ecc71',
                        },
                        {
                            label: 'Expenses',
                            data: [totalExpenses],
                            backgroundColor: '#e74c3c',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } }
                }
            });
        }

        const spendingCategoryCtx = document.getElementById('spendingCategoryChart');
        if (spendingCategoryCtx) {
            new Chart(spendingCategoryCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Housing', 'Food', 'Transportation', 'Entertainment', 'Utilities', 'Other'],
                    datasets: [{
                        data: [300, 500, 200, 100, 150, 50],  // Dummy data
                        backgroundColor: [
                            '#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6', '#34495e'
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'right' } }
                }
            });
        }
    }
});
