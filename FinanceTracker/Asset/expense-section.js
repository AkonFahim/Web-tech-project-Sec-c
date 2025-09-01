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
            
            // Create expense object
            const expense = {
                amount,
                description,
                category,
                date
            };
            
            // Save expense (in a real app, this would be sent to a server)
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
    // In a real application, this would be an API call to the server
    // For now, we'll store in localStorage
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
        expenseHistory.innerHTML = '<tr><td colspan="4" class="finance-no-data">No expense records found</td></tr>';
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
        `;
        
        expenseHistory.appendChild(row);
    });
}