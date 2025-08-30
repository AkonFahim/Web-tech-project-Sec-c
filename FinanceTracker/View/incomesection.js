// Initialize the income section
function initIncomeSection() {
    const saveBtn = document.getElementById('saveIncome');
    const form = document.getElementById('incomeForm');
    const historyTable = document.getElementById('incomeHistory');

    // Set current date as default
    const today = new Date().toISOString().split('T')[0];
    const incomeDateInput = document.getElementById('incomeDate');
    if (incomeDateInput) {
        incomeDateInput.value = today;
    }

    if (!saveBtn || !form) {
        console.error('Income form elements not found');
        return false;
    }

    // Remove any existing event listeners to prevent duplicates
    const newSaveBtn = saveBtn.cloneNode(true);
    saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);

    // Add new event listener
    document.getElementById('saveIncome').addEventListener('click', function () {
        const amount = parseFloat(document.getElementById('incomeAmount').value);
        const description = document.getElementById('incomeDescription').value.trim();
        const category = document.getElementById('incomeCategory').value;
        const date = document.getElementById('incomeDate').value;

        if (isNaN(amount) || amount <= 0 || !description || !category || !date) {
            showIncomeMessage('Please fill in all fields with valid values.', 'error');
            return;
        }

        // Update financeData
        window.financeData.totalIncome += amount;
        window.financeData.balance = window.financeData.totalIncome - window.financeData.totalExpenses;
        window.financeData.transactions.push({
            type: 'income',
            amount,
            description,
            category,
            date
        });

        // Update dashboard UI
        if (typeof updateFinanceUI === 'function') {
            updateFinanceUI();
        }

        // Add row to income history
        if (historyTable) {
            // Clear "no transactions" message if it exists
            if (historyTable.rows.length === 1 && historyTable.rows[0].cells[0].colSpan > 1) {
                historyTable.deleteRow(0);
            }
            
            const newRow = historyTable.insertRow(0);
            newRow.innerHTML = `
                <td>${new Date(date).toLocaleDateString()}</td>
                <td>${description}</td>
                <td><span class="badge bg-secondary">${category}</span></td>
                <td class="text-success fw-bold">+$${amount.toFixed(2)}</td>
            `;
        }

        // Update count badge
        updateIncomeCount();

        form.reset();
        document.getElementById('incomeDate').value = new Date().toISOString().split('T')[0];

        showIncomeMessage('Income added successfully!', 'success');
    });

    return true;
}

function updateIncomeHistory() {
    const historyTable = document.getElementById('incomeHistory');
    if (!historyTable) return;

    while (historyTable.rows.length > 0) {
        historyTable.deleteRow(0);
    }

    const incomeTransactions = window.financeData.transactions.filter(t => t.type === 'income');

    if (incomeTransactions.length === 0) {
        const row = historyTable.insertRow(0);
        row.innerHTML = '<td colspan="4" class="text-center py-3">No income transactions</td>';
        return;
    }

    const sortedTransactions = [...incomeTransactions].sort((a, b) => new Date(b.date) - new Date(a.date));

    sortedTransactions.forEach(transaction => {
        const newRow = historyTable.insertRow();
        newRow.innerHTML = `
            <td>${new Date(transaction.date).toLocaleDateString()}</td>
            <td>${transaction.description}</td>
            <td><span class="badge bg-secondary">${transaction.category}</span></td>
            <td class="text-success fw-bold">+$${transaction.amount.toFixed(2)}</td>
        `;
    });

    updateIncomeCount();
}

function updateIncomeCount() {
    const incomeCountBadge = document.getElementById('incomeCount');
    if (incomeCountBadge) {
        const incomeTransactions = window.financeData.transactions.filter(t => t.type === 'income');
        incomeCountBadge.textContent = incomeTransactions.length;
    }
}

function showIncomeMessage(message, type) {
    const messageDiv = document.getElementById('incomeMessage');
    if (!messageDiv) return;

    messageDiv.innerHTML = `
        <div class="alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    setTimeout(() => {
        const alert = messageDiv.querySelector('.alert');
        if (alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }
    }, 3000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize income section regardless of active state
    initIncomeSection();
    
    // Update income history if we're on the income section
    const incomeSection = document.getElementById('income-section');
    if (incomeSection && incomeSection.classList.contains('active-content-section')) {
        updateIncomeHistory();
    }
});

// Listen for section changes
document.addEventListener('sectionChanged', function(e) {
    if (e.detail && e.detail.section === 'income-section') {
        // Reinitialize the income section when it becomes active
        initIncomeSection();
        updateIncomeHistory();
    }
});

// Fallback: Check periodically if income section becomes active
let incomeCheckInterval = setInterval(function() {
    const incomeSection = document.getElementById('income-section');
    if (incomeSection && incomeSection.classList.contains('active-content-section')) {
        initIncomeSection();
        updateIncomeHistory();
        clearInterval(incomeCheckInterval);
    }
}, 500);

// Stop checking after 10 seconds
setTimeout(() => {
    clearInterval(incomeCheckInterval);
}, 10000);