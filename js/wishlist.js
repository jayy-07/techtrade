// Wait for the DOM to be fully loaded before executing
$(document).ready(function() {
    /**
     * Displays a toast notification with the given message and type
     * @param {string} message - The message to display in the toast
     * @param {string} type - The bootstrap contextual class for styling (primary, success, danger, etc.)
     */
    function showToast(message, type = 'primary') {
        const toast = $('#toastContainer');
        toast.removeClass().addClass(`toast align-items-center text-bg-${type} border-0`);
        $('#toastMessage').text(message);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Add click event handler for wishlist remove buttons
    $('.wishlist-remove-btn').on('click', function() {
        // Get reference to clicked button and extract necessary data
        const button = $(this);
        const productId = button.data('product-id');
        const card = button.closest('.col');

        // Send AJAX request to remove item from wishlist
        $.ajax({
            url: '../actions/update_wishlist.php',
            method: 'POST',
            data: {
                product_id: productId,
                action: 'remove'
            },
            success: function(response) {
                if (response.success) {
                    // Animate card removal and check if wishlist is empty
                    card.fadeOut(300, function() {
                        $(this).remove();
                        // Reload page if no items left in wishlist
                        if ($('.col').length === 0) {
                            location.reload();
                        }
                    });
                    showToast('Item removed from wishlist', 'success');
                } else {
                    // Show error message if removal failed
                    showToast(response.error || 'Failed to remove item', 'danger');
                }
            },
            error: function() {
                // Show error message if AJAX request failed
                showToast('An error occurred', 'danger');
            }
        });
    });
}); 