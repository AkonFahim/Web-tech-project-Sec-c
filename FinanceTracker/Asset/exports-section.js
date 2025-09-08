 document.addEventListener('DOMContentLoaded', function() {
            const exportButtons = document.querySelectorAll('.export-btn');
            const exportHistoryList = document.getElementById('export-history-list');
            const scheduledListContainer = document.getElementById('scheduled-list-container');
            const saveScheduleBtn = document.querySelector('.save-schedule-btn');
            const scheduleTypeSelect = document.getElementById('schedule-type-select');
            const scheduleFormatSelect = document.getElementById('schedule-format-select');
            const scheduleEmailInput = document.getElementById('schedule-email-input');
            const exportSettingsBtn = document.getElementById('export-settings-btn');
            const dateRangeSelect = document.getElementById('date-range-select');
            const dataTypeSelect = document.getElementById('data-type-select');
            const encryptionSelect = document.getElementById('encryption-select');
            const includeCheckboxes = document.querySelectorAll('.checkbox-group input[type="checkbox"]');

            // Function to handle file download
            function downloadFile(fileName, content) {
                const element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(content));
                element.setAttribute('download', fileName);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            }

            // Function to add a new history item with delete/download functionality
            function addHistoryItem(fileName, details, iconClass, content) {
                const date = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const newExportItem = document.createElement('div');
                newExportItem.classList.add('history-item');
                newExportItem.innerHTML = `
                    <div class="option-icon">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="item-details">
                        <h6>${fileName}</h6>
                        <p>${details}</p>
                        <p>Exported on ${date}</p>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn download-btn">Download</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </div>
                `;
                exportHistoryList.appendChild(newExportItem);

                // Add event listeners for the new buttons
                newExportItem.querySelector('.download-btn').addEventListener('click', function() {
                    downloadFile(fileName, content);
                });

                newExportItem.querySelector('.delete-btn').addEventListener('click', function() {
                    if (confirm(`Are you sure you want to delete ${fileName}?`)) {
                        newExportItem.remove();
                        alert(`${fileName} has been deleted.`);
                    }
                });
            }

            // Function to handle export with settings
            if (exportSettingsBtn) {
                exportSettingsBtn.addEventListener('click', function() {
                    const dateRange = dateRangeSelect.value;
                    const dataType = dataTypeSelect.value;
                    const encryption = encryptionSelect.value;
                    
                    const checkedIncludes = Array.from(includeCheckboxes).filter(cb => cb.checked);
                    const includes = checkedIncludes.map(cb => cb.parentNode.textContent.trim()).join(', ');

                    // Check if any of the required fields are empty
                    if (dateRange === '' || dataType === '' || encryption === '') {
                        alert('Please fill out all the fields in Export Settings.');
                        return;
                    }

                    if (checkedIncludes.length === 0) {
                        alert('Please select at least one "Include" option.');
                        return;
                    }

                    const fileName = `custom_export_data_${new Date().getFullYear()}.txt`;
                    const fileContent = `This is a financial data export file.\n\nSettings used:\nDate Range: ${dateRange}\nData Type: ${dataType}\nEncryption: ${encryption}\nIncludes: ${includes}`;
                    const details = `<strong>Settings:</strong> ${dateRange}, ${dataType}, Includes: ${includes}`;
                    
                    addHistoryItem(fileName, details, 'fas fa-cog', fileContent);
                    alert('Export completed successfully!');
                });
            }

            // Function to handle standard export buttons
            exportButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const optionDiv = this.closest('.option-item');
                    const format = optionDiv.dataset.format;
                    const fileName = `${format.toLowerCase().replace(' ', '_')}_data_${new Date().getFullYear()}.` + (format === 'CSV' ? 'csv' : 'pdf');
                    const iconClass = format === 'CSV' ? 'fas fa-file-csv' : (format === 'PDF' ? 'fas fa-file-pdf' : 'fas fa-file-invoice-dollar');
                    const details = 'Standard Export';
                    const fileContent = `This is a standard export file in ${format} format.`;
                    
                    addHistoryItem(fileName, details, iconClass, fileContent);
                    alert('Export completed successfully!');
                });
            });

            // Function to handle saving schedule and downloading
            saveScheduleBtn.addEventListener('click', function() {
                const scheduleType = scheduleTypeSelect.value;
                const scheduleFormat = scheduleFormatSelect.value;
                const email = scheduleEmailInput.value.trim();

                // Check if any of the required fields are empty
                if (scheduleType === '' || scheduleFormat === '' || email === '') {
                    alert('Please fill out all the fields to save the schedule.');
                    return;
                }

                const fileName = `scheduled_export_${scheduleType.toLowerCase()}_${new Date().getFullYear()}.txt`;
                const fileContent = `This is a scheduled export file.\n\nSchedule Details:\nType: ${scheduleType}\nFormat: ${scheduleFormat}\nEmail: ${email}`;

                const newScheduleItem = document.createElement('div');
                newScheduleItem.classList.add('schedule-item');
                newScheduleItem.innerHTML = `
                    <div class="option-icon"><i class="fas fa-clock"></i></div>
                    <div class="item-details">
                        <h6>Scheduled Export</h6>
                        <p><strong>Type:</strong> ${scheduleType}, <strong>Format:</strong> ${scheduleFormat}</p>
                        <p><strong>Email:</strong> ${email}</p>
                    </div>
                    <div class="item-actions">
                        <button class="action-btn download-btn">Download</button>
                        <button class="action-btn delete-btn">Delete</button>
                    </div>
                `;
                scheduledListContainer.appendChild(newScheduleItem);
                
                // Add download functionality to the newly added item
                newScheduleItem.querySelector('.download-btn').addEventListener('click', function() {
                    downloadFile(fileName, fileContent);
                });

                newScheduleItem.querySelector('.delete-btn').addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this schedule?')) {
                        newScheduleItem.remove();
                    }
                });

                scheduleEmailInput.value = ''; // Clear the input field
                alert('Schedule saved successfully!');
            });
        });