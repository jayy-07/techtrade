$(document).ready(function() {

  /**
   * Shows a toast notification with the specified message and type
   * @param {string} message - The message to display in the toast
   * @param {string} type - The type of toast (primary, success, or danger)
   */
  function showToast(message, type = "primary") {
      const toastContainer = $("#toastContainer");
      const toastMessage = $("#toastMessage");

      toastContainer.removeClass("text-bg-primary text-bg-success text-bg-danger");
      toastContainer.addClass(`text-bg-${type}`);
      toastMessage.text(message);

      const toast = new bootstrap.Toast(toastContainer[0]);
      toast.show();
  }

  /**
   * Updates the product table after edit/delete operations
   * @param {string} response - JSON response from server containing updated product data
   */
  function updateProductTable(response) {
      try {
          const res = JSON.parse(response);
          if (res.success) {
              showToast(res.message, "success");

              if (res.action === 'edit') {
                  // Update the row in the table with new product data
                  const updatedRow = `
                      <td>${res.product_name}</td>
                      <td>${res.category_name}</td>
                      <td>${res.brand_name}</td>
                      <td>${res.price}</td>
                      <td>${res.discount}</td>
                      <td>${res.stock_quantity}</td>
                      <td>
                          <button class="btn btn-primary btn-sm edit-product" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="${res.product_id}" data-product-name="${res.product_name}" data-product-price="${res.price}" data-product-stock="${res.stock_quantity}" data-product-discount="${res.discount}">Edit</button>
                          <button class="btn btn-danger btn-sm delete-product" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-product-id="${res.product_id}" data-product-name="${res.product_name}">Delete</button>
                      </td>
                  `;
                  $(`tr[data-product-id="${res.product_id}"]`).html(updatedRow);
              } else if (res.action === 'delete') {
                  // Remove the deleted product row
                  $(`tr[data-product-id="${res.product_id}"]`).remove();
              }
          } else {
              showToast(res.message, "danger");
          }
      } catch (error) {
          console.error("Error parsing response:", error);
          showToast("An unexpected error occurred.", "danger");
      }
  }

  /**
   * Handle Add Product form submission
   * Validates input and sends AJAX request to add new product
   */
  $("#addProductForm").submit(function(e) {
      e.preventDefault();

      // Validate form inputs
      let isValid = true;
      const price = parseFloat($("#price").val());
      const stock = parseInt($("#stock_quantity").val());
      const discount = parseFloat($("#discount").val());
      const errorMessage = $("#error-message");

      if (isNaN(price) || price <= 0) {
          errorMessage.text("Price must be a positive number.");
          isValid = false;
      } else if (isNaN(stock) || stock <= 0) {
          errorMessage.text("Stock quantity must be a positive integer.");
          isValid = false;
      } else if (isNaN(discount) || discount < 0 || discount >= 100) {
          errorMessage.text("Discount must be between 0 and 99.99%.");
          isValid = false;
      } else {
          errorMessage.text("");
      }

      if (isValid) {
          var formData = $(this).serialize();

          $.ajax({
              url: "../actions/add_seller_product.php",
              type: "POST",
              data: formData,
              success: function(response) {
                  // Reload the page after successful product addition
                  location.reload();
              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  alert("An error occurred while adding the product.");
              }
          });
      }
  });

  /**
   * Handle Edit Product form submission
   * Validates input and sends AJAX request to update product
   */
  $("#editProductForm").submit(function(e) {
      e.preventDefault();

      // Validate form inputs
      let isValid = true;
      const price = parseFloat($("#edit_price").val());
      const stock = parseInt($("#edit_stock_quantity").val());
      const discount = parseFloat($("#edit_discount").val());
      const errorMessage = $("#editProductModal #error-message");

      if (isNaN(price) || price <= 0) {
          errorMessage.text("Price must be a positive number.");
          isValid = false;
      } else if (isNaN(stock) || stock <= 0) {
          errorMessage.text("Stock quantity must be a positive integer.");
          isValid = false;
      } else if (isNaN(discount) || discount < 0 || discount >= 100) {
          errorMessage.text("Discount must be between 0 and 99.99%.");
          isValid = false;
      } else {
          errorMessage.text("");
      }

      if (isValid) {
          var formData = $(this).serialize();

          $.ajax({
              url: "../actions/update_seller_product.php",
              type: "POST",
              data: formData,
              success: function(response) {
                  $("#editProductModal").modal("hide");
                  updateProductTable(response);
              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
                  showToast("An error occurred while updating the product.", "danger");
              }
          });
      }
  });

  /**
   * Handle Delete Product confirmation
   * Sends AJAX request to delete product when confirmed
   */
  $("#confirmDeleteProduct").click(function(e) {
      e.preventDefault();
      const productId = $(this).data("product-id");

      $.ajax({
          url: `../actions/delete_seller_product.php?product_id=${productId}`,
          type: "GET",
          success: function(response) {
              $("#deleteProductModal").modal("hide");
              updateProductTable(response);
          },
          error: function(xhr, status, error) {
              console.error(xhr.responseText);
              showToast("An error occurred while deleting the product.", "danger");
          }
      });
  });

  /**
   * Handle Edit Product modal opening
   * Populates form fields with current product data
   */
  $(document).on("click", ".edit-product", function() {
      const productId = $(this).data("product-id");
      const productPrice = $(this).data("product-price");
      const productStock = $(this).data("product-stock");
      const productDiscount = $(this).data("product-discount");

      $("#editProductForm #edit_product_id").val(productId);
      $("#editProductForm #edit_price").val(productPrice);
      $("#editProductForm #edit_stock_quantity").val(productStock);
      $("#editProductForm #edit_discount").val(productDiscount);
  });

  /**
   * Handle Delete Product modal opening
   * Sets product details in confirmation dialog
   */
  $(document).on("click", ".delete-product", function() {
      const productId = $(this).data("product-id");
      const productName = $(this).data("product-name");

      $("#deleteProductName").text(productName);
      $("#confirmDeleteProduct").data("product-id", productId);
  });
});