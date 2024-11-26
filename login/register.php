<!DOCTYPE html>
<html lang="en">
<?php
require_once '../functions/getRegions.php';
$regions = fetch_regions();
?>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register</title>
  <link rel="icon" type="image/x-icon" href="../images/favicon.png" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
  <link href="../css/home.css" rel="stylesheet" />
</head>

<body>
  <div class="container mt-5 mb-5">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-7">
        <div class="text-center">
          <img src="../images/download.png" alt="Logo" class="img-fluid" style="max-width: 180px;" />
          <h3>Create an account</h3>
        </div>
        <div class="card">
          <div class="card-body">
            <form id="register-form" method="post">
              <!-- Name Fields -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="first_name" class="form-label">First Name</label>
                  <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" required />
                </div>
                <div class="col-md-6">
                  <label for="last_name" class="form-label">Last Name</label>
                  <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name" required />
                </div>
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required />
              </div>

              <!-- Phone -->
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your number" required />
                <small id="phone-error" class="text-danger"></small>
              </div>

              <!-- Address -->
              <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="address" placeholder="Enter your address" required />
              </div>

              <!-- City and Region -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="city" class="form-label">City</label>
                  <input type="text" name="city" class="form-control" id="city" placeholder="Enter your city" required />
                </div>
                <div class="col-md-6">
                  <label for="region" class="form-label">Region</label>
                  <select id="region" name="region_id" class="form-select" required>
                    <option value="" disabled selected>Select your region</option>
                    <?php foreach ($regions as $region): ?>
                      <option value="<?= htmlspecialchars($region['id']); ?>"><?= htmlspecialchars($region['name']); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <!-- Password Fields -->
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password" required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{6,}$" />
                </div>
                <div class="col-md-6">
                  <label for="confirm-password" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" id="confirm-password" name="password2" placeholder="Confirm Password" required />
                </div>
              </div>

              <!-- Error Message and Submit -->
              <p class="text-danger" id="error-message"></p>
              <button type="submit" id="signin-btn" class="btn w-100">Sign up</button>
            </form>

            <!-- Sign In Link -->
            <div class="text-center mt-3">
              <p>Already have an account? <a href="login.php">Sign in</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script src="../js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../js/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>


  <script>
    $(document).ready(function() {
      const phoneInput = intlTelInput(document.querySelector("#phone"), {
        initialCountry: "GH",
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        separateDialCode: true,
        autoPlaceholder: "polite",
      });

      $('#register-form').on('submit', function(e) {
        e.preventDefault();

        const password = $('#password').val();
        const confirmPassword = $('#confirm-password').val();
        const phoneNumber = phoneInput.getNumber(intlTelInputUtils.numberFormat.E164);
        const phoneError = $("#phone-error");
        const errorMessage = $('#error-message');

        phoneError.text("");
        errorMessage.text("");

        // Password match validation
        if (password !== confirmPassword) {
          errorMessage.html("Passwords do not match.");
          return;
        }

        // Phone number validation
        if (!phoneInput.isValidNumber()) {
          const errorCode = phoneInput.getValidationError();
          let errorText = "Invalid phone number.";
          switch (errorCode) {
            case intlTelInputUtils.validationError.INVALID_COUNTRY_CODE:
              errorText = "Invalid country code.";
              break;
            case intlTelInputUtils.validationError.TOO_SHORT:
              errorText = "The phone number is too short.";
              break;
            case intlTelInputUtils.validationError.TOO_LONG:
              errorText = "The phone number is too long.";
              break;
            case intlTelInputUtils.validationError.NOT_A_NUMBER:
              errorText = "This is not a valid number.";
              break;
          }
          phoneError.text(errorText);
          return;
        }

        // Submit form via AJAX
        $.ajax({
          url: '../actions/registerProcess.php',
          type: 'POST',
          data: $(this).serialize() + '&phone=' + encodeURIComponent(phoneNumber),
          success: function(response) {
            const res = JSON.parse(response);

            if (res.status === 'success') {
              window.location.href = '../login/login.php';
            } else {
              if (res.errors && res.errors.length > 0) {
                const errorList = res.errors.map(err => `<div>${err}</div>`).join('');
                errorMessage.html(errorList);
              } else {
                errorMessage.text("An unexpected error occurred. Please try again.");
              }
            }

          },
          error: function() {
            errorMessage.text("Unable to connect to the server. Please try again.");
          },
        });
      });
    });
  </script>

</body>

</html>