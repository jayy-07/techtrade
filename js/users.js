/**
 * Displays a toast notification with a specified message and type
 * @param {string} message - The message to display in the toast
 * @param {string} type - The type of toast (primary, success, danger, etc.)
 */
function showToast(message, type = "primary") {
  // Get toast elements from DOM
  const toastContainer = $("#toastContainer");
  const toastMessage = $("#toastMessage");

  // Remove existing background classes and add new one based on type
  toastContainer.removeClass("text-bg-primary text-bg-danger text-bg-success");
  toastContainer.addClass(`text-bg-${type}`);
  toastMessage.text(message);

  // Initialize and show the toast
  const toast = new bootstrap.Toast(toastContainer[0]);
  toast.show();
}

// Initialize event handlers when document is ready
$(document).ready(function () {
  // Handle opening the edit user modal
  $(".edit-user").on("click", function () {
    // Get user data from clicked element's data attributes
    const userId = $(this).data("user-id");
    const userName = $(this).data("user-name");
    const userEmail = $(this).data("user-email");
    const userRole = $(this).data("user-role");

    // Populate the edit form with user data
    $("#editUserForm #userId").val(userId);
    $("#editUserForm #userName").val(userName);
    $("#editUserForm #userEmail").val(userEmail);
    $("#editUserForm #userRole").val(userRole);
  });

  // Handle saving user changes
  $("#saveChanges").on("click", function () {
    // Serialize form data and add action parameter
    const formData = $("#editUserForm").serialize() + "&action=update";

    $.ajax({
      url: "../actions/manage_users.php",
      method: "POST",
      data: formData,
      success: function (response) {
        try {
          const res = JSON.parse(response);
          if (res.success) {
            // Show success message
            showToast(res.message, "success");

            // Close the edit modal
            const editUserModal = bootstrap.Modal.getInstance(
              document.getElementById("editUserModal")
            );
            editUserModal.hide();

            // Update the user role in the table
            const updatedRole = $("#userRole").val();
            const userId = $("#userId").val();
            $(`tr[data-user-id="${userId}"] td:nth-child(5)`).text(updatedRole);
          } else {
            // Show error message from server
            showToast(res.message, "danger");
          }
        } catch (e) {
          // Handle JSON parsing errors
          console.error("JSON parsing error:", e);
          showToast(
            "An unexpected error occurred. Check the console for details.",
            "danger"
          );
        }
      },
      error: function (xhr, status, error) {
        // Handle AJAX request errors
        console.error("AJAX error:", status, error);
        showToast("An error occurred. Please try again.", "danger");
      },
    });
  });

  // Handle opening the delete user modal
  $(".delete-user").on("click", function () {
    // Get user data from clicked element
    const userId = $(this).data("user-id");
    const userName = $(this).data("user-name");

    // Populate delete modal with user info
    $("#deleteUserName").text(userName);
    $("#confirmDelete").data("user-id", userId);
  });

  // Handle user deletion confirmation
  $("#confirmDelete").on("click", function () {
    const userId = $(this).data("user-id");

    $.ajax({
      url: "../actions/manage_users.php",
      method: "POST",
      data: { action: "delete", user_id: userId },
      success: function (response) {
        const res = JSON.parse(response);
        if (res.success) {
          // Show success message
          showToast(res.message, "success");

          // Remove user row from table
          $(`tr[data-user-id="${userId}"]`).remove();

          // Close the delete modal
          const deleteUserModal = bootstrap.Modal.getInstance(
            document.getElementById("deleteUserModal")
          );
          deleteUserModal.hide();
        } else {
          // Show error message from server
          showToast(res.message, "danger");
        }
      },
      error: function () {
        // Handle AJAX request errors
        showToast("An error occurred. Please try again.", "danger");
      },
    });
  });
});
