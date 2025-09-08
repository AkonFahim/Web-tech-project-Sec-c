 // Get all the relevant HTML elements using their IDs
    const emailInput = document.getElementById('emailInput');
    const currencySelect = document.getElementById('currencySelect');
    const dateInput = document.getElementById('dateInput');
    const languageSelect = document.getElementById('languageSelect');
    const saveAccountBtn = document.getElementById('saveAccountBtn');
    const accountOutputDiv = document.getElementById('accountOutput');

    const billReminders = document.getElementById('billReminders');
    const budgetAlerts = document.getElementById('budgetAlerts');
    const weeklyReports = document.getElementById('weeklyReports');
    const securityAlerts = document.getElementById('securityAlerts');
    const saveNotificationBtn = document.getElementById('saveNotificationBtn');
    const notificationOutputDiv = document.getElementById('notificationOutput');

    const exportDataBtn = document.getElementById('exportDataBtn');
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const setup2FABtn = document.getElementById('setup2FABtn');

    // Function to save account settings to local storage and show output
    function saveAccountSettings() {
        const accountSettings = {
            email: emailInput.value,
            currency: currencySelect.value,
            date: dateInput.value,
            language: languageSelect.value
        };
        localStorage.setItem('accountSettings', JSON.stringify(accountSettings));
        
        let outputText = 'Account settings saved successfully! ✅\n\n';
        outputText += `Email: ${accountSettings.email || 'Not set'}\n`;
        outputText += `Currency: ${accountSettings.currency || 'Not set'}\n`;
        outputText += `Date: ${accountSettings.date || 'Not set'}\n`;
        outputText += `Language: ${accountSettings.language || 'Not set'}`;
        
        accountOutputDiv.innerText = outputText;
        accountOutputDiv.style.display = 'block';
    }

    // Function to load account settings from local storage
    function loadAccountSettings() {
        const savedSettings = localStorage.getItem('accountSettings');
        if (savedSettings) {
            const accountSettings = JSON.parse(savedSettings);
            emailInput.value = accountSettings.email || '';
            currencySelect.value = accountSettings.currency || '';
            dateInput.value = accountSettings.date || '';
            languageSelect.value = accountSettings.language || '';
        }
    }

    // Function to save notification settings to local storage and show output
    function saveNotificationSettings() {
        const notificationSettings = {
            billReminders: billReminders.checked,
            budgetAlerts: budgetAlerts.checked,
            weeklyReports: weeklyReports.checked,
            securityAlerts: securityAlerts.checked
        };
        localStorage.setItem('notificationSettings', JSON.stringify(notificationSettings));
        
        let outputText = 'Notification settings saved successfully! ✅\n\n';
        outputText += `Bill Reminders: ${notificationSettings.billReminders ? 'On' : 'Off'}\n`;
        outputText += `Budget Alerts: ${notificationSettings.budgetAlerts ? 'On' : 'Off'}\n`;
        outputText += `Weekly Reports: ${notificationSettings.weeklyReports ? 'On' : 'Off'}\n`;
        outputText += `Security Alerts: ${notificationSettings.securityAlerts ? 'On' : 'Off'}`;
        
        notificationOutputDiv.innerText = outputText;
        notificationOutputDiv.style.display = 'block';
    }

    // Function to load notification settings from local storage
    function loadNotificationSettings() {
        const savedSettings = localStorage.getItem('notificationSettings');
        if (savedSettings) {
            const notificationSettings = JSON.parse(savedSettings);
            billReminders.checked = notificationSettings.billReminders;
            budgetAlerts.checked = notificationSettings.budgetAlerts;
            weeklyReports.checked = notificationSettings.weeklyReports;
            securityAlerts.checked = notificationSettings.securityAlerts;
        }
    }

    // Add event listeners
    saveAccountBtn.addEventListener('click', saveAccountSettings);
    saveNotificationBtn.addEventListener('click', saveNotificationSettings);

    exportDataBtn.addEventListener('click', () => {
        alert('Exporting your data... (This is a placeholder function)');
    });

    deleteAccountBtn.addEventListener('click', () => {
        if (confirm('Are you sure you want to permanently delete your account? This action cannot be undone.')) {
            alert('Your account has been deleted. (This is a placeholder function)');
            // In a real application, you would send a request to the server to delete the account.
        }
    });

    setup2FABtn.addEventListener('click', () => {
        alert('Setting up Two-Factor Authentication... (This is a placeholder function)');
    });

    // Load saved settings on page load
    window.addEventListener('load', () => {
        loadAccountSettings();
        loadNotificationSettings();
    });