
    // In-memory storage for budget data
    let budgetData = {};

    // --- Core Functions ---

    // Function to calculate and update the total monthly budget
    const updateMonthlyBudget = () => {
        let totalBudget = 0;
        let totalSpent = 0;

        // Loop through each category and sum up the values
        for (const category in budgetData) {
            totalBudget += budgetData[category].budget;
            totalSpent += budgetData[category].spent;
        }

        // Update the HTML elements with the new totals
        const totalBudgetAmount = document.getElementById('totalBudgetAmount');
        const budgetProgressText = document.getElementById('budgetProgressText');
        const budgetRemaining = document.getElementById('budgetRemaining');
        const budgetProgressFill = document.querySelector('.finance-budget-progress-fill');

        totalBudgetAmount.textContent = `$${totalBudget.toFixed(2)}`;
        budgetRemaining.textContent = `$${(totalBudget - totalSpent).toFixed(2)}`;
        
        const progressPercentage = (totalBudget > 0) ? (totalSpent / totalBudget) * 100 : 0;
        budgetProgressText.textContent = `${progressPercentage.toFixed(2)}% used`;
        budgetProgressFill.style.width = `${progressPercentage}%`;
    };

    // Function to update the list of budget categories
    const updateBudgetCategories = () => {
        const budgetCategoriesList = document.getElementById('budgetCategoriesList');
        budgetCategoriesList.innerHTML = ''; // Clear the list first

        for (const category in budgetData) {
            const categoryData = budgetData[category];
            const categoryRow = document.createElement('div');
            categoryRow.classList.add('finance-budget-category-item');
            categoryRow.style.cssText = 'border-bottom: 1px solid #e9ecef; padding: 1rem 0;';
            categoryRow.innerHTML = `
                <div class="finance-budget-category-info" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span class="finance-budget-category-name" style="font-weight: 600; color: #212529;">${category}</span>
                    <span class="finance-budget-category-amount" style="font-size: 0.9rem; color: #6c757d;">$${categoryData.spent.toFixed(2)} / $${categoryData.budget.toFixed(2)}</span>
                </div>
                <div class="finance-budget-category-progress" style="height: 6px; background-color: #e9ecef; border-radius: 3px;">
                    <div class="finance-budget-category-progress-bar" style="height: 100%; background-color: #007bff; border-radius: 3px; width: ${(categoryData.spent / categoryData.budget) * 100}%;"></div>
                </div>
            `;
            budgetCategoriesList.appendChild(categoryRow);
        }
    };
    
    // This is for a different part of your site, so no changes needed
    const updateSidebarMenu = () => {};

    // --- Event Handlers ---
    document.addEventListener('DOMContentLoaded', () => {
        const setBudgetBtn = document.getElementById('setBudgetBtn');
        if (setBudgetBtn) {
            setBudgetBtn.addEventListener('click', () => {
                const budgetAmount = parseFloat(document.getElementById('budgetAmount').value);
                const category = document.getElementById('budgetCategory').value;

                if (!category || isNaN(budgetAmount) || budgetAmount <= 0) {
                    alert('Please enter a valid budget amount and select a category.');
                    return;
                }

                // Store the new budget
                budgetData[category] = {
                    budget: budgetAmount,
                    spent: budgetData[category] ? budgetData[category].spent : 0 
                };

                // Clear the form and update the UI
                document.getElementById('budgetAmount').value = '';
                document.getElementById('budgetCategory').value = '';
                updateBudgetCategories();
                updateMonthlyBudget();
            });
        }
    });

    // --- Example Function for Demonstration ---
    // This function can be called from other parts of your app when an expense is made
    function addExpense(category, amount) {
        if (budgetData[category]) {
            if (budgetData[category].spent + amount <= budgetData[category].budget) {
                budgetData[category].spent += amount;
                updateBudgetCategories();
                updateMonthlyBudget();
            } else {
                alert('Expense exceeds budget for this category!');
            }
        }
    }
