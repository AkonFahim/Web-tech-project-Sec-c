document.addEventListener("DOMContentLoaded", () => {
    const reportFilterForm = document.getElementById("reportFilterForm");
    const startDateInput = document.getElementById("reportStartDate");
    const endDateInput = document.getElementById("reportEndDate");
    const exportChartsBtn = document.getElementById("exportChartsBtn");

    let spendingTrendsChart, incomeExpenseChart, netWorthChart;

    // Initialize all charts with unique IDs
    function initCharts() {
        const ctxSpending = document.getElementById("reportSpendingTrendsChart").getContext("2d");
        const ctxIncomeExpense = document.getElementById("reportIncomeExpenseChart").getContext("2d");
        const ctxNetWorth = document.getElementById("reportNetWorthChart").getContext("2d");

        spendingTrendsChart = new Chart(ctxSpending, {
            type: "line",
            data: { labels: [], datasets: [{ label: "Expenses", data: [], borderColor: "red", backgroundColor: "rgba(255,0,0,0.1)", tension: 0.3 }] },
            options: { responsive: true }
        });

        incomeExpenseChart = new Chart(ctxIncomeExpense, {
            type: "bar",
            data: { labels: [], datasets: [
                { label: "Income", data: [], backgroundColor: "green" },
                { label: "Expenses", data: [], backgroundColor: "red" }
            ] },
            options: { responsive: true }
        });

        netWorthChart = new Chart(ctxNetWorth, {
            type: "line",
            data: { labels: [], datasets: [{ label: "Net Worth", data: [], borderColor: "blue", backgroundColor: "rgba(0,0,255,0.1)", tension: 0.3 }] },
            options: { responsive: true }
        });
    }

    // Filter transactions by start/end date
    function filterTransactions(transactions, start, end) {
        const startTime = start ? new Date(start).getTime() : null;
        const endTime = end ? new Date(end).getTime() : null;

        return transactions.filter(tx => {
            const txTime = new Date(tx.date).getTime();
            return (!startTime || txTime >= startTime) && (!endTime || txTime <= endTime);
        });
    }

    // Update all charts
    window.updateCharts = function() {
        const transactions = window.financeData.transactions || [];
        const filtered = filterTransactions(transactions, startDateInput.value, endDateInput.value);

        // Unique sorted dates
        const labels = Array.from(new Set(filtered.map(tx => tx.date))).sort();

        // Prepare data
        const incomeData = labels.map(date => filtered.filter(tx => tx.type === "income" && tx.date === date)
            .reduce((sum, tx) => sum + tx.amount, 0));

        const expenseData = labels.map(date => filtered.filter(tx => tx.type === "expense" && tx.date === date)
            .reduce((sum, tx) => sum + tx.amount, 0));

        let cumulativeNetWorth = 0;
        const netWorthData = labels.map((d, idx) => {
            cumulativeNetWorth += incomeData[idx] - expenseData[idx];
            return cumulativeNetWorth;
        });

        // Update charts
        spendingTrendsChart.data.labels = labels;
        spendingTrendsChart.data.datasets[0].data = expenseData;
        spendingTrendsChart.update();

        incomeExpenseChart.data.labels = labels;
        incomeExpenseChart.data.datasets[0].data = incomeData;
        incomeExpenseChart.data.datasets[1].data = expenseData;
        incomeExpenseChart.update();

        netWorthChart.data.labels = labels;
        netWorthChart.data.datasets[0].data = netWorthData;
        netWorthChart.update();
    }

    // Export charts as PNG
    function exportCharts() {
        [spendingTrendsChart, incomeExpenseChart, netWorthChart].forEach((chart, i) => {
            const link = document.createElement("a");
            link.href = chart.toBase64Image();
            link.download = `chart_${i + 1}.png`;
            link.click();
        });
    }

    // Form submit - apply date filters
    reportFilterForm.addEventListener("submit", e => {
        e.preventDefault();
        updateCharts();
    });

    exportChartsBtn.addEventListener("click", exportCharts);

    // Initialize charts
    initCharts();
    updateCharts();

    // Optional: refresh charts when reports section becomes active
    document.addEventListener('sectionChanged', e => {
        if (e.detail && e.detail.section === 'reports-section') {
            updateCharts();
        }
    });
});
