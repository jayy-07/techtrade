$(document).ready(function() {
    // Handle quantity changes
    $('.quantity-btn').on('click', function() {
        const input = $(this).closest('.input-group').find('.quantity-input');
        const currentValue = parseInt(input.val());
        const action = $(this).data('action');
        const maxStock = parseInt(input.attr('max'));
        
        let newValue = action === 'increase' ? currentValue + 1 : currentValue - 1;
        
        if (newValue < 1 || newValue > maxStock) return;
        
        input.val(newValue);
        updateCartItem(input);
    });

    $('.quantity-input').on('change', function() {
        updateCartItem($(this));
    });

    function updateCartItem(input) {
        const cartItemId = input.closest('.cart-item').data('cart-item-id');
        const quantity = parseInt(input.val());
        const maxStock = parseInt(input.attr('max'));

        if (quantity < 1 || quantity > maxStock) {
            input.val(quantity < 1 ? 1 : maxStock);
            return;
        }

        $.ajax({
            url: '../actions/update_cart_quantity.php',
            method: 'POST',
            data: {
                cart_item_id: cartItemId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    location.reload(); // Refresh to update totals
                } else {
                    showToast(response.message || 'Error updating quantity', 'danger');
                }
            },
            error: function() {
                showToast('Failed to update quantity', 'danger');
            }
        });
    }

    // Handle item removal
    $('.remove-item').on('click', function() {
        const cartItem = $(this).closest('.cart-item');
        const cartItemId = cartItem.data('cart-item-id');
        const productName = cartItem.find('.card-title').text();
        
        // Set the product name in the modal
        $('#deleteItemName').text(productName);
        
        // Store the cart item ID for the confirmation button
        $('#confirmDeleteItem').data('cart-item-id', cartItemId);
        
        // Show the modal
        $('#deleteCartItemModal').modal('show');
    });

    // Handle delete confirmation
    $('#confirmDeleteItem').on('click', function() {
        const cartItemId = $(this).data('cart-item-id');
        
        $.ajax({
            url: '../actions/remove_cart_item.php',
            method: 'POST',
            data: { cart_item_id: cartItemId },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    showToast(response.message || 'Error removing item', 'danger');
                }
            },
            error: function() {
                showToast('Failed to remove item', 'danger');
            }
        });

        // Hide the modal
        $('#deleteCartItemModal').modal('hide');
    });

    // Toast notification function
    function showToast(message, type = 'primary') {
        const toastContainer = $('#toastContainer');
        const toastMessage = $('#toastMessage');
        
        toastContainer.removeClass('text-bg-primary text-bg-success text-bg-danger')
            .addClass(`text-bg-${type}`);
        toastMessage.text(message);
        
        const toast = new bootstrap.Toast(toastContainer[0]);
        toast.show();
    }

    // Add checkout button handler
    $('#checkoutBtn').on('click', function() {
        // Check if user is logged in (we'll handle this in PHP)
        $.ajax({
            url: '../actions/check_login.php',
            method: 'GET',
            success: function(response) {
                if (response.logged_in) {
                    window.location.href = 'checkout.php';
                } else {
                    // Store the intended destination
                    sessionStorage.setItem('redirect_after_login', 'checkout.php');
                    window.location.href = 'login.php';
                }
            },
            error: function() {
                showToast('An error occurred. Please try again.', 'danger');
            }
        });
    });
}); 