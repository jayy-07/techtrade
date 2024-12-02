$(document).ready(function () {
  // Initialize Bootstrap Modal for trade-in functionality
  const tradeInModal = new bootstrap.Modal($("#tradeInModal")[0]);

  /**
   * Shows a toast notification with the specified message and type
   * @param {string} message - The message to display in the toast
   * @param {string} type - The type of toast (primary, success, or danger)
   */
  function showToast(message, type = "primary") {
    const toastContainer = $("#toastContainer");
    const toastMessage = $("#toastMessage");

    if (!toastContainer.length || !toastMessage.length) {
      console.error("Toast container or message element not found.");
      return;
    }

    toastContainer.removeClass(
      "text-bg-primary text-bg-success text-bg-danger"
    );
    toastContainer.addClass(`text-bg-${type}`);
    toastMessage.text(message);

    const toast = new bootstrap.Toast(toastContainer[0]);
    toast.show();
  }

  // Event handler for opening the trade-in modal when "Add to Cart" is clicked
  $('[data-bs-target="#tradeInModal"]').on("click", function () {
    const button = $(this);

    // Store product and seller information in the confirm button's data attributes
    $("#confirmTradeInButton").data({
      productId: button.data("product-id"),
      sellerId: button.data("seller-id"),
      productName: button.data("product-name"),
      sellerName: button.data("seller-name"),
      price: button.data("price"),
    });

    // Reset all form fields and hide conditional elements
    $("#modalTradeInToggle").prop("checked", false);
    $(
      "#modalDeviceTypeDropdown, #modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField"
    ).hide();
    $(
      "#modalDeviceType, #modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice"
    ).val("");
    $("#confirmTradeInButton").prop("disabled", false);

    tradeInModal.show();
  });

  // Handle trade-in toggle checkbox changes
  $("#modalTradeInToggle").on("change", function () {
    if ($(this).is(":checked")) {
      $("#modalDeviceTypeDropdown").show();
      $("#confirmTradeInButton").prop("disabled", true);
    } else {
      // Reset and hide all trade-in related fields
      $(
        "#modalDeviceTypeDropdown, #modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField"
      ).hide();
      $(
        "#modalDeviceType, #modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice"
      ).val("");
      $("#confirmTradeInButton").prop("disabled", false);
    }
  });

  // Show device condition dropdown when device type is selected
  $("#modalDeviceType").on("change", function () {
    if ($(this).val()) {
      $("#modalDeviceConditionDropdown").show();
    } else {
      // Reset and hide dependent fields
      $(
        "#modalDeviceConditionDropdown, #usageDurationField, #purchasePriceField"
      ).hide();
      $("#modalDeviceCondition, #modalUsageDuration, #modalPurchasePrice").val(
        ""
      );
    }
    checkFormCompletion();
  });

  // Show additional fields when device condition is selected
  $("#modalDeviceCondition").on("change", function () {
    if ($(this).val()) {
      $("#usageDurationField, #purchasePriceField").show();
    } else {
      // Reset and hide dependent fields
      $("#usageDurationField, #purchasePriceField").hide();
      $("#modalUsageDuration, #modalPurchasePrice").val("");
    }
    checkFormCompletion();
  });

  // Validate form on input changes for usage duration and purchase price
  $("#modalUsageDuration, #modalPurchasePrice").on("input change", function () {
    checkFormCompletion();
  });

  /**
   * Validates the trade-in form and enables/disables the confirm button
   * Calculates trade-in value and ensures it's valid relative to product price
   */
  function checkFormCompletion() {
    if ($("#modalTradeInToggle").is(":checked")) {
      const deviceType = $("#modalDeviceType").val();
      const deviceCondition = $("#modalDeviceCondition").val();
      const usageDuration = $("#modalUsageDuration").val();
      const purchasePrice = parseFloat($("#modalPurchasePrice").val());
      const productPrice = parseFloat($("#confirmTradeInButton").data("price"));

      // Calculate potential trade-in value
      const tradeInValue = calculateTradeInValue(
        deviceCondition,
        usageDuration,
        purchasePrice
      );

      // Validate all conditions are met
      const isComplete =
        deviceType &&
        deviceCondition &&
        usageDuration &&
        purchasePrice > 0 &&
        tradeInValue <= productPrice && // Trade-in value must not exceed product price
        productPrice - tradeInValue > 0; // Final price must be positive

      $("#confirmTradeInButton").prop("disabled", !isComplete);
    } else {
      $("#confirmTradeInButton").prop("disabled", false);
    }
  }

  /**
   * Calculates the trade-in value based on device condition, usage duration, and original purchase price
   * @param {string} condition - The condition of the device (Excellent, Good, Fair, Poor)
   * @param {string} duration - The usage duration category
   * @param {number} price - The original purchase price
   * @returns {number} The calculated trade-in value
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

  // Handle form submission when confirm button is clicked
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

    // Send AJAX request to add item to cart
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

  // Horizontal scrolling functionality for seller listings
  function scrollSellersRight() {
    $(".other-sellers-row").animate({ scrollLeft: "+=300" }, 300);
  }

  function scrollSellersLeft() {
    $(".other-sellers-row").animate({ scrollLeft: "-=300" }, 300);
  }

  // Attach scroll handlers to navigation buttons
  $(".prev-btn").on("click", scrollSellersLeft);
  $(".next-btn").on("click", scrollSellersRight);

  /**
   * Updates the visibility of scroll buttons based on scroll position
   * Hides/shows buttons when reaching start/end of scrollable content
   */
  function updateSellersScrollButtons() {
    const container = $(".other-sellers-row");
    const maxScrollLeft = container[0].scrollWidth - container[0].clientWidth;

    $(".prev-btn").toggle(container.scrollLeft() > 0);
    $(".next-btn").toggle(container.scrollLeft() < maxScrollLeft);
  }

  // Update scroll buttons on scroll and window resize
  $(".other-sellers-row").on("scroll", updateSellersScrollButtons);
  $(window).on("resize", updateSellersScrollButtons);

  // Initial scroll buttons visibility check
  updateSellersScrollButtons();

  // Wishlist functionality
  $(".wishlist-btn").on("click", function (e) {
    e.preventDefault();
    const button = $(this);
    const productId = button.data("product-id");
    const icon = button.find("i");
    const action = icon.hasClass("bi-heart-fill") ? "remove" : "add";

    // Send AJAX request to update wishlist
    $.ajax({
      url: "../actions/update_wishlist.php",
      method: "POST",
      data: {
        product_id: productId,
        action: action,
      },
      success: function (response) {
        if (response.success) {
          icon.toggleClass("bi-heart bi-heart-fill text-danger");
          showToast(
            action === "add" ? "Added to wishlist!" : "Removed from wishlist",
            "success"
          );
        } else {
          showToast(response.error || "Failed to update wishlist", "danger");
        }
      },
      error: function () {
        showToast("An error occurred", "danger");
      },
    });
  });

  // Read More/Less functionality for product description
  const content = $(".description-content");
  const readMoreLink = $(".read-more-link");

  // Only show read more link if content exceeds max height
  if (content[0].scrollHeight <= content[0].clientHeight) {
    readMoreLink.addClass("hidden");
  }

  // Handle read more/less toggle
  readMoreLink.on("click", function (e) {
    e.preventDefault();
    content.toggleClass("collapsed expanded");

    if (content.hasClass("expanded")) {
      readMoreLink.text("Read less");
    } else {
      readMoreLink.text("Read more");
      // Smooth scroll back to description top when collapsing
      const descriptionTop = content.offset().top - 100;
      if ($(window).scrollTop() > descriptionTop) {
        $("html, body").animate(
          {
            scrollTop: descriptionTop,
          },
          300
        );
      }
    }
  });
});
