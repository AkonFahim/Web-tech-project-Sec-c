const balanceElement = document.getElementById('balance');
const incomeElement = document.getElementById('income');
const expenseElement = document.getElementById('expense');
const transactionsList = document.getElementById('transactions-list');
const transactionForm = document.getElementById('transaction-form');
const resetBtn = document.getElementById('reset-btn');
const chartCanvas = document.getElementById('chart');
const exportBtn = document.getElementById('export-btn');
const exportOptions = document.getElementById('exportOptions');
const exportPdf = document.getElementById('export-pdf');
const exportExcel = document.getElementById('export-excel');

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


const menuToggle = document.getElementById('menuToggle');
const headerNav = document.querySelector('.header-nav');

if (menuToggle && headerNav) {
  menuToggle.addEventListener('click', function() {
    headerNav.classList.toggle('active');
    
    const icon = menuToggle.querySelector('i');
    if (headerNav.classList.contains('active')) {
      icon.classList.remove('fa-bars');
      icon.classList.add('fa-times');
    } else {
      icon.classList.remove('fa-times');
      icon.classList.add('fa-bars');
    }
  });
  
  document.addEventListener('click', function(event) {
    if (!event.target.closest('.header') && headerNav.classList.contains('active')) {
      headerNav.classList.remove('active');
      const icon = menuToggle.querySelector('i');
      icon.classList.remove('fa-times');
      icon.classList.add('fa-bars');
    }
  });
}





exportBtn.addEventListener('click', function(e) {
  e.preventDefault();
  e.stopPropagation();
  exportOptions.classList.toggle('active');
});

document.addEventListener('click', function(e) {
  if (!e.target.closest('#export-btn') && !e.target.closest('.export-options')) {
    exportOptions.classList.remove('active');
  }
});
// PDF Export functionality
exportPdf.addEventListener('click', function() {
  exportOptions.classList.remove('active');
  
  if (transactions.length === 0) {
    alert('No transactions to export!');
    return;
  }
  
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  // Add title
  doc.setFontSize(20);
  doc.text('Financial Transactions Report', 105, 15, { align: 'center' });
  doc.setFontSize(12);
  doc.text(`Generated on: ${new Date().toLocaleDateString()}`, 105, 22, { align: 'center' });
  
  const amounts = transactions.map(t => t.type === 'income' ? t.amount : -t.amount);
  const income = amounts.filter(item => item > 0).reduce((acc, item) => acc + item, 0);
  const expense = amounts.filter(item => item < 0).reduce((acc, item) => acc + item, 0) * -1;
  const balance = income - expense;
  
  doc.setFontSize(14);
  doc.text('Financial Summary', 14, 35);
  doc.setFontSize(10);
  doc.text(`Total Income: BDT${income.toFixed(2)}`, 14, 45);
  doc.text(`Total Expenses: BDT${expense.toFixed(2)}`, 14, 55);
  doc.text(`Balance: BDT${balance.toFixed(2)}`, 14, 65);
  
  doc.setFontSize(14);
  doc.text('Transaction Details', 14, 80);
  
  doc.setFillColor(67, 97, 238);
  doc.setTextColor(255, 255, 255);
  doc.rect(14, 85, 183, 8, 'F');
  doc.text('Date', 20, 90);
  doc.text('Description', 60, 90);
  doc.text('Category', 120, 90);
  doc.text('Amount', 160, 90);
  
  doc.setTextColor(0, 0, 0);
  let yPosition = 100;
  
  transactions.slice().reverse().forEach((transaction, index) => {
    if (yPosition > 270) {
      doc.addPage();
      yPosition = 20;
    }
    
    if (index % 2 === 0) {
      doc.setFillColor(240, 245, 255);
      doc.rect(14, yPosition - 5, 183, 8, 'F');
    }
    
    doc.text(transaction.date, 20, yPosition);
    doc.text(transaction.title, 60, yPosition);
    doc.text(transaction.category, 120, yPosition);
    
    if (transaction.type === 'income') {
      doc.setTextColor(46, 204, 113);
      doc.text('+' + formatCurrency(transaction.amount), 160, yPosition);
    } else {
      doc.setTextColor(231, 76, 60);
      doc.text('-' + formatCurrency(transaction.amount), 160, yPosition);
    }
    
    doc.setTextColor(0, 0, 0);
    yPosition += 10;
  });
  
  doc.save('financial-report.pdf');
});

// Excel Export functionality
exportExcel.addEventListener('click', function() {
  exportOptions.classList.remove('active');
  
  if (transactions.length === 0) {
    alert('No transactions to export!');
    return;
  }
  
  const worksheetData = [
    ['Date', 'Description', 'Category', 'Type', 'Amount', 'Status'],
    ...transactions.map(transaction => [
      transaction.date,
      transaction.title,
      transaction.category,
      transaction.type,
      transaction.amount,
      transaction.type === 'income' ? 'Income' : 'Expense'
    ])
  ];
  
  const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
  
  if (!worksheet['A1'].s) worksheet['A1'].s = {};
  if (!worksheet['B1'].s) worksheet['B1'].s = {};
  if (!worksheet['C1'].s) worksheet['C1'].s = {};
  if (!worksheet['D1'].s) worksheet['D1'].s = {};
  if (!worksheet['E1'].s) worksheet['E1'].s = {};
  if (!worksheet['F1'].s) worksheet['F1'].s = {};
  
  const headerStyle = {
    font: { bold: true, color: { rgb: "FFFFFF" } },
    fill: { fgColor: { rgb: "4361EE" } }
  };
  
  worksheet['A1'].s = headerStyle;
  worksheet['B1'].s = headerStyle;
  worksheet['C1'].s = headerStyle;
  worksheet['D1'].s = headerStyle;
  worksheet['E1'].s = headerStyle;
  worksheet['F1'].s = headerStyle;
  
  // Set column widths
  worksheet['!cols'] = [
    { wch: 15 },
    { wch: 25 },
    { wch: 15 },
    { wch: 10 },
    { wch: 12 },
    { wch: 10 }
  ];
  
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Transactions');
  
  const amounts = transactions.map(t => t.type === 'income' ? t.amount : -t.amount);
  const income = amounts.filter(item => item > 0).reduce((acc, item) => acc + item, 0);
  const expense = amounts.filter(item => item < 0).reduce((acc, item) => acc + item, 0) * -1;
  const balance = income - expense;
  
  const summaryData = [
    ['Financial Summary', ''],
    ['Total Income', income],
    ['Total Expenses', expense],
    ['Balance', balance],
    ['', ''],
    ['Total Transactions', transactions.length],
    ['Income Transactions', transactions.filter(t => t.type === 'income').length],
    ['Expense Transactions', transactions.filter(t => t.type === 'expense').length]
  ];
  
  const summarySheet = XLSX.utils.aoa_to_sheet(summaryData);
  XLSX.utils.book_append_sheet(workbook, summarySheet, 'Summary');
  
  XLSX.writeFile(workbook, 'financial-report.xlsx');
});

exportOptions.addEventListener('click', function(e) {
  e.stopPropagation();
});