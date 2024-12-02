/**
 * Initialize checkout functionality when DOM is loaded
 * Handles payment form submission and processing via Paystack
 */
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('payButton');
    const checkoutForm = document.getElementById('checkoutForm');
    
    /**
     * Handle payment button click
     * Validates form, shows loading state, and initializes Paystack payment
     */
    payButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validate form before proceeding
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }

        // Get form data for payment request
        const formData = new FormData(checkoutForm);
        
        // Update button to loading state
        payButton.disabled = true;
        payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Initialize Paystack payment via backend
        fetch('../actions/initialize_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment initialization response:', data);
            if (data.success && data.authorization_url) {
                // Redirect to Paystack checkout page on success
                window.location.href = data.authorization_url;
            } else {
                // Show error and reset button on failure
                showToast('error', data.message || 'Payment initialization failed');
                payButton.disabled = false;
                payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
            }
        })
        .catch(error => {
            // Handle network/server errors
            console.error('Error:', error);
            showToast('error', 'An error occurred while processing your payment');
            payButton.disabled = false;
            payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
        });
    });

    /**
     * Display toast notification
     * @param {string} type - The type of toast ('error' or other)
     * @param {string} message - The message to display
     */
    function showToast(type, message) {
        const toast = document.getElementById('toastContainer');
        const toastMessage = document.getElementById('toastMessage');
        
        // Set toast styling based on type
        toast.classList.remove('text-bg-primary', 'text-bg-danger');
        toast.classList.add(type === 'error' ? 'text-bg-danger' : 'text-bg-primary');
        
        // Set message and show toast
        toastMessage.textContent = message;
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
}); 