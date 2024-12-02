/**
 * Initialize event handlers when document is ready
 */
$(document).ready(function() {
    /**
     * Handle click event on view-order buttons
     * Fetches and displays order details in modal
     */
    $('.view-order').on('click', function() {
        // Get order ID from data attribute
        const orderId = $(this).data('order-id');
        
        // Fetch order details via AJAX
        $.ajax({
            url: '../actions/get_order_details.php',
            method: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                // Display order details in modal
                $('#orderDetailsContent').html(response);
            },
            error: function() {
                // Show error message if request fails
                $('#orderDetailsContent').html('<div class="alert alert-danger">Failed to load order details</div>');
            }
        });
    });
}); 