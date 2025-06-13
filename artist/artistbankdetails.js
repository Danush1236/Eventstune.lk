document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const bankForm = document.getElementById('bankDetailsForm');
    const bankSelect = document.getElementById('bankName');
    const accountNumber = document.getElementById('accountNumber');
    const accountHolder = document.getElementById('accountHolder');
    const saveButton = document.getElementById('saveBankDetails');
    const bankDetailsDisplay = document.getElementById('bankDetailsDisplay');

    // Load saved bank details
    loadBankDetails();

    // Handle save button click
    saveButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent form submission
        if (validateForm()) {
            saveBankDetails();
            showSavedDetails();
            showSaveSuccess();
        }
    });

    // Function to load saved bank details
    function loadBankDetails() {
        const savedDetails = JSON.parse(localStorage.getItem('bankDetails'));
        
        if (savedDetails && savedDetails.bankName) {
            // Show saved details
            showSavedDetails();
        } else {
            // Show new form
            showNewForm();
        }
    }

    // Function to show saved details
    function showSavedDetails() {
        const savedDetails = JSON.parse(localStorage.getItem('bankDetails'));
        if (!savedDetails) return;
        
        // Hide form
        bankForm.style.display = 'none';
        
        // Show saved details
        bankDetailsDisplay.innerHTML = `
            <div class="saved-details">
                <div class="detail-item">
                    <label>Bank Name:</label>
                    <span>${getBankName(savedDetails.bankName)}</span>
                </div>
                <div class="detail-item">
                    <label>Account Number:</label>
                    <span>${maskAccountNumber(savedDetails.accountNumber)}</span>
                </div>
                <div class="detail-item">
                    <label>Account Holder:</label>
                    <span>${savedDetails.accountHolder}</span>
                </div>
                <button type="button" onclick="editBankDetails()" class="btn-edit">
                    <i class="fas fa-edit"></i> Edit Bank Details
                </button>
            </div>
        `;
        bankDetailsDisplay.style.display = 'block';
    }

    // Function to show new form
    function showNewForm() {
        bankForm.style.display = 'block';
        bankDetailsDisplay.style.display = 'none';
        
        // Clear form fields
        bankSelect.value = '';
        accountNumber.value = '';
        accountHolder.value = '';
        
        // Enable form fields
        bankSelect.disabled = false;
        accountNumber.disabled = false;
        accountHolder.disabled = false;
    }

    // Function to edit bank details (made global)
    window.editBankDetails = function() {
        const savedDetails = JSON.parse(localStorage.getItem('bankDetails'));
        if (!savedDetails) return;

        // Show form
        bankForm.style.display = 'block';
        bankDetailsDisplay.style.display = 'none';
        
        // Populate form with saved details
        bankSelect.value = savedDetails.bankName;
        accountNumber.value = savedDetails.accountNumber;
        accountHolder.value = savedDetails.accountHolder;
        
        // Enable form fields
        bankSelect.disabled = false;
        accountNumber.disabled = false;
        accountHolder.disabled = false;
    };

    // Function to save bank details
    function saveBankDetails() {
        const bankDetails = {
            bankName: bankSelect.value,
            accountNumber: accountNumber.value,
            accountHolder: accountHolder.value
        };

        // Save to localStorage
        localStorage.setItem('bankDetails', JSON.stringify(bankDetails));
        console.log('Saved bank details:', bankDetails); // Debug log
    }

    // Function to validate form
    function validateForm() {
        let isValid = true;
        const errorMessages = [];

        if (!bankSelect.value) {
            errorMessages.push('Please select a bank');
            isValid = false;
        }

        if (!accountNumber.value || !/^\d{8,}$/.test(accountNumber.value)) {
            errorMessages.push('Account number must be at least 8 digits');
            isValid = false;
        }

        if (!accountHolder.value.trim()) {
            errorMessages.push('Please enter account holder name');
            isValid = false;
        }

        if (!isValid) {
            alert('Please correct the following errors:\n' + errorMessages.join('\n'));
        }

        return isValid;
    }

    // Helper function to get bank name from value
    function getBankName(value) {
        const banks = {
            'boc': 'Bank of Ceylon',
            'peoples': "People's Bank",
            'commercial': 'Commercial Bank',
            'hatton': 'Hatton National Bank',
            'sampath': 'Sampath Bank',
            'ndb': 'NDB Bank',
            'seylan': 'Seylan Bank',
            'dfcc': 'DFCC Bank',
            'panasia': 'Pan Asia Bank',
            'union': 'Union Bank'
        };
        return banks[value] || value;
    }

    // Helper function to mask account number
    function maskAccountNumber(number) {
        if (!number) return '';
        return '****' + number.slice(-4);
    }

    // Function to show save success message
    function showSaveSuccess() {
        const successMessage = document.createElement('div');
        successMessage.className = 'success-message';
        successMessage.textContent = 'Bank details saved successfully!';
        successMessage.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: fadeInOut 3s ease-in-out;
        `;

        document.body.appendChild(successMessage);
        setTimeout(() => successMessage.remove(), 3000);
    }
}); 