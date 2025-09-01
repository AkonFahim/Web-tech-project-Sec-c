// dashboardsection.js
function initDashboardCharts() {
    // Income vs Expenses Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
    if (incomeExpenseCtx) {
        const incomeExpenseChart = new Chart(incomeExpenseCtx.getContext('2d'), {
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
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    // Spending by Category Chart
    const spendingCategoryCtx = document.getElementById('spendingCategoryChart');
    if (spendingCategoryCtx) {
        const spendingCategoryChart = new Chart(spendingCategoryCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Housing', 'Food', 'Transportation', 'Entertainment', 'Utilities', 'Other'],
                datasets: [{
                    data: [1200, 600, 300, 200, 250, 200],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#e74c3c',
                        '#f39c12',
                        '#9b59b6',
                        '#34495e'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }
}

function loadRecentTransactions() {
    const transactionsList = document.getElementById('recentTransactionsList');
    if (!transactionsList) return;
    
    // Get transactions from localStorage
    const incomes = JSON.parse(localStorage.getItem('incomes')) || [];
    const expenses = JSON.parse(localStorage.getItem('expenses')) || [];
    
    // Combine and sort transactions by date (newest first)
    const allTransactions = [
        ...incomes.map(income => ({...income, type: 'income'})),
        ...expenses.map(expense => ({...expense, type: 'expense'}))
    ].sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 5); // Get latest 5 transactions
    
    // Clear the "no transactions" message
    transactionsList.innerHTML = '';
    
    if (allTransactions.length === 0) {
        transactionsList.innerHTML = '<p class="finance-no-transactions-text">No recent transactions</p>';
        return;
    }
    
    // Add transactions to the list
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