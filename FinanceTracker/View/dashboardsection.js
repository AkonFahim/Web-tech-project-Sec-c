// Initialize the dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Initialize financeData if not set
    if (typeof window.financeData === 'undefined') {
        window.financeData = {
            balance: 0,
            totalIncome: 0,
            totalExpenses: 0,
            transactions: [],
            lastMonthData: { income: 0, expenses: 0 }
        };
    }

    initDashboard();
    updateFinanceUI();
    initCharts();
});

function initDashboard() {
    const today = new Date().toISOString().split('T')[0];
    if (document.getElementById('incomeDate')) {
        document.getElementById('incomeDate').value = today;
    }
    if (document.getElementById('expenseDate')) {
        document.getElementById('expenseDate').value = today;
    }
}

function updateFinanceUI() {
    const totalIncomeEl = document.getElementById('totalIncomeDisplay');
    const totalExpensesEl = document.getElementById('totalExpensesDisplay');
    const savingsEl = document.getElementById('savingsDisplay');

    if (totalIncomeEl) totalIncomeEl.textContent = '$' + window.financeData.totalIncome.toFixed(2);
    if (totalExpensesEl) totalExpensesEl.textContent = '$' + window.financeData.totalExpenses.toFixed(2);
    if (savingsEl) savingsEl.textContent = '$' + (window.financeData.totalIncome - window.financeData.totalExpenses).toFixed(2);

    updateRecentTransactions();
}

function updateRecentTransactions() {
    const container = document.getElementById('recentTransactionsList');
    if (!container) return;

    container.innerHTML = '';
    
    if (!window.financeData.transactions || window.financeData.transactions.length === 0) {
        container.innerHTML = '<p class="text-muted text-center py-3">No recent transactions</p>';
        return;
    }

    // Sort transactions by date descending
    const sorted = [...window.financeData.transactions].sort((a, b) => new Date(b.date) - new Date(a.date));
    const recent = sorted.slice(0, 5); // Show 5 latest transactions

    recent.forEach(transaction => {
        const isIncome = transaction.type === 'income';
        const row = document.createElement('div');
        row.className = 'transaction-item';
        const sign = isIncome ? '+' : '-';
        const textClass = isIncome ? 'credit' : 'debit';

        row.innerHTML = `
            <div class="transaction-info">
                <div class="transaction-category">
                    <i class="fas ${isIncome ? 'fa-money-bill-wave' : 'fa-credit-card'}"></i>
                </div>
                <div class="transaction-details">
                    <div class="transaction-name">${transaction.description}</div>
                    <div class="transaction-date">${formatDate(transaction.date)}</div>
                </div>
            </div>
            <div class="transaction-amount ${textClass}">${sign}$${transaction.amount.toFixed(2)}</div>
        `;
        container.appendChild(row);
    });
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function initCharts() {
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
    if (incomeExpenseCtx) {
        new Chart(incomeExpenseCtx, {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun'],
                datasets: [
                    { 
                        label: 'Income', 
                        data: [3200,3500,4000,3800,4200,4850], 
                        backgroundColor:'rgba(40,167,69,0.7)', 
                        borderColor:'rgba(40,167,69,1)', 
                        borderWidth:1 
                    },
                    { 
                        label: 'Expenses', 
                        data:[2800,2950,3100,2700,2850,2645], 
                        backgroundColor:'rgba(220,53,69,0.7)', 
                        borderColor:'rgba(220,53,69,1)', 
                        borderWidth:1 
                    }
                ]
            },
            options: { 
                responsive: true, 
                scales: { 
                    y: { 
                        beginAtZero: true 
                    } 
                } 
            }
        });
    }

    const spendingCategoryCtx = document.getElementById('spendingCategoryChart');
    if (spendingCategoryCtx) {
        new Chart(spendingCategoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Food','Housing','Utilities','Entertainment','Transportation','Healthcare'],
                datasets: [{
                    data: [800,1200,350,300,250,150], 
                    backgroundColor: [
                        'rgba(40,167,69,0.7)',
                        'rgba(0,123,255,0.7)',
                        'rgba(108,117,125,0.7)',
                        'rgba(255,193,7,0.7)',
                        'rgba(23,162,184,0.7)',
                        'rgba(220,53,69,0.7)'
                    ], 
                    borderWidth: 1 
                }]
            },
            options: { 
                responsive: true, 
                plugins: { 
                    legend: { 
                        position: 'right' 
                    } 
                } 
            }
        });
    }
}

