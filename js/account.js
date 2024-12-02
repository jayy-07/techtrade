$(document).ready(function() {
    // Initialize international phone input with Ghana as default country
    const phoneInput = intlTelInput(document.querySelector("#phone"), {
        initialCountry: "GH", // Set Ghana as default
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js", // Required for validation/formatting
        separateDialCode: true, // Show country code separately
        autoPlaceholder: "polite", // Show example number as placeholder
    });

    /**
     * Handle profile form submission
     * Validates phone number and submits form data via AJAX
     */
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        const phoneNumber = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
        const phoneError = $("#phone-error");
        const errorMessage = $('#error-message');
        
        // Clear any previous error messages
        phoneError.text("");
        errorMessage.text("");

        // Validate phone number format and show specific error messages
        if (!phoneInput.isValidNumber()) {
            const errorCode = phoneInput.getValidationError();
            let errorText = "Invalid phone number.";
            switch (errorCode) {
                case intlTelInputUtils.validationError.INVALID_COUNTRY_CODE:
                    errorText = "Invalid country code.";
                    break;
                case intlTelInputUtils.validationError.TOO_SHORT:
                    errorText = "The phone number is too short.";
                    break;
                case intlTelInputUtils.validationError.TOO_LONG:
                    errorText = "The phone number is too long.";
                    break;
                case intlTelInputUtils.validationError.NOT_A_NUMBER:
                    errorText = "This is not a valid number.";
                    break;
            }
            phoneError.text(errorText);
            return;
        }

        // Submit profile data to server
        $.ajax({
            url: '../actions/update_profile.php',
            type: 'POST',
            data: $(this).serialize() + '&phone=' + encodeURIComponent(phoneNumber),
            success: function(response) {
                const res = JSON.parse(response);
                showToast(res.message, res.success ? 'success' : 'danger');
                if (res.success) {
                    // Reload page after successful update
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function() {
                showToast('Unable to connect to the server. Please try again.', 'danger');
            }
        });
    });

    /**
     * Handle password change form submission
     * Validates password match and submits via AJAX
     */
    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        const errorMessage = $('#password-error');
        
        const currentPassword = $('#currentPassword').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();

        // Clear any previous error messages
        errorMessage.text("");

        // Validate that new passwords match
        if (newPassword !== confirmPassword) {
            errorMessage.text("New passwords do not match.");
            return;
        }

        // Submit password change request
        $.ajax({
            url: '../actions/update_password.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                showToast(res.message, res.success ? 'success' : 'danger');
                if (res.success) {
                    $('#passwordForm')[0].reset(); // Clear form on success
                }
            },
            error: function() {
                showToast('Unable to connect to the server. Please try again.', 'danger');
            }
        });
    });

    /**
     * Handle address form submission
     * Submits address update via AJAX
     */
    $('#addressForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../actions/update_address.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                showToast(res.message, res.success ? 'success' : 'danger');
                if (res.success) {
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function() {
                showToast('Unable to connect to the server. Please try again.', 'danger');
            }
        });
    });

    /**
     * Display toast notification
     * @param {string} message - Message to display
     * @param {string} type - Bootstrap contextual class (primary, success, danger etc)
     */
    function showToast(message, type = 'primary') {
        const toast = $('#toastContainer');
        toast.removeClass().addClass(`toast align-items-center text-bg-${type} border-0`);
        $('#toastMessage').text(message);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
}); 