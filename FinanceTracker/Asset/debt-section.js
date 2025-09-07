document.addEventListener('DOMContentLoaded', () => {

    const debts = [];

    // 2. Select necessary DOM elements for the form and display areas
    const debtNameInput = document.querySelector('.finance-debt-form input[placeholder="e.g., Credit Card"]');
    const initialAmountInput = document.querySelector('.finance-debt-form-group:nth-of-type(2) .finance-debt-form-input');
    const currentBalanceInput = document.querySelector('.finance-debt-form-group:nth-of-type(3) .finance-debt-form-input');
    const interestRateInput = document.querySelector('.finance-debt-form-group:nth-of-type(4) .finance-debt-form-input');
    const minimumPaymentInput = document.querySelector('.finance-debt-form-group:nth-of-type(5) .finance-debt-form-input');
    const addDebtBtn = document.querySelector('.finance-adddebt-btn');
    
    const debtSummaryContainer = document.querySelector('.finance-debt-summary');
    const debtListContainer = document.querySelector('.finance-debt-list');

    // ---
    
    // 3. Helper function to determine the icon based on debt name
    const getDebtIcon = (debtName) => {
        const lowerCaseName = debtName.toLowerCase();
        if (lowerCaseName.includes('credit card')) return 'fas fa-credit-card';
        if (lowerCaseName.includes('student')) return 'fas fa-university';
        if (lowerCaseName.includes('car')) return 'fas fa-car';
        if (lowerCaseName.includes('mortgage') || lowerCaseName.includes('home')) return 'fas fa-home';
        if (lowerCaseName.includes('loan')) return 'fas fa-hand-holding-usd';
        return 'fas fa-money-bill-wave';
    };
    
    // ---
    
    // 4. Function to render the debt list and summary
    const renderDebts = () => {
        // Clear existing content
        debtSummaryContainer.innerHTML = '';
        debtListContainer.innerHTML = '';

        if (debts.length === 0) {
            debtListContainer.innerHTML = '<p class="text-center text-muted mt-3">No debts added. Add a new one to get started!</p>';
            debtSummaryContainer.innerHTML = `
                <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Total Debt</span>
                    <span class="finance-debt-summary-value">$0.00</span>
                </div>
                <div class="finance-debt-summary-item">
                    <span class="finance-debt-summary-label">Monthly Payments</span>
                    <span class="finance-debt-summary-value">$0.00</span>
                </div>
            `;
            return;
        }

        // Calculate totals
        const totalDebt = debts.reduce((sum, debt) => sum + parseFloat(debt.currentBalance), 0);
        const totalMinPayment = debts.reduce((sum, debt) => sum + parseFloat(debt.minimumPayment), 0);
        
        // Render summary
        debtSummaryContainer.innerHTML = `
            <div class="finance-debt-summary-item">
                <span class="finance-debt-summary-label">Total Debt</span>
                <span class="finance-debt-summary-value">$${totalDebt.toFixed(2)}</span>
            </div>
            <div class="finance-debt-summary-item">
                <span class="finance-debt-summary-label">Monthly Payments</span>
                <span class="finance-debt-summary-value">$${totalMinPayment.toFixed(2)}</span>
            </div>
            `;
        
        // Render individual debt items
        debts.forEach((debt, index) => {
            const debtItem = document.createElement('div');
            debtItem.classList.add('finance-debt-item');
            debtItem.dataset.index = index;
            
            const icon = getDebtIcon(debt.name);
            
            debtItem.innerHTML = `
                <div class="finance-debt-icon">
                    <i class="${icon}"></i>
                </div>
                <div class="finance-debt-details">
                    <h6>${debt.name}</h6>
                    <p>Interest: ${parseFloat(debt.interestRate).toFixed(2)}%</p>
                </div>
                <div class="finance-debt-amount">
                    <span class="finance-debt-balance">$${parseFloat(debt.currentBalance).toFixed(2)}</span>
                    <span class="finance-debt-payment">Min: $${parseFloat(debt.minimumPayment).toFixed(2)}</span>
                </div>
                <div class="finance-debt-actions">
                    <button class="finance-makepayment-btn">Make Payment</button>
                    <button class="finance-deletedebt-btn"><i class="fas fa-trash"></i></button>
                </div>
            `;
            
            debtListContainer.appendChild(debtItem);
        });
        
        // Add event listeners for delete buttons
        document.querySelectorAll('.finance-deletedebt-btn').forEach(button => {
            button.addEventListener('click', handleDeleteDebt);
        });
    };

    // ---
    
    // 5. Function to handle adding a new debt
    const handleAddDebt = (event) => {
        event.preventDefault();
        
        // Get values from input fields
        const name = debtNameInput.value.trim();
        const initialAmount = parseFloat(initialAmountInput.value);
        const currentBalance = parseFloat(currentBalanceInput.value);
        const interestRate = parseFloat(interestRateInput.value);
        const minimumPayment = parseFloat(minimumPaymentInput.value);

        // Simple validation to ensure all fields are filled and are valid numbers
        if (!name || isNaN(initialAmount) || isNaN(currentBalance) || isNaN(interestRate) || isNaN(minimumPayment) || initialAmount <= 0 || currentBalance <= 0 || interestRate < 0 || minimumPayment <= 0) {
            alert('Please fill out all fields correctly. All amounts must be positive numbers.');
            return;
        }

        const newDebt = {
            name,
            initialAmount,
            currentBalance,
            interestRate,
            minimumPayment
        };
        
        debts.push(newDebt);
        
        // Clear form fields
        debtNameInput.value = '';
        initialAmountInput.value = '';
        currentBalanceInput.value = '';
        interestRateInput.value = '';
        minimumPaymentInput.value = '';
        
        renderDebts();
    };

    // ---

    // 6. Function to handle deleting a debt
    const handleDeleteDebt = (event) => {
        const debtItem = event.target.closest('.finance-debt-item');
        const index = debtItem.dataset.index;
        
        debts.splice(index, 1);
        renderDebts();
    };
    
    // ---
    
    // 7. Initial rendering and event listener setup
    renderDebts();
    addDebtBtn.addEventListener('click', handleAddDebt);
});