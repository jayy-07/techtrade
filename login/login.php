<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="../css/home.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center align-items-center">
            <!-- Right Column: Login Form -->
            <div class="col-md-7">
                <div class="text-center">
                    <img src="../images/download.png" alt="Logo" class="img-fluid" style="max-width: 180px;" />
                    <h3>Sign in</h3>
                </div>
                <div class="card login-card">
                    <div class="card-body">
                        <form id="login-form" method="post">
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required />
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" id="password" required pattern="^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$" oninvalid="setCustomValidity('Password must be a minimum of 6 characters. At least 1 uppercase letter, 1 lowercase letter, and 1 number. No spaces. ')" oninput="setCustomValidity('')" placeholder="Enter your password">
                            </div>

                            <!-- Error Message -->
                            <p class="text-danger" id="error-message"></p>

                            <!-- Login Button -->
                            <button type="submit" id="signin-btn" class="btn w-100">Sign In</button>
                        </form>

                        <!-- Register Link -->
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="register.php">Create an account</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Form validation and login submission
            $('#login-form').on('submit', function(e) {
                e.preventDefault();

                const email = $('#email').val();
                const password = $('#password').val();

                if (!email || !password) {
                    $('#error-message').text("Please fill in all fields.");
                    return;
                }

                // Submit the login form
                $.ajax({
                    url: '../actions/loginprocess.php',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password
                    },
                    success: function(response) {
                        const res = JSON.parse(response);

                        if (res.status === 'success') {
                            // Redirect to the appropriate page based on role
                            window.location.href = res.redirect_url;
                        } else {
                            $('#error-message').text(res.errors.join(' '));
                        }
                    },
                    error: function() {
                        $('#error-message').text('An unexpected error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
</body>

</html>