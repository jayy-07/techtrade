$(document).ready(function() {
    $('.view-order').on('click', function() {
        const orderId = $(this).data('order-id');
        
        $.ajax({
            url: '../actions/get_order_details.php',
            method: 'GET',
            data: { order_id: orderId },
            success: function(response) {
                $('#orderDetailsContent').html(response);
            },
            error: function() {
                $('#orderDetailsContent').html('<div class="alert alert-danger">Failed to load order details</div>');
            }
        });
    });
}); 