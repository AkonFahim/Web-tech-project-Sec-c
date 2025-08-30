// Dashboard Section JavaScript
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    setupEventListeners();
});

// Initialize Charts
function initializeCharts() {
    // Income vs Expense Chart
    const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
    new Chart(incomeExpenseCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [
                {
                    label: 'Income',
                    data: [3200, 3500, 4000, 3800, 4200, 4500, 4800],
                    backgroundColor: '#38A169',
                    borderRadius: 6
                },
                {
                    label: 'Expenses',
                    data: [2400, 2700, 3000, 2900, 3200, 3400, 3700],
                    backgroundColor: '#E53E3E',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Spending by Category Chart
    const spendingCategoryCtx = document.getElementById('spendingCategoryChart').getContext('2d');
    new Chart(spendingCategoryCtx, {
        type: 'doughnut',
        data: {
            labels: ['Housing', 'Food', 'Transport', 'Utilities', 'Entertainment'],
            datasets: [{
                data: [35, 20, 15, 12, 18],
                backgroundColor: [
                    '#805AD5',
                    '#38A169',
                    '#3182CE',
                    '#DD6B20',
                    '#D53F8C'
                ],
                borderWidth: 0,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            },
            cutout: '70%'
        }
    });
}

// Setup Event Listeners
function setupEventListeners() {
    // View All Transactions Button
    const viewAllButtons = document.querySelectorAll('.view-all-button');
    viewAllButtons.forEach(button => {
        button.addEventListener('click', function() {
            alert('Redirecting to full transactions page...');
            // In a real application, this would navigate to the transactions page
        });
    });
    
    // Manage Budget Button
    const manageBudgetButton = document.querySelector('.manage-budget-button');
    if (manageBudgetButton) {
        manageBudgetButton.addEventListener('click', function() {
            alert('Redirecting to budget management page...');
            // In a real application, this would navigate to the budget management page
        });
    }
    
    // Chart Period Selector
    const periodSelectors = document.querySelectorAll('.chart-period-selector');
    periodSelectors.forEach(selector => {
        selector.addEventListener('change', function() {
            alert(`Loading data for ${this.value}...`);
            // In a real application, this would reload chart data based on the selected period
        });
    });
    
    // Summary Card Click Events
    const summaryCards = document.querySelectorAll('.summary-card');
    summaryCards.forEach(card => {
        card.addEventListener('click', function() {
            const cardLabel = this.querySelector('.card-label').textContent;
            alert(`Viewing details for ${cardLabel}`);
            // In a real application, this would show detailed information for the selected metric
        });
    });
}

// Function to update dashboard data (would be called periodically or on user action)
function refreshDashboardData() {
    console.log('Refreshing dashboard data...');
    // In a real application, this would fetch updated data from the server
    // and update the charts and UI elements accordingly
}

// Export function to be called from other modules if needed
window.dashboardSection = {
    refreshData: refreshDashboardData
};