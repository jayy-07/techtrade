function showToast(message, type = "primary") {
  // Change the toast's background color based on the type (success, error, etc.)
  const toastContainer = $("#toastContainer");
  const toastMessage = $("#toastMessage");

  toastContainer.removeClass("text-bg-primary text-bg-danger text-bg-success");
  toastContainer.addClass(`text-bg-${type}`);
  toastMessage.text(message);

  // Initialize and show the toast
  const toast = new bootstrap.Toast(toastContainer[0]);
  toast.show();
}

$(document).ready(function () {
  // Open Edit Modal
  $(".edit-user").on("click", function () {
    const userId = $(this).data("user-id");
    const userName = $(this).data("user-name");
    const userEmail = $(this).data("user-email");
    const userRole = $(this).data("user-role");

    $("#editUserForm #userId").val(userId);
    $("#editUserForm #userName").val(userName);
    $("#editUserForm #userEmail").val(userEmail);
    $("#editUserForm #userRole").val(userRole);
  });

  // Save Changes
  $("#saveChanges").on("click", function () {
    const formData = $("#editUserForm").serialize() + "&action=update";

    $.ajax({
      url: "../actions/manage_users.php",
      method: "POST",
      data: formData,
      success: function (response) {
        try {
          const res = JSON.parse(response);
          if (res.success) {
            showToast(res.message, "success");

            // Close the modal
            const editUserModal = bootstrap.Modal.getInstance(
              document.getElementById("editUserModal")
            );
            editUserModal.hide();

            // Update the UI dynamically (e.g., update the user role in the table)
            const updatedRole = $("#userRole").val();
            const userId = $("#userId").val();
            $(`tr[data-user-id="${userId}"] td:nth-child(5)`).text(updatedRole); // Assuming role is the 5th column
          } else {
            showToast(res.message, "danger");
          }
        } catch (e) {
          console.error("JSON parsing error:", e);
          showToast(
            "An unexpected error occurred. Check the console for details.",
            "danger"
          );
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX error:", status, error);
        showToast("An error occurred. Please try again.", "danger");
      },
    });
  });

  // Open Delete Modal
  $(".delete-user").on("click", function () {
    const userId = $(this).data("user-id");
    const userName = $(this).data("user-name");

    $("#deleteUserName").text(userName);
    $("#confirmDelete").data("user-id", userId);
  });

  // Confirm Delete
  $("#confirmDelete").on("click", function () {
    const userId = $(this).data("user-id");

    $.ajax({
      url: "../actions/manage_users.php",
      method: "POST",
      data: { action: "delete", user_id: userId },
      success: function (response) {
        const res = JSON.parse(response);
        if (res.success) {
          // Show success toast
          showToast(res.message, "success");

          // Dynamically remove the deleted user from the table
          $(`tr[data-user-id="${userId}"]`).remove();

          // Close the delete modal
          const deleteUserModal = bootstrap.Modal.getInstance(
            document.getElementById("deleteUserModal")
          );
          deleteUserModal.hide();
        } else {
          // Show error toast
          showToast(res.message, "danger");
        }
      },
      error: function () {
        showToast("An error occurred. Please try again.", "danger");
      },
    });
  });
});
