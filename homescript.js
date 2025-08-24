const balanceElement = document.getElementById('balance');
const incomeElement = document.getElementById('income');
const expenseElement = document.getElementById('expense');
const transactionsList = document.getElementById('transactions-list');
const transactionForm = document.getElementById('transaction-form');
const resetBtn = document.getElementById('reset-btn');
const chartCanvas = document.getElementById('chart');

let transactions = [];

document.getElementById('date').valueAsDate = new Date();

const chart = new Chart(chartCanvas, {
  type: 'doughnut',
  data: {
    labels: ['Income', 'Expenses'],
    datasets: [{
      data: [0, 0],
      backgroundColor: ['#2ecc71', '#e74c3c'],
      borderWidth: 0,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          font: { size: 14 },
          padding: 20
        }
      },
      tooltip: {
        callbacks: {
          label: context => `$${context.parsed.toFixed(2)}`
        }
      }
    },
    cutout: '70%'
  }
});

function formatCurrency(amount) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'BDT',
    minimumFractionDigits: 2
  }).format(amount);
}

function generateID() {
  return Math.floor(Math.random() * 1e9);
}

function updateSummary() {
  const amounts = transactions.map(t => t.type === 'income' ? t.amount : -t.amount);
  const balance = amounts.reduce((acc, item) => acc + item, 0);
  const income = amounts.filter(item => item > 0).reduce((acc, item) => acc + item, 0);
  const expense = amounts.filter(item => item < 0).reduce((acc, item) => acc + item, 0) * -1;

  balanceElement.textContent = formatCurrency(balance);
  incomeElement.textContent = formatCurrency(income);
  expenseElement.textContent = formatCurrency(expense);

  chart.data.datasets[0].data = [income, expense];
  chart.update();
}

function addTransactionDOM(transaction) {
  const sign = transaction.type === 'income' ? '+' : '-';
  const item = document.createElement('div');
  item.classList.add('transaction-item', transaction.type);

  const categoryIcons = {
    salary: 'fa-money-bill',
    freelance: 'fa-laptop',
    investment: 'fa-chart-line',
    food: 'fa-utensils',
    shopping: 'fa-shopping-bag',
    transport: 'fa-car',
    entertainment: 'fa-film',
    utilities: 'fa-bolt',
    health: 'fa-heartbeat',
    other: 'fa-question-circle'
  };

  item.innerHTML = `
    <div class="transaction-info">
      <div class="transaction-icon">
        <i class="fas ${categoryIcons[transaction.category] || 'fa-question-circle'}"></i>
      </div>
      <div class="transaction-details">
        <h3>${transaction.title}</h3>
        <p>${transaction.date} • ${transaction.category}</p>
      </div>
    </div>
    <div class="transaction-amount">${sign}${formatCurrency(transaction.amount)}</div>
    <button class="delete-btn" data-id="${transaction.id}">
      <i class="fas fa-trash-alt"></i>
    </button>
  `;
  transactionsList.insertBefore(item, transactionsList.firstChild);
}

function updateTransactionsList() {
  transactionsList.innerHTML = '';
  transactions.slice().reverse().forEach(addTransactionDOM);
  updateSummary();
}

function addTransaction(e) {
  e.preventDefault();
  const title = document.getElementById('title').value.trim();
  const amount = parseFloat(document.getElementById('amount').value);
  const typeElem = document.querySelector('.radio-option.selected');
  const type = typeElem ? typeElem.dataset.type : '';
  const category = document.getElementById('category').value;
  const date = document.getElementById('date').value;

  if (!title || !amount || !type || !category || !date) {
    alert('Please fill all fields');
    return;
  }

  const transaction = {
    id: generateID(),
    title,
    amount,
    type,
    category,
    date: new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
  };

  transactions.push(transaction);
  addTransactionDOM(transaction);
  updateSummary();

  transactionForm.reset();
  document.getElementById('date').valueAsDate = new Date();
  document.querySelectorAll('.radio-option').forEach(o => o.classList.remove('selected'));
}

function deleteTransaction(id) {
  transactions = transactions.filter(t => t.id !== id);
  updateTransactionsList();
}

function resetData() {
  if (confirm('Are you sure you want to reset all data?')) {
    transactions = [];
    updateTransactionsList();
  }
}

transactionForm.addEventListener('submit', addTransaction);
resetBtn.addEventListener('click', resetData);

document.querySelectorAll('.radio-option').forEach(option => {
  option.addEventListener('click', function () {
    document.querySelectorAll('.radio-option').forEach(o => o.classList.remove('selected'));
    this.classList.add('selected');
  });
});

transactionsList.addEventListener('click', e => {
  const btn = e.target.closest('.delete-btn');
  if (btn) {
    deleteTransaction(parseInt(btn.dataset.id));
  }
});

const logoutBtn = document.getElementById("logout-btn");
logoutBtn.addEventListener('click', () => {
  window.location.href = "LogSign.html";
});


function init() {
  updateTransactionsList();
}
init();



