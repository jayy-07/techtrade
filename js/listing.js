$(document).ready(function() {
    // Function to show loading state
    function showLoading() {
        $('#productListing').hide();
        $('#loadingState').show();
    }

    // Function to hide loading state
    function hideLoading() {
        $('#loadingState').hide();
        $('#productListing').show();
    }

    // Function to update product listing
    function updateProducts() {
        showLoading();

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
                $('#productListing').html(`
                    <div class="col-12 text-center py-5">
                        <div class="empty-state">
                            <i class="bi bi-exclamation-circle display-1 text-danger mb-3"></i>
                            <h3>Oops! Something went wrong</h3>
                            <p class="text-muted">
                                We encountered an error while loading the products.
                                Please try again later.
                            </p>
                            <button class="btn btn-outline-primary" onclick="resetFilters()">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                            </button>
                        </div>
                    </div>
                `);
            }
        });
    }

    // Function to reset all filters
    window.resetFilters = function() {
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

    // Event listeners for filters
    $('#categoryFilter, #brandFilter, #priceFilter, #sortOptions').on('change', function() {
        // Update URL with new parameters
        const url = new URL(window.location.href);
        
        const category = $('#categoryFilter').val();
        const brand = $('#brandFilter').val();
        const price = $('#priceFilter').val();
        const sort = $('#sortOptions').val();

        // Update URL parameters
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

    // Handle browser back/forward buttons
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

    // Initialize tooltips if you're using them
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}); 