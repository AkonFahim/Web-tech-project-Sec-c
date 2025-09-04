function initDashboardCharts() {
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
    if (incomeExpenseCtx) {
        new Chart(incomeExpenseCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [
                    {
                        label: 'Income',
                        data: [3000, 3200, 2800, 3500, 4000, 3800],
                        backgroundColor: '#2ecc71',
                    },
                    {
                        label: 'Expenses',
                        data: [2200, 2400, 2600, 2300, 2800, 2500],
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
                    data: [1200, 600, 300, 200, 250, 200],
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

function loadRecentTransactions() {
    const transactionsList = document.getElementById('recentTransactionsList');
    if (!transactionsList) return;

    const allTransactions = [
        ...incomeTransactions.map(i => ({ ...i, type: 'income' })),
        ...expenseTransactions.map(e => ({ ...e, type: 'expense' }))
    ].sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5);

    transactionsList.innerHTML = '';

    if (allTransactions.length === 0) {
        transactionsList.innerHTML = '<p class="finance-no-transactions-text">No recent transactions</p>';
        return;
    }

    allTransactions.forEach(transaction => {
        const transactionElement = document.createElement('div');
        transactionElement.className = `finance-transaction-item ${transaction.type}`;

        transactionElement.innerHTML = `
            <div class="finance-transaction-icon">
                <i class="fas ${transaction.type === 'income' ? 'fa-arrow-down' : 'fa-arrow-up'}"></i>
            </div>
            <div class="finance-transaction-details">
                <h6>${transaction.description}</h6>
                <p>${transaction.category} • ${new Date(transaction.date).toLocaleDateString()}</p>
            </div>
            <div class="finance-transaction-amount ${transaction.type}">
                ${transaction.type === 'income' ? '+' : '-'}$${transaction.amount.toFixed(2)}
            </div>
        `;

        transactionsList.appendChild(transactionElement);
    });
}

function showDeleteConfirmation(id, type) {
    const overlay = document.createElement('div');
    overlay.className = 'finance-delete-modal-overlay';

    const modal = document.createElement('div');
    modal.className = 'finance-delete-modal';
    modal.innerHTML = `
        <div class="finance-delete-modal-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this ${type} transaction? This action cannot be undone.</p>
            <div class="finance-delete-modal-buttons">
                <button class="finance-cancel-delete-btn">Cancel</button>
                <button class="finance-confirm-delete-btn">Delete</button>
            </div>
        </div>
    `;

    modal.querySelector('.finance-cancel-delete-btn').addEventListener('click', () => {
        document.body.removeChild(overlay);
    });

    modal.querySelector('.finance-confirm-delete-btn').addEventListener('click', () => {
        if (type === 'income') deleteIncome(id);
        else if (type === 'expense') deleteExpense(id);
        document.body.removeChild(overlay);
    });

    overlay.appendChild(modal);
    document.body.appendChild(overlay);
}

function updateDashboardTotals(incomeAmount, expenseAmount) {
    const totalIncomeDisplay = document.getElementById('totalIncomeDisplay');
    const totalExpensesDisplay = document.getElementById('totalExpensesDisplay');
    const balanceDisplay = document.getElementById('balanceDisplay');

    let totalIncome = incomeTransactions.reduce((sum, i) => sum + i.amount, 0);
    let totalExpenses = expenseTransactions.reduce((sum, e) => sum + e.amount, 0);

    totalIncome += incomeAmount;
    totalExpenses += expenseAmount;
    const balance = totalIncome - totalExpenses;

    totalIncomeDisplay.textContent = `$${totalIncome.toFixed(2)}`;
    totalExpensesDisplay.textContent = `$${totalExpenses.toFixed(2)}`;
    balanceDisplay.textContent = `$${balance.toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', () => {
    initDashboardCharts();
    loadRecentTransactions();
});
