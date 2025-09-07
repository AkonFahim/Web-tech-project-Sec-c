function createModal(message, isConfirm = false, onConfirm = null) {
  // Check if a modal already exists to prevent duplicates
  if (document.getElementById('custom-modal')) {
    return;
  }

  const modalOverlay = document.createElement('div');
  modalOverlay.id = 'custom-modal';
  modalOverlay.className = 'fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50';

  const modalContainer = document.createElement('div');
  modalContainer.className = 'bg-white p-6 rounded-lg shadow-xl max-w-sm w-full text-center';

  const messageText = document.createElement('p');
  messageText.className = 'text-lg font-semibold text-gray-800 mb-4';
  messageText.textContent = message;

  modalContainer.appendChild(messageText);

  const buttonContainer = document.createElement('div');
  buttonContainer.className = 'flex justify-center space-x-4';

  const closeButton = document.createElement('button');
  closeButton.className = 'px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors';
  closeButton.textContent = 'OK';
  closeButton.onclick = () => {
    modalOverlay.remove();
  };

  if (isConfirm) {
    const confirmButton = document.createElement('button');
    confirmButton.className = 'px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors';
    confirmButton.textContent = 'Confirm';
    confirmButton.onclick = () => {
      onConfirm();
      modalOverlay.remove();
    };
    buttonContainer.appendChild(confirmButton);
    closeButton.textContent = 'Cancel';
  }

  buttonContainer.appendChild(closeButton);
  modalContainer.appendChild(buttonContainer);
  modalOverlay.appendChild(modalContainer);
  document.body.appendChild(modalOverlay);
}

// A simple function to validate if a string contains only letters, numbers, and spaces.
function isValidInput(str) {
  for (let i = 0; i < str.length; i++) {
    const char = str[i];
    const isLetter = (char >= 'a' && char <= 'z') || (char >= 'A' && char <= 'Z');
    const isNumber = (char >= '0' && char <= '9');
    const isSpace = char === ' ';

    if (!isLetter && !isNumber && !isSpace) {
      return false;
    }
  }
  return true;
}

// Global array to store savings goal objects.
let savingsGoals = [];

/**
 * Creates a new savings goal and adds it to the savingsGoals array.
 * @param {string} name - The name of the goal.
 * @param {number} targetAmount - The target amount for the goal.
 * @param {string} targetDate - The target date as a string (YYYY-MM-DD).
 * @param {number} initialAmount - The initial amount saved towards the goal.
 * @returns {object|null} The newly created goal object, or null if validation fails.
 */
function createNewGoal(name, targetAmount, targetDate, initialAmount) {
  // Input validation
  if (!name || !isValidInput(name)) {
    createModal("Please enter a valid goal name (letters, numbers, and spaces only).");
    return null;
  }
  if (isNaN(targetAmount) || targetAmount <= 0) {
    createModal("Please enter a valid target amount greater than 0.");
    return null;
  }
  if (isNaN(initialAmount) || initialAmount < 0) {
    createModal("Please enter a valid initial amount (0 or more).");
    return null;
  }
  if (new Date(targetDate) < new Date()) {
    createModal("Please select a target date in the future.");
    return null;
  }
  if (initialAmount > targetAmount) {
    createModal("Initial amount cannot be more than the target amount.");
    return null;
  }

  // Create a unique ID for the goal
  const goalId = Date.now().toString();

  const newGoal = {
    id: goalId,
    name: name,
    targetAmount: parseFloat(targetAmount),
    targetDate: targetDate,
    savedAmount: parseFloat(initialAmount),
  };

  savingsGoals.push(newGoal);
  return newGoal;
}

/**
 * Renders the list of savings goals in the UI.
 */
function renderSavingsGoals() {
  const goalsListContainer = document.getElementById("savings-goals-list");
  goalsListContainer.innerHTML = ''; // Clear previous goals to prevent duplicates

  if (savingsGoals.length === 0) {
    goalsListContainer.innerHTML = '<p class="finance-no-goals-message">You have no active savings goals. Create one to get started!</p>';
  } else {
    savingsGoals.forEach(goal => {
      // Calculate progress percentage
      const progress = (goal.savedAmount / goal.targetAmount) * 100;
      const progressPercentage = Math.min(progress, 100).toFixed(2); // Cap at 100%

      // Create the HTML for a single goal card
      const goalCard = document.createElement('div');
      goalCard.classList.add('finance-savings-goal-card');
      goalCard.innerHTML = `
        <div class="finance-savings-goal-header">
          <h6 class="finance-savings-goal-name">${goal.name}</h6>
          <button class="finance-savings-goal-delete-btn" data-goal-id="${goal.id}">Delete</button>
        </div>
        <div class="finance-savings-goal-info">
          <p><strong>Target:</strong> $${goal.targetAmount.toFixed(2)}</p>
          <p><strong>Saved:</strong> $${goal.savedAmount.toFixed(2)}</p>
        </div>
        <div class="finance-savings-goal-progress">
          <div class="finance-savings-progress-bar">
            <div class="finance-savings-progress-fill" style="width: ${progressPercentage}%"></div>
          </div>
          <span class="finance-savings-progress-percent">${progressPercentage}%</span>
        </div>
        <div class="finance-savings-goal-date">
            <p><strong>Target Date:</strong> ${goal.targetDate}</p>
        </div>
        <div class="finance-savings-goal-deposit mt-4 flex items-center justify-between space-x-2">
            <input type="number" step="0.01" class="finance-savings-deposit-input flex-grow p-2 rounded-lg border border-gray-300" placeholder="Amount to deposit">
            <button class="finance-savings-deposit-btn px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors" data-goal-id="${goal.id}">Deposit</button>
        </div>
      `;
      goalsListContainer.appendChild(goalCard);
    });
  }
}

/**
 * Updates the overall savings summary displayed in the UI.
 */
function updateSavingsSummary() {
  const totalGoals = savingsGoals.length;
  const totalSaved = savingsGoals.reduce((sum, goal) => sum + goal.savedAmount, 0);
  const totalTarget = savingsGoals.reduce((sum, goal) => sum + goal.targetAmount, 0);

  const overallProgress = totalTarget > 0 ? (totalSaved / totalTarget) * 100 : 0;
  const overallProgressPercentage = Math.min(overallProgress, 100).toFixed(2);

  // Update the summary card values
  document.getElementById('total-goals-value').textContent = totalGoals;
  document.getElementById('total-saved-value').textContent = `$${totalSaved.toFixed(2)}`;
  document.getElementById('total-target-value').textContent = `$${totalTarget.toFixed(2)}`;
  document.getElementById('overall-progress-value').textContent = `${overallProgressPercentage}%`;
  document.getElementById('overall-progress-fill').style.width = `${overallProgressPercentage}%`;
}

/**
 * Deposits money into a savings goal.
 * @param {string} goalId - The ID of the goal to deposit into.
 * @param {number} amount - The amount to deposit.
 */
function depositMoney(goalId, amount) {
  const goal = savingsGoals.find(g => g.id === goalId);
  if (!goal) {
    createModal("Goal not found.");
    return;
  }

  const depositAmount = parseFloat(amount);
  if (isNaN(depositAmount) || depositAmount <= 0) {
    createModal("Please enter a valid amount to deposit.");
    return;
  }

  const newSavedAmount = goal.savedAmount + depositAmount;
 

  goal.savedAmount = newSavedAmount;
  renderSavingsGoals();
  updateSavingsSummary();
}

/**
 * Deletes a savings goal based on its ID.
 * @param {string} goalId - The ID of the goal to delete.
 */
function deleteGoal(goalId) {
  savingsGoals = savingsGoals.filter(goal => goal.id !== goalId);
  renderSavingsGoals();
  updateSavingsSummary();
}

/**
 * Handles the form submission for creating a new goal.
 */
function handleCreateGoal(event) {
  event.preventDefault(); // Prevent the default form submission behavior

  const goalName = document.getElementById('goal-name-input').value.trim();
  const targetAmount = document.getElementById('target-amount-input').value;
  const targetDate = document.getElementById('target-date-input').value;
  const initialAmount = document.getElementById('initial-amount-input').value;

  const newGoal = createNewGoal(goalName, targetAmount, targetDate, initialAmount);
  if (newGoal) {
    // If a new goal was successfully created, render the list and update the summary
    renderSavingsGoals();
    updateSavingsSummary();
    // Clear the form fields after successful submission
    document.getElementById('goal-name-input').value = '';
    document.getElementById('target-amount-input').value = '';
    document.getElementById('target-date-input').value = '';
    document.getElementById('initial-amount-input').value = '';
  }
}

/**
 * Sets up all the event listeners for the page.
 */
function setupEventListeners() {
  // Add listener for the "Create Goal" button
  const createGoalBtn = document.getElementById('create-goal-btn');
  createGoalBtn.addEventListener('click', handleCreateGoal);

  // Add listener to the goals list container for delegated events
  const goalsListContainer = document.getElementById('goals-list-container');
  goalsListContainer.addEventListener('click', (event) => {
    // Handle deposit button click
    if (event.target.classList.contains('finance-savings-deposit-btn')) {
      const goalId = event.target.getAttribute('data-goal-id');
      const depositInput = event.target.closest('.finance-savings-goal-deposit').querySelector('.finance-savings-deposit-input');
      const amount = depositInput.value;
      if (amount) {
        depositMoney(goalId, amount);
        depositInput.value = ''; // Clear the input field after deposit
      }
    }

    // Handle delete button click
    if (event.target.classList.contains('finance-savings-goal-delete-btn')) {
      const goalId = event.target.getAttribute('data-goal-id');
      createModal("Are you sure you want to delete this goal?", true, () => {
        deleteGoal(goalId);
      });
    }
  });
}

// Initial call to set up the UI and listeners when the page loads
document.addEventListener('DOMContentLoaded', () => {
  setupEventListeners();
  // Initially render the UI with no goals and a summary of 0.
  renderSavingsGoals();
  updateSavingsSummary();
});