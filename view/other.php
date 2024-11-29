<div class="container my-5">
    <div class="row">
        <!-- Product Image Carousel -->
        <div class="col-md-5 mb-2">
            <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/300" class="d-block w-100" alt="Product Image 1">
                    </div>
                    <div class="carousel-item">
                        <img src="https://via.placeholder.com/300" class="d-block w-100" alt="Product Image 2">
                    </div>
                    <div class="carousel-item">
                        <img src="https://via.placeholder.com/300" class="d-block w-100" alt="Product Image 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h2 class="product-title">Product Title</h2>
            <p class="text-muted">Sold by: <strong>Seller Name</strong></p>
            <p class="product-description">This is a brief description of the product. It highlights key features, materials, and any other important details that might attract a buyer. </p>

            <div class="mb-3">
                <h4 class="product-price">$99.99</h4>
            </div>

            <div class="d-grid gap-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="tradeInToggle">
                    <label class="form-check-label" for="tradeInToggle"><i class="bi bi-arrow-left-right"></i> Trade-in your device</label>
                </div>
                <div id="deviceTypeDropdown" class="mt-3" style="display: none;">
                    <label for="deviceType" class="form-label">What device is it?</label>
                    <select id="deviceType" class="form-select">
                        <option selected disabled>Select your device</option>
                        <option value="phone">Phone</option>
                        <option value="tablet">Tablet</option>
                        <option value="laptop">Laptop</option>
                        <option value="smartwatch">Smartwatch</option>
                    </select>
                </div>

                <!-- Dropdown for Device Condition -->
                <div id="deviceConditionDropdown" class="mt-3" style="display: none;">
                    <label for="deviceCondition" class="form-label">Is it in good condition?</label>
                    <select id="deviceCondition" class="form-select">
                        <option selected disabled>Select condition</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                </div>

                <button id="addToCartButton" type="button" class="btn btn-primary">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="tradeInModal" tabindex="-1" aria-labelledby="tradeInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Centering the modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tradeInModalLabel">Add to Cart - Trade-In Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Trade-In Toggle -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="modalTradeInToggle">
                    <label class="form-check-label" for="modalTradeInToggle">
                        <i class="bi bi-arrow-left-right"></i> Trade-in your device
                    </label>
                </div>

                <!-- Device Type Dropdown -->
                <div id="modalDeviceTypeDropdown" class="mt-3" style="display: none;">
                    <label for="modalDeviceType" class="form-label">Select your device type:</label>
                    <select id="modalDeviceType" class="form-select">
                        <option selected disabled>Select your device</option>
                        <option value="phone">Phone</option>
                        <option value="tablet">Tablet</option>
                        <option value="laptop">Laptop</option>
                        <option value="smartwatch">Smartwatch</option>
                    </select>
                </div>

                <!-- Device Condition Dropdown -->
                <div id="modalDeviceConditionDropdown" class="mt-3" style="display: none;">
                    <label for="modalDeviceCondition" class="form-label">Device Condition:</label>
                    <select id="modalDeviceCondition" class="form-select">
                        <option selected disabled>Select condition</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmTradeInButton" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h4>Other Sellers on TechTrade</h4>
    <div class="other-sellers-container">
        <button class="scroll-btn prev-btn" style="display: none;" onclick="scrollSellersLeft()">
            <span>&#10094;</span>
        </button>
        <div class="other-sellers-row">
            <!-- Sample cards -->
            <div class="seller-card">
                <p><strong>Price:</strong> $89.99</p>
                <p><strong>Condition:</strong> Like New</p>
                <p><strong>Seller:</strong> BestSeller123</p>
                <button id="addToCartButton-seller" type="button" class="btn btn-primary">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
        <!-- Add more cards as needed -->
    </div>
    <button class="scroll-btn next-btn" onclick="scrollSellersRight()">
        <span>&#10095;</span> <!-- Right arrow symbol -->
    </button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tradeInToggle = document.getElementById("tradeInToggle");
        const deviceTypeDropdown = document.getElementById("deviceTypeDropdown");
        const deviceConditionDropdown = document.getElementById("deviceConditionDropdown");
        const deviceType = document.getElementById("deviceType");
        const deviceCondition = document.getElementById("deviceCondition");
        const addToCartButton = document.getElementById("addToCartButton");

        tradeInToggle.addEventListener("change", function() {
            deviceTypeDropdown.style.display = this.checked ? "block" : "none";
            deviceConditionDropdown.style.display = "none";

            // Always disable the button when toggle is checked
            addToCartButton.disabled = this.checked;

            // Reset selections when toggle is turned off
            if (!this.checked) {
                deviceType.value = "";
                deviceCondition.value = "";
            }
        });

        deviceType.addEventListener("change", function() {
            deviceConditionDropdown.style.display = this.value ? "block" : "none";
            if (!this.value) {
                deviceCondition.value = "";
            }
            checkDropdowns();
        });

        deviceCondition.addEventListener("change", checkDropdowns);

        function checkDropdowns() {
            addToCartButton.disabled = tradeInToggle.checked &&
                (!deviceType.value || !deviceCondition.value);
        }
    });
</script>
<script>
    // JavaScript for horizontal scrolling of seller row
    function scrollSellersRight() {
        const container = document.querySelector(".other-sellers-row");
        container.scrollLeft += 300; // Adjust scroll distance as needed
    }

    function scrollSellersLeft() {
        const container = document.querySelector(".other-sellers-row");
        container.scrollLeft -= 300; // Adjust scroll distance as needed
    }

    // Show or hide scroll buttons based on scroll position
    function updateSellersScrollButtons() {
        const container = document.querySelector(".other-sellers-row");
        const prevBtn = document.querySelector(".prev-btn");
        const nextBtn = document.querySelector(".next-btn");

        // Show 'prev' button if content has scrolled right
        prevBtn.style.display = container.scrollLeft > 0 ? "block" : "none";

        // Show 'next' button if content is still scrollable to the right
        const maxScrollLeft = container.scrollWidth - container.clientWidth;
        nextBtn.style.display = container.scrollLeft < maxScrollLeft ? "block" : "none";
    }

    // Call updateSellersScrollButtons immediately after DOM is ready
    document.addEventListener("DOMContentLoaded", updateSellersScrollButtons);

    // Add scroll event listener to update button visibility on manual scroll
    document.querySelector(".other-sellers-row").addEventListener("scroll", updateSellersScrollButtons);

    // Update on window resize
    window.addEventListener("resize", updateSellersScrollButtons);
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get elements
        const addToCartButton = document.getElementById("addToCartButton-seller");
        const modalTradeInToggle = document.getElementById("modalTradeInToggle");
        const modalDeviceTypeDropdown = document.getElementById("modalDeviceTypeDropdown");
        const modalDeviceConditionDropdown = document.getElementById("modalDeviceConditionDropdown");
        const modalDeviceType = document.getElementById("modalDeviceType");
        const modalDeviceCondition = document.getElementById("modalDeviceCondition");
        const confirmTradeInButton = document.getElementById("confirmTradeInButton");

        // Initialize Bootstrap Modal
        const tradeInModal = new bootstrap.Modal(document.getElementById("tradeInModal"));

        // Open modal on "Add to Cart" button click
        addToCartButton.addEventListener("click", function() {
            tradeInModal.show();
        });

        // Trade-In Toggle Event
        modalTradeInToggle.addEventListener("change", function() {
            if (modalTradeInToggle.checked) {
                modalDeviceTypeDropdown.style.display = "block"; // Show device type dropdown
                confirmTradeInButton.disabled = true; // Initially disable confirm button
            } else {
                modalDeviceTypeDropdown.style.display = "none";
                modalDeviceConditionDropdown.style.display = "none";
                modalDeviceType.value = ""; // Reset selections
                modalDeviceCondition.value = "";
                confirmTradeInButton.disabled = false; // Enable confirm button if toggle is off
            }
            checkDropdowns(); // Check dropdowns to manage button state
        });

        // Show condition dropdown after selecting device type
        modalDeviceType.addEventListener("change", function() {
            if (modalDeviceType.value) {
                modalDeviceConditionDropdown.style.display = "block";
            } else {
                modalDeviceConditionDropdown.style.display = "none";
                modalDeviceCondition.value = ""; // Reset condition selection
            }
            checkDropdowns();
        });

        // Enable confirm button only if both dropdowns are selected when toggle is on
        modalDeviceCondition.addEventListener("change", checkDropdowns);

        function checkDropdowns() {
            if (modalTradeInToggle.checked && modalDeviceType.value && modalDeviceCondition.value) {
                confirmTradeInButton.disabled = false;
            } else if (modalTradeInToggle.checked) {
                confirmTradeInButton.disabled = true;
            }
        }

        // Handle Confirm button click
        confirmTradeInButton.addEventListener("click", function() {
            // Execute add-to-cart functionality here (reuse or modify as needed)
            console.log("Item added to cart with trade-in details");

            // Close the modal
            tradeInModal.hide();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get elements
        const addToCartButton = document.getElementById("addToCartButton-seller");
        const modalTradeInToggle = document.getElementById("modalTradeInToggle");
        const modalDeviceTypeDropdown = document.getElementById("modalDeviceTypeDropdown");
        const modalDeviceConditionDropdown = document.getElementById("modalDeviceConditionDropdown");
        const modalDeviceType = document.getElementById("modalDeviceType");
        const modalDeviceCondition = document.getElementById("modalDeviceCondition");
        const confirmTradeInButton = document.getElementById("confirmTradeInButton");

        // Initialize Bootstrap Modal
        const tradeInModal = new bootstrap.Modal(document.getElementById("tradeInModal"));

        // Open modal on "Add to Cart" button click
        addToCartButton.addEventListener("click", function() {
            // Reset modal state when opening
            modalTradeInToggle.checked = false;
            modalDeviceTypeDropdown.style.display = "none";
            modalDeviceConditionDropdown.style.display = "none";
            modalDeviceType.value = "";
            modalDeviceCondition.value = "";
            confirmTradeInButton.disabled = false;

            tradeInModal.show();
        });

        // Trade-In Toggle Event
        modalTradeInToggle.addEventListener("change", function() {
            if (this.checked) {
                modalDeviceTypeDropdown.style.display = "block";
                confirmTradeInButton.disabled = true; // Disable button when toggle is on
            } else {
                modalDeviceTypeDropdown.style.display = "none";
                modalDeviceConditionDropdown.style.display = "none";
                modalDeviceType.value = "";
                modalDeviceCondition.value = "";
                confirmTradeInButton.disabled = false;
            }
        });

        // Show condition dropdown after selecting device type
        modalDeviceType.addEventListener("change", function() {
            if (this.value) {
                modalDeviceConditionDropdown.style.display = "block";
            } else {
                modalDeviceConditionDropdown.style.display = "none";
                modalDeviceCondition.value = "";
            }
            checkDropdowns();
        });

        // Check dropdowns on condition change
        modalDeviceCondition.addEventListener("change", checkDropdowns);

        function checkDropdowns() {
            if (modalTradeInToggle.checked) {
                confirmTradeInButton.disabled = !modalDeviceType.value || !modalDeviceCondition.value;
            }
        }

        // Handle Confirm button click
        confirmTradeInButton.addEventListener("click", function() {
            console.log("Item added to cart with trade-in details");
            tradeInModal.hide();
        });
    });
</script>