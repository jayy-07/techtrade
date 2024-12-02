$(document).ready(function() {
    /**
     * Handle quantity changes via buttons
     * Increases or decreases quantity within stock limits
     */
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

    /**
     * Handle direct quantity input changes
     */
    $('.quantity-input').on('change', function() {
        updateCartItem($(this));
    });

    /**
     * Update cart item quantity via AJAX
     * Validates quantity is within stock limits
     * Reloads page on success to update totals
     * @param {jQuery} input - The quantity input element
     */
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

    /**
     * Handle cart item removal button click
     * Shows confirmation modal with item details
     */
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

    /**
     * Handle delete confirmation in modal
     * Removes item via AJAX and reloads on success
     */
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

    /**
     * Display toast notification
     * @param {string} message - Message to display
     * @param {string} type - Bootstrap contextual class (primary/success/danger)
     */
    function showToast(message, type = 'primary') {
        const toastContainer = $('#toastContainer');
        const toastMessage = $('#toastMessage');
        
        toastContainer.removeClass('text-bg-primary text-bg-success text-bg-danger')
            .addClass(`text-bg-${type}`);
        toastMessage.text(message);
        
        const toast = new bootstrap.Toast(toastContainer[0]);
        toast.show();
    }

    /**
     * Handle checkout button click
     * Checks login status and redirects appropriately
     */
    $('#checkoutBtn').on('click', function() {
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

    /**
     * Calculate trade-in value based on condition and usage duration
     * @param {string} condition - Device condition (Excellent/Good/Fair/Poor)
     * @param {string} duration - Usage duration category
     * @param {number} price - Original price
     * @returns {number} Calculated trade-in value
     */
    function calculateTradeInValue(condition, duration, price) {
        const conditionMultiplier = {
            Excellent: 0.8,
            Good: 0.6,
            Fair: 0.4,
            Poor: 0.2,
        };
        const usageMultiplier = {
            "Less than 6 months": 1.0,
            "6-12 months": 0.9,
            "1-2 years": 0.7,
            "2-3 years": 0.5,
            "More than 3 years": 0.3,
        };

        const conditionValue = conditionMultiplier[condition] || 0;
        const usageValue = usageMultiplier[duration] || 0;

        return price * conditionValue * usageValue;
    }
}); 