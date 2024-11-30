$(document).ready(function() {
    // Initialize phone input
    const phoneInput = intlTelInput(document.querySelector("#phone"), {
        initialCountry: "GH",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        separateDialCode: true,
        autoPlaceholder: "polite",
    });

    // Profile form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        const phoneNumber = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
        const phoneError = $("#phone-error");
        const errorMessage = $('#error-message');
        
        phoneError.text("");
        errorMessage.text("");

        // Phone number validation
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

        // Submit form via AJAX
        $.ajax({
            url: '../actions/update_profile.php',
            type: 'POST',
            data: $(this).serialize() + '&phone=' + encodeURIComponent(phoneNumber),
            success: function(response) {
                const res = JSON.parse(response);
                showToast(res.message, res.success ? 'success' : 'danger');
                if (res.success) {
                    // Optionally refresh the page or update the UI
                    setTimeout(() => window.location.reload(), 1500);
                }
            },
            error: function() {
                showToast('Unable to connect to the server. Please try again.', 'danger');
            }
        });
    });

    // Password form submission
    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        const errorMessage = $('#password-error');
        
        const currentPassword = $('#currentPassword').val();
        const newPassword = $('#newPassword').val();
        const confirmPassword = $('#confirmPassword').val();

        errorMessage.text("");

        // Password match validation
        if (newPassword !== confirmPassword) {
            errorMessage.text("New passwords do not match.");
            return;
        }

        $.ajax({
            url: '../actions/update_password.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                showToast(res.message, res.success ? 'success' : 'danger');
                if (res.success) {
                    $('#passwordForm')[0].reset();
                }
            },
            error: function() {
                showToast('Unable to connect to the server. Please try again.', 'danger');
            }
        });
    });

    // Address form submission
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

    // Function to show toast notifications
    function showToast(message, type = 'primary') {
        const toast = $('#toastContainer');
        toast.removeClass().addClass(`toast align-items-center text-bg-${type} border-0`);
        $('#toastMessage').text(message);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
}); 