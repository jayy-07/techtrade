$(document).ready(function() {
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
     * Fetches and updates product listing based on current filter values
     * Shows loading state while request is in progress
     */
    function updateProducts() {
        showLoading();

        // Get current filter values
        const category = $('#categoryFilter').val();
        const brand = $('#brandFilter').val();
        const price = $('#priceFilter').val();
        const sort = $('#sortOptions').val();

        $.ajax({
            url: '../actions/get_filtered_products.php',
            type: 'GET',
            data: {
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
                // Show error state with reset option
                $('#productListing').html(`
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-exclamation-circle display-1 text-danger mb-3"></i>
                            <h3>Oops! Something went wrong</h3>
                            <p class="text-muted">
                                We encountered an error while loading the products.
                                Please try again later.
                            </p>
                            <button class="btn btn-techtrade-outline" onclick="resetFilters()">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                `);
            }
        });
    }

    /**
     * Resets all filters to their default values and updates URL
     * Made available globally for error state reset button
     */
    window.resetFilters = function() {
        // Reset all filter dropdowns to first option
        $('#categoryFilter').val($('#categoryFilter option:first').val());
        $('#brandFilter').val($('#brandFilter option:first').val());
        $('#priceFilter').val($('#priceFilter option:first').val());
        $('#sortOptions').val($('#sortOptions option:first').val());
        
        // Update URL to remove parameters
        const url = new URL(window.location.href);
        url.searchParams.delete('category');
        url.searchParams.delete('brand');
        url.searchParams.delete('price');
        url.searchParams.delete('sort');
        window.history.pushState({}, '', url);

        updateProducts();
    }

    // Event listeners for all filter changes
    $('#categoryFilter, #brandFilter, #priceFilter, #sortOptions').on('change', function() {
        // Update URL with new parameters
        const url = new URL(window.location.href);
        
        // Get current filter values
        const category = $('#categoryFilter').val();
        const brand = $('#brandFilter').val();
        const price = $('#priceFilter').val();
        const sort = $('#sortOptions').val();

        // Update URL parameters - only add if value exists
        if (category) url.searchParams.set('category', category);
        else url.searchParams.delete('category');
        
        if (brand) url.searchParams.set('brand', brand);
        else url.searchParams.delete('brand');
        
        if (price) url.searchParams.set('price', price);
        else url.searchParams.delete('price');
        
        if (sort && sort !== 'default') url.searchParams.set('sort', sort);
        else url.searchParams.delete('sort');

        // Update URL without reloading the page
        window.history.pushState({}, '', url);

        updateProducts();
    });

    /**
     * Handles browser back/forward navigation
     * Updates filters and product listing based on URL parameters
     */
    window.addEventListener('popstate', function() {
        // Get parameters from URL
        const urlParams = new URLSearchParams(window.location.search);
        
        // Update filter selections based on URL parameters
        $('#categoryFilter').val(urlParams.get('category') || '');
        $('#brandFilter').val(urlParams.get('brand') || '');
        $('#priceFilter').val(urlParams.get('price') || '');
        $('#sortOptions').val(urlParams.get('sort') || 'default');

        updateProducts();
    });

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}); 