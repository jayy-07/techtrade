$(document).ready(function() {
    // Get search query from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('q') || '';

    /**
     * Shows loading spinner and hides product listing
     */
    function showLoading() {
        $('#productListing').hide();
        $('#loadingState').show();
    }

    /**
     * Hides loading spinner and shows product listing
     */
    function hideLoading() {
        $('#loadingState').hide();
        $('#productListing').show();
    }

    /**
     * Fetches and updates product listing based on filters
     * Makes AJAX call to search_products.php with current filter values
     */
    function updateProducts() {
        showLoading();

        // Get current filter values
        const category = $('#categoryFilter').val();
        const brand = $('#brandFilter').val();
        const price = $('#priceFilter').val();
        const sort = $('#sortOptions').val();

        // Make AJAX request
        $.ajax({
            url: '../actions/search_products.php',
            type: 'GET',
            data: {
                q: searchQuery,
                category: category,
                brand: brand,
                price: price,
                sort: sort
            },
            success: function(response) {
                hideLoading();
                $('#productListing').html(response);
            },
            error: function() {
                hideLoading();
                // Show error state with home button
                $('#productListing').html(`
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-exclamation-circle display-1 text-danger mb-3"></i>
                            <h3>Oops! Something went wrong</h3>
                            <p class="text-muted">
                                We encountered an error while searching for products.
                                Please try again later.
                            </p>
                            <a href="home.php" class="btn btn-outline-primary">
                                <i class="bi bi-house"></i> Back to Home
                            </a>
                        </div>
                    </div>
                `);
            }
        });
    }

    /**
     * Resets all filter selections to their default values
     * Updates URL to remove filter parameters while preserving search query
     */
    window.resetFilters = function() {
        // Reset all filters to first option
        $('#categoryFilter').val($('#categoryFilter option:first').val());
        $('#brandFilter').val($('#brandFilter option:first').val());
        $('#priceFilter').val($('#priceFilter option:first').val());
        $('#sortOptions').val($('#sortOptions option:first').val());
        
        // Update URL - keep search query but remove filter params
        const url = new URL(window.location.href);
        const searchQuery = url.searchParams.get('q');
        url.search = searchQuery ? `?q=${searchQuery}` : '';
        window.history.pushState({}, '', url);

        updateProducts();
    }

    // Add event listeners to all filter dropdowns
    $('#categoryFilter, #brandFilter, #priceFilter, #sortOptions').on('change', function() {
        // Update URL with new filter parameters
        const url = new URL(window.location.href);
        
        const category = $('#categoryFilter').val();
        const brand = $('#brandFilter').val();
        const price = $('#priceFilter').val();
        const sort = $('#sortOptions').val();

        url.searchParams.set('category', category);
        url.searchParams.set('brand', brand);
        url.searchParams.set('price', price);
        url.searchParams.set('sort', sort);

        window.history.pushState({}, '', url);

        updateProducts();
    });

    // Load initial products when page loads
    updateProducts();
}); 