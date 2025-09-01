// incomesection.js
function initIncomeSection() {
    const saveIncomeButton = document.getElementById('saveIncome');
    const incomeForm = document.getElementById('incomeForm');
    const incomeMessage = document.getElementById('incomeMessage');
    
    // Set default date to today if not already set
    const incomeDateField = document.getElementById('incomeDate');
    if (!incomeDateField.value) {
        incomeDateField.valueAsDate = new Date();
    }
    
    // Load income history
    loadIncomeHistory();
    
    // Add event listener for save income button if not already added
    if (saveIncomeButton && !saveIncomeButton.hasListener) {
        saveIncomeButton.addEventListener('click', function() {
            // Validate form
            if (!incomeForm.checkValidity()) {
                incomeForm.reportValidity();
                return;
            }
            
            // Get form values
            const amount = parseFloat(document.getElementById('incomeAmount').value);
            const description = document.getElementById('incomeDescription').value;
            const category = document.getElementById('incomeCategory').value;
            const date = document.getElementById('incomeDate').value;
            
            // Create income object with unique ID
            const income = {
                id: Date.now().toString(), // Unique ID for deletion
                amount,
                description,
                category,
                date
            };
            
            // Save income
            saveIncome(income);
            
            // Show success message
            incomeMessage.textContent = 'Income added successfully!';
            incomeMessage.className = 'finance-income-message success';
            
            // Reset form
            incomeForm.reset();
            document.getElementById('incomeDate').valueAsDate = new Date();
            
            // Reload income history
            loadIncomeHistory();
            
            // Update dashboard totals
            updateDashboardTotals(amount, 0);
            
            // Hide message after 3 seconds
            setTimeout(() => {
                incomeMessage.className = 'finance-income-message';
                incomeMessage.textContent = '';
            }, 3000);
        });
        
        // Mark that we've added the listener
        saveIncomeButton.hasListener = true;
    }
}

function saveIncome(income) {
    // Store in localStorage
    let incomes = JSON.parse(localStorage.getItem('incomes')) || [];
    incomes.push(income);
    localStorage.setItem('incomes', JSON.stringify(incomes));
}

function loadIncomeHistory() {
    const incomeHistory = document.getElementById('incomeHistory');
    const incomes = JSON.parse(localStorage.getItem('incomes')) || [];
    
    // Clear the table
    incomeHistory.innerHTML = '';
    
    if (incomes.length === 0) {
        incomeHistory.innerHTML = '<tr><td colspan="5" class="finance-no-data">No income records found</td></tr>';
        return;
    }
    
    // Add income records to the table
    incomes.forEach(income => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${new Date(income.date).toLocaleDateString()}</td>
            <td>${income.description}</td>
            <td>${income.category}</td>
            <td>$${income.amount.toFixed(2)}</td>
            <td>
                <button class="finance-delete-btn" data-id="${income.id}" data-type="income">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        incomeHistory.appendChild(row);
    });
    
    // Add event listeners to delete buttons
    document.querySelectorAll('.finance-delete-btn[data-type="income"]').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showDeleteConfirmation(id, 'income');
        });
    });
}

function deleteIncome(id) {
    let incomes = JSON.parse(localStorage.getItem('incomes')) || [];
    const incomeIndex = incomes.findIndex(income => income.id === id);
    
    if (incomeIndex !== -1) {
        const deletedIncome = incomes[incomeIndex];
        incomes.splice(incomeIndex, 1);
        localStorage.setItem('incomes', JSON.stringify(incomes));
        
        // Update dashboard totals (subtract the deleted income)
        updateDashboardTotals(-deletedIncome.amount, 0);
        
        // Reload income history
        loadIncomeHistory();
    }
}

// expensesection.js
function initExpenseSection() {
    const saveExpenseButton = document.getElementById('saveExpense');
    const expenseForm = document.getElementById('expenseForm');
    const expenseMessage = document.getElementById('expenseMessage');
    
    // Set default date to today if not already set
    const expenseDateField = document.getElementById('expenseDate');
    if (!expenseDateField.value) {
        expenseDateField.valueAsDate = new Date();
    }
    
    // Load expense history
    loadExpenseHistory();
    
    // Add event listener for save expense button if not already added
    if (saveExpenseButton && !saveExpenseButton.hasListener) {
        saveExpenseButton.addEventListener('click', function() {
            // Validate form
            if (!expenseForm.checkValidity()) {
                expenseForm.reportValidity();
                return;
            }
            
            // Get form values
            const amount = parseFloat(document.getElementById('expenseAmount').value);
            const description = document.getElementById('expenseDescription').value;
            const category = document.getElementById('expenseCategory').value;
            const date = document.getElementById('expenseDate').value;
            
            // Create expense object with unique ID
            const expense = {
                id: Date.now().toString(), // Unique ID for deletion
                amount,
                description,
                category,
                date
            };
            
            // Save expense
            saveExpense(expense);
            
            // Show success message
            expenseMessage.textContent = 'Expense added successfully!';
            expenseMessage.className = 'finance-expense-message success';
            
            // Reset form
            expenseForm.reset();
            document.getElementById('expenseDate').valueAsDate = new Date();
            
            // Reload expense history
            loadExpenseHistory();
            
            // Update dashboard totals
            updateDashboardTotals(0, amount);
            
            // Hide message after 3 seconds
            setTimeout(() => {
                expenseMessage.className = 'finance-expense-message';
                expenseMessage.textContent = '';
            }, 3000);
        });
        
        // Mark that we've added the listener
        saveExpenseButton.hasListener = true;
    }
}

function saveExpense(expense) {
    // Store in localStorage
    let expenses = JSON.parse(localStorage.getItem('expenses')) || [];
    expenses.push(expense);
    localStorage.setItem('expenses', JSON.stringify(expenses));
}

function loadExpenseHistory() {
    const expenseHistory = document.getElementById('expenseHistory');
    const expenses = JSON.parse(localStorage.getItem('expenses')) || [];
    
    // Clear the table
    expenseHistory.innerHTML = '';
    
    if (expenses.length === 0) {
        expenseHistory.innerHTML = '<tr><td colspan="5" class="finance-no-data">No expense records found</td></tr>';
        return;
    }
    
    // Add expense records to the table
    expenses.forEach(expense => {
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
    
    // Add event listeners to delete buttons
    document.querySelectorAll('.finance-delete-btn[data-type="expense"]').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            showDeleteConfirmation(id, 'expense');
        });
    });
}

function deleteExpense(id) {
    let expenses = JSON.parse(localStorage.getItem('expenses')) || [];
    const expenseIndex = expenses.findIndex(expense => expense.id === id);
    
    if (expenseIndex !== -1) {
        const deletedExpense = expenses[expenseIndex];
        expenses.splice(expenseIndex, 1);
        localStorage.setItem('expenses', JSON.stringify(expenses));
        
        // Update dashboard totals (add back the deleted expense)
        updateDashboardTotals(0, -deletedExpense.amount);
        
        // Reload expense history
        loadExpenseHistory();
    }
}

// Delete confirmation modal
function showDeleteConfirmation(id, type) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.className = 'finance-delete-modal-overlay';
    
    // Create modal content
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
    
    // Add event listeners
    modal.querySelector('.finance-cancel-delete-btn').addEventListener('click', function() {
        document.body.removeChild(overlay);
    });
    
    modal.querySelector('.finance-confirm-delete-btn').addEventListener('click', function() {
        if (type === 'income') {
            deleteIncome(id);
        } else if (type === 'expense') {
            deleteExpense(id);
        }
        document.body.removeChild(overlay);
    });
    
    // Add to DOM
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
}

function updateDashboardTotals(incomeAmount, expenseAmount) {
    // Update the dashboard totals
    const totalIncomeDisplay = document.getElementById('totalIncomeDisplay');
    const totalExpensesDisplay = document.getElementById('totalExpensesDisplay');
    const balanceDisplay = document.getElementById('balanceDisplay');
    
    // Get current values
    let totalIncome = parseFloat(totalIncomeDisplay.textContent.replace('$', '').replace(/,/g, ''));
    let totalExpenses = parseFloat(totalExpensesDisplay.textContent.replace('$', '').replace(/,/g, ''));
    
    // Update values
    totalIncome += incomeAmount;
    totalExpenses += expenseAmount;
    const balance = totalIncome - totalExpenses;
    
    // Update displays
    totalIncomeDisplay.textContent = `$${totalIncome.toFixed(2)}`;
    totalExpensesDisplay.textContent = `$${totalExpenses.toFixed(2)}`;
    balanceDisplay.textContent = `$${balance.toFixed(2)}`;
}

// Initialize sections when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initIncomeSection();
    initExpenseSection();
});