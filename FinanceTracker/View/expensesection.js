// Initialize the expense section
function initExpenseSection() {
    const saveBtn = document.getElementById('saveExpense');
    const form = document.getElementById('expenseForm');
    const historyTable = document.getElementById('expenseHistory');

    // Set current date as default
    const today = new Date().toISOString().split('T')[0];
    const expenseDateInput = document.getElementById('expenseDate');
    if (expenseDateInput) {
        expenseDateInput.value = today;
    }

    if (!saveBtn || !form) {
        console.error('Expense form elements not found');
        return false;
    }

    // Remove duplicate listeners
    const newSaveBtn = saveBtn.cloneNode(true);
    saveBtn.parentNode.replaceChild(newSaveBtn, saveBtn);

    // Add click listener
    document.getElementById('saveExpense').addEventListener('click', function () {
        const amount = parseFloat(document.getElementById('expenseAmount').value);
        const description = document.getElementById('expenseDescription').value.trim();
        const category = document.getElementById('expenseCategory').value;
        const date = document.getElementById('expenseDate').value;

        if (isNaN(amount) || amount <= 0 || !description || !category || !date) {
            showExpenseMessage('Please fill in all fields with valid values.', 'error');
            return;
        }

        // Update financeData
        window.financeData.totalExpenses += amount;
        window.financeData.balance = window.financeData.totalIncome - window.financeData.totalExpenses;
        window.financeData.transactions.push({
            type: 'expense',
            amount,
            description,
            category,
            date
        });

        // Update dashboard UI
        if (typeof updateFinanceUI === 'function') {
            updateFinanceUI();
        }

        // Add row to expense history
        if (historyTable) {
            if (historyTable.rows.length === 1 && historyTable.rows[0].cells[0].colSpan > 1) {
                historyTable.deleteRow(0);
            }

            const newRow = historyTable.insertRow(0);
            newRow.innerHTML = `
                <td>${new Date(date).toLocaleDateString()}</td>
                <td>${description}</td>
                <td><span class="badge bg-secondary">${category}</span></td>
                <td class="text-danger fw-bold">-$${amount.toFixed(2)}</td>
            `;
        }

        // Update count badge (if you add one for expenses)
        updateExpenseCount();

        form.reset();
        document.getElementById('expenseDate').value = new Date().toISOString().split('T')[0];

        showExpenseMessage('Expense added successfully!', 'success');
    });

    return true;
}

function updateExpenseHistory() {
    const historyTable = document.getElementById('expenseHistory');
    if (!historyTable) return;

    while (historyTable.rows.length > 0) {
        historyTable.deleteRow(0);
    }

    const expenseTransactions = window.financeData.transactions.filter(t => t.type === 'expense');

    if (expenseTransactions.length === 0) {
        const row = historyTable.insertRow(0);
        row.innerHTML = '<td colspan="4" class="text-center py-3">No expense transactions</td>';
        return;
    }

    const sortedTransactions = [...expenseTransactions].sort((a, b) => new Date(b.date) - new Date(a.date));

    sortedTransactions.forEach(transaction => {
        const newRow = historyTable.insertRow();
        newRow.innerHTML = `
            <td>${new Date(transaction.date).toLocaleDateString()}</td>
            <td>${transaction.description}</td>
            <td><span class="badge bg-secondary">${transaction.category}</span></td>
            <td class="text-danger fw-bold">-$${transaction.amount.toFixed(2)}</td>
        `;
    });

    updateExpenseCount();
}

function updateExpenseCount() {
    const expenseCountBadge = document.getElementById('expenseCount');
    if (expenseCountBadge) {
        const expenseTransactions = window.financeData.transactions.filter(t => t.type === 'expense');
        expenseCountBadge.textContent = expenseTransactions.length;
    }
}

function showExpenseMessage(message, type) {
    const messageDiv = document.getElementById('expenseMessage');
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
    initExpenseSection();
    const expenseSection = document.getElementById('expenses-section');
    if (expenseSection && expenseSection.classList.contains('active-content-section')) {
        updateExpenseHistory();
    }
});

// Listen for section changes
document.addEventListener('sectionChanged', function(e) {
    if (e.detail && e.detail.section === 'expenses-section') {
        initExpenseSection();
        updateExpenseHistory();
    }
});

// Fallback: check periodically
let expenseCheckInterval = setInterval(function() {
    const expenseSection = document.getElementById('expenses-section');
    if (expenseSection && expenseSection.classList.contains('active-content-section')) {
        initExpenseSection();
        updateExpenseHistory();
        clearInterval(expenseCheckInterval);
    }
}, 500);

setTimeout(() => {
    clearInterval(expenseCheckInterval);
}, 10000);
