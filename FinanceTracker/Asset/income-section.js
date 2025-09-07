// In-Memory Income Transactions
const incomeTransactions = [];

// Initialize Income Section
document.addEventListener('DOMContentLoaded', () => {
    const saveIncomeButton = document.getElementById('saveIncome');
    const incomeForm = document.getElementById('incomeForm');
    const incomeMessage = document.getElementById('incomeMessage');
    const incomeHistory = document.getElementById('incomeHistory');

    // Add income transaction
    saveIncomeButton.addEventListener('click', () => {
        if (!incomeForm.checkValidity()) {
            incomeForm.reportValidity();
            return;
        }

        const amount = parseFloat(document.getElementById('incomeAmount').value);
        const description = document.getElementById('incomeDescription').value;
        const category = document.getElementById('incomeCategory').value;
        const date = document.getElementById('incomeDate').value;

        // Create an income object and push it into the transactions array
        const income = { id: Date.now(), amount, description, category, date };
        incomeTransactions.push(income);

        // Show success message
        incomeMessage.textContent = 'Income added successfully! hello';
        incomeMessage.className = 'finance-income-message success';

        // Reset form
        incomeForm.reset();

        // Load updated income history
        loadIncomeHistory();

        // Clear success message after 3 seconds
        setTimeout(() => {
            incomeMessage.textContent = '';
            incomeMessage.className = 'finance-income-message';
        }, 3000);
    });

    // Load income history into the table
    function loadIncomeHistory() {
        incomeHistory.innerHTML = '';  // Clear the table

        // If there are no transactions, show a message
        if (incomeTransactions.length === 0) {
            incomeHistory.innerHTML = '<tr><td colspan="5">No income records found</td></tr>';
            return;
        }

        // Populate the table with income transactions
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

        // Add event listener to delete button
        document.querySelectorAll('.finance-delete-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                deleteIncome(e.target.getAttribute('data-id'));
            });
        });
    }

    // Delete an income transaction
    function deleteIncome(id) {
        const index = incomeTransactions.findIndex(i => i.id == id);
        if (index !== -1) {
            incomeTransactions.splice(index, 1);  // Remove transaction
            loadIncomeHistory();  // Reload the history table
        }
    }

    // Initialize income history
    loadIncomeHistory();
});
