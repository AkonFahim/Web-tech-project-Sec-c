// ------------------- In-Memory Expense Transactions -------------------
const expenseTransactions = [];

// ------------------- Expense Section -------------------
function initExpenseSection() {
    const saveExpenseButton = document.getElementById('saveExpense');
    const expenseForm = document.getElementById('expenseForm');
    const expenseMessage = document.getElementById('expenseMessage');

    const expenseDateField = document.getElementById('expenseDate');
    if (!expenseDateField.value) expenseDateField.valueAsDate = new Date();

    loadExpenseHistory();

    if (saveExpenseButton && !saveExpenseButton.hasListener) {
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
            expenseDateField.valueAsDate = new Date();

            loadExpenseHistory();
            updateDashboardTotals(0, amount);
            loadRecentTransactions();

            setTimeout(() => {
                expenseMessage.textContent = '';
                expenseMessage.className = 'finance-expense-message';
            }, 3000);
        });

        saveExpenseButton.hasListener = true;
    }
}

function loadExpenseHistory() {
    const expenseHistory = document.getElementById('expenseHistory');
    expenseHistory.innerHTML = '';

    if (expenseTransactions.length === 0) {
        expenseHistory.innerHTML = '<tr><td colspan="5" class="finance-no-data">No expense records found</td></tr>';
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
                <button class="finance-delete-btn" data-id="${expense.id}" data-type="expense">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        expenseHistory.appendChild(row);
    });

    document.querySelectorAll('.finance-delete-btn[data-type="expense"]').forEach(button => {
        button.addEventListener('click', function() {
            showDeleteConfirmation(this.getAttribute('data-id'), 'expense');
        });
    });
}

function deleteExpense(id) {
    const index = expenseTransactions.findIndex(e => e.id === id);
    if (index !== -1) {
        const [deleted] = expenseTransactions.splice(index, 1);
        updateDashboardTotals(0, -deleted.amount);
        loadExpenseHistory();
        loadRecentTransactions();
    }
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', initExpenseSection);
