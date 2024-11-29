$(document).ready(function() {
    // Get search query from URL
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('q') || '';

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

    // Function to reset all filters
    window.resetFilters = function() {
        $('#categoryFilter').val($('#categoryFilter option:first').val());
        $('#brandFilter').val($('#brandFilter option:first').val());
        $('#priceFilter').val($('#priceFilter option:first').val());
        $('#sortOptions').val($('#sortOptions option:first').val());
        
        // Update URL to remove filter parameters but keep search query
        const url = new URL(window.location.href);
        const searchQuery = url.searchParams.get('q');
        url.search = searchQuery ? `?q=${searchQuery}` : '';
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

        url.searchParams.set('category', category);
        url.searchParams.set('brand', brand);
        url.searchParams.set('price', price);
        url.searchParams.set('sort', sort);

        window.history.pushState({}, '', url);

        updateProducts();
    });

    // Initial call to update products
    updateProducts();
}); 