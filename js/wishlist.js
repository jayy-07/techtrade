$(document).ready(function() {
    function showToast(message, type = 'primary') {
        const toast = $('#toastContainer');
        toast.removeClass().addClass(`toast align-items-center text-bg-${type} border-0`);
        $('#toastMessage').text(message);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    $('.wishlist-btn').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const card = button.closest('.col');

        $.ajax({
            url: '../actions/update_wishlist.php',
            method: 'POST',
            data: {
                product_id: productId,
                action: 'remove'
            },
            success: function(response) {
                if (response.success) {
                    card.fadeOut(300, function() {
                        $(this).remove();
                        if ($('.col').length === 0) {
                            location.reload();
                        }
                    });
                    showToast('Item removed from wishlist', 'success');
                } else {
                    showToast(response.error || 'Failed to remove item', 'danger');
                }
            },
            error: function() {
                showToast('An error occurred', 'danger');
            }
        });
    });
}); 