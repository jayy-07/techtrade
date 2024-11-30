document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('payButton');
    const checkoutForm = document.getElementById('checkoutForm');
    
    payButton.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!checkoutForm.checkValidity()) {
            checkoutForm.reportValidity();
            return;
        }

        // Get form data
        const formData = new FormData(checkoutForm);
        
        // Show loading state
        payButton.disabled = true;
        payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

        // Initialize payment
        fetch('../actions/initialize_payment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Payment initialization response:', data);
            if (data.success && data.authorization_url) {
                // Redirect to Paystack payment page
                window.location.href = data.authorization_url;
            } else {
                showToast('error', data.message || 'Payment initialization failed');
                // Reset button state
                payButton.disabled = false;
                payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('error', 'An error occurred while processing your payment');
            payButton.disabled = false;
            payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i>Proceed to Payment';
        });
    });

    function showToast(type, message) {
        const toast = document.getElementById('toastContainer');
        const toastMessage = document.getElementById('toastMessage');
        
        toast.classList.remove('text-bg-primary', 'text-bg-danger');
        toast.classList.add(type === 'error' ? 'text-bg-danger' : 'text-bg-primary');
        
        toastMessage.textContent = message;
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
}); 