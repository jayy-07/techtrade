function showToast(message, type = "primary") {
  // Change toast background color based on type
  const toastContainer = $("#toastContainer");
  const toastMessage = $("#toastMessage");

  toastContainer.removeClass("text-bg-primary text-bg-success text-bg-danger");
  toastContainer.addClass(`text-bg-${type}`);
  toastMessage.text(message);

  // Show the toast
  const toast = new bootstrap.Toast(toastContainer[0]);
  toast.show();
}

$(document).ready(function () {
  // Add Product AJAX Request
  $("#saveProduct").click(function (e) {
    e.preventDefault();
    var formData = $("#addProductForm").serialize();

    $.ajax({
      url: "../actions/add_product.php",
      type: "POST",
      data: formData,
      success: function (response) {
        // Reload the page after successful product addition
        location.reload();
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
        alert("An error occurred while adding the product.");
      },
    });
  });

  // Update Product AJAX Request
  $("#updateProduct").click(function (e) {
    e.preventDefault();
    var formData = $("#editProductForm").serialize();

    $.ajax({
      url: "../actions/update_product.php",
      type: "POST",
      data: formData,
      success: function (response) {
        try {
          const res = JSON.parse(response);

          if (res.success) {
            showToast(res.message, "success");

            // Dynamically update the product in the table
            const updatedProduct = `
                      <td>${res.product_name}</td>
                      <td>${res.category_name}</td>
                      <td>${res.brand_name}</td>
                      <td>
                          <button class="btn btn-primary btn-sm edit-product" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="${res.product_id}" data-product-name="${res.product_name}" data-product-category-id="${res.category_id}" data-product-brand-id="${res.brand_id}" data-product-description="${res.description}">Edit</button>
                          <button class="btn btn-danger btn-sm delete-product" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-product-id="${res.product_id}" data-product-name="${res.product_name}">Delete</button>
                      </td>
                  `;
            $(`tr[data-product-id="${res.product_id}"]`).html(updatedProduct);

            // Close the modal
            $("#editProductModal").modal("hide");
          } else {
            showToast(res.message, "danger");
          }
        } catch (error) {
          console.error("Error parsing response:", error);
          showToast("An unexpected error occurred.", "danger");
        }
      },
      error: function () {
        showToast("An error occurred while updating the product.", "danger");
      },
    });
  });

  // Delete Product AJAX Request
  $("#confirmDeleteProduct").click(function (e) {
    e.preventDefault();
    const productId = $(this).data("product-id");

    $.ajax({
      url: `../actions/delete_product.php?product_id=${productId}`,
      type: "GET",
      success: function (response) {
        try {
          // Parse the JSON response
          const res = JSON.parse(response);

          if (res.success) {
            // Display success toast message
            showToast(res.message, "success");

            // Remove the corresponding row from the table
            $(`tr[data-product-id="${res.product_id}"]`).remove();

            // Close the modal
            $("#deleteProductModal").modal("hide");
          } else {
            // If not successful, display the error message in a toast
            showToast(res.message, "danger");
          }
        } catch (error) {
          // If there's an error parsing the JSON, log it and show a generic error message
          console.error("Error parsing response:", error);
          showToast("An unexpected error occurred.", "danger");
        }
      },
      error: function () {
        // If there's an AJAX error, show a generic error message
        showToast("An error occurred while deleting the product.", "danger");
      },
    });
  });

  // Open Edit Modal (Prefill fields and fetch images)
  $(document).on("click", ".edit-product", function () {
    const productId = $(this).data("product-id");
    const productName = $(this).data("product-name");
    const productCategoryId = $(this).data("product-category-id");
    const productBrandId = $(this).data("product-brand-id");
    const productDescription = $(this).data("product-description");

    $("#editProductForm #edit_product_id").val(productId);
    $("#editProductForm #edit_product_name").val(productName);
    $("#editProductForm #edit_category_id").val(productCategoryId);
    $("#editProductForm #edit_brand_id").val(productBrandId);
    $("#editProductForm #edit_description").val(productDescription);
  });

  // Open Delete Modal
  $(document).on("click", ".delete-product", function () {
    const productId = $(this).data("product-id");
    const productName = $(this).data("product-name");

    $("#deleteProductName").text(productName);
    $("#confirmDeleteProduct").data("product-id", productId);
  });
});
