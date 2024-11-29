$(document).ready(function () {
    // Initialize Bootstrap Modal
    const tradeInModal = new bootstrap.Modal($("#tradeInModal")[0]);

    // Show Toast Function
    function showToast(message, type = "primary") {
        const toastContainer = $("#toastContainer");
        const toastMessage = $("#toastMessage");

        if (!toastContainer.length || !toastMessage.length) {
            console.error("Toast container or message element not found.");
            return;
        }

        toastContainer.removeClass("text-bg-primary text-bg-success text-bg-danger");
        toastContainer.addClass(`text-bg-${type}`);
        toastMessage.text(message);

        const toast = new bootstrap.Toast(toastContainer[0]);
        toast.show();
    }

    // Open modal on "Add to Cart" button click
    $('[data-bs-target="#tradeInModal"]').on("click", function () {
        const button = $(this);

        // Attach product and seller info to the confirm button
        $("#confirmTradeInButton").data({
            productId: button.data("product-id"),
            sellerId: button.data("seller-id"),
            productName: button.data("product-name"),
            sellerName: button.data("seller-name"),
            price: button.data("price"),
        });

        // Reset modal state
        $("#modalTradeInToggle").prop("checked", false);
        $("#modalDeviceTypeDropdown, #modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField").hide();
        $("#modalDeviceType, #modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice").val("");
        $("#confirmTradeInButton").prop("disabled", false);

        tradeInModal.show();
    });

    // Trade-In Toggle Event
    $("#modalTradeInToggle").on("change", function () {
        if ($(this).is(":checked")) {
            $("#modalDeviceTypeDropdown").show();
            $("#confirmTradeInButton").prop("disabled", true);
        } else {
            $("#modalDeviceTypeDropdown, #modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField").hide();
            $("#modalDeviceType, #modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice").val("");
            $("#confirmTradeInButton").prop("disabled", false);
        }
    });

    // Show condition dropdown after selecting device type
    $("#modalDeviceType").on("change", function () {
        if ($(this).val()) {
            $("#modalDeviceConditionDropdown").show();
        } else {
            $("#modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField").hide();
            $("#modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice").val("");
        }
        checkFormCompletion();
    });

    // Show additional fields after selecting condition
    $("#modalDeviceCondition").on("change", function () {
        if ($(this).val()) {
            $("#usageDurationField, #purchasePriceField").show();
        } else {
            $("#usageDurationField, #purchasePriceField").hide();
            $("#modalUsageDuration, #modalPurchasePrice").val("");
        }
        checkFormCompletion();
    });

    // Enable confirm button when all fields are filled
    $("#modalUsageDuration, #modalPurchasePrice").on("input change", function () {
        checkFormCompletion();
    });

    // Check form completion to enable or disable confirm button
    function checkFormCompletion() {
        if ($("#modalTradeInToggle").is(":checked")) {
            const deviceType = $("#modalDeviceType").val();
            const deviceCondition = $("#modalDeviceCondition").val();
            const usageDuration = $("#modalUsageDuration").val();
            const purchasePrice = parseFloat($("#modalPurchasePrice").val());
            const productPrice = parseFloat($("#confirmTradeInButton").data("price"));

            // Calculate trade-in value
            const tradeInValue = calculateTradeInValue(deviceCondition, usageDuration, purchasePrice);

            const isComplete =
                deviceType &&
                deviceCondition &&
                usageDuration &&
                purchasePrice > 0 &&
                tradeInValue <= productPrice && // Ensure trade-in value does not exceed product price
                (productPrice - tradeInValue) > 0; // Ensure final price is positive

            $("#confirmTradeInButton").prop("disabled", !isComplete);
        } else {
            $("#confirmTradeInButton").prop("disabled", false);
        }
    }

    // Function to calculate trade-in value
    function calculateTradeInValue(condition, duration, price) {
        const conditionMultiplier = {
            'Excellent': 0.8,
            'Good': 0.6,
            'Fair': 0.4,
            'Poor': 0.2
        };
        const usageMultiplier = {
            'Less than 6 months': 1.0,
            '6-12 months': 0.9,
            '1-2 years': 0.7,
            '2-3 years': 0.5,
            'More than 3 years': 0.3
        };

        const conditionValue = conditionMultiplier[condition] || 0;
        const usageValue = usageMultiplier[duration] || 0;

        return price * conditionValue * usageValue;
    }

    // Handle Confirm button click for Add to Cart
    $("#confirmTradeInButton").on("click", function () {
        const button = $(this);
        const data = {
            product_id: button.data("productId"),
            seller_id: button.data("sellerId"),
            product_name: button.data("productName"),
            seller_name: button.data("sellerName"),
            price: button.data("price"),
            trade_in: $("#modalTradeInToggle").is(":checked"),
            device_type: $("#modalDeviceType").val(),
            device_condition: $("#modalDeviceCondition").val(),
            usage_duration: $("#modalUsageDuration").val(),
            purchase_price: $("#modalPurchasePrice").val(),
        };

        $.ajax({
            url: "../actions/add_to_cart.php",
            method: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    showToast("Item added to cart successfully!", "success");
                } else {
                    showToast(response.error || "Failed to add item to cart.", "danger");
                }
            },
            error: function () {
                showToast("An error occurred while adding to cart.", "danger");
            },
        });

        tradeInModal.hide();
    });

    // Horizontal scrolling for seller row
    function scrollSellersRight() {
        $(".other-sellers-row").animate({ scrollLeft: "+=300" }, 300);
    }

    function scrollSellersLeft() {
        $(".other-sellers-row").animate({ scrollLeft: "-=300" }, 300);
    }

    $(".prev-btn").on("click", scrollSellersLeft);
    $(".next-btn").on("click", scrollSellersRight);

    // Show or hide scroll buttons based on scroll position
    function updateSellersScrollButtons() {
        const container = $(".other-sellers-row");
        const maxScrollLeft = container[0].scrollWidth - container[0].clientWidth;

        $(".prev-btn").toggle(container.scrollLeft() > 0);
        $(".next-btn").toggle(container.scrollLeft() < maxScrollLeft);
    }

    $(".other-sellers-row").on("scroll", updateSellersScrollButtons);
    $(window).on("resize", updateSellersScrollButtons);

    // Initial check for scroll buttons
    updateSellersScrollButtons();
});
