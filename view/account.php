<?php
require_once '../settings/core.php';
require_once '../controllers/UserController.php';
include '../functions/getRegions.php';

check_login();

$userController = new UserController();
$user = $userController->getUserById($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    <link href="../css/home.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <main>

        <div class="container my-5">
            <h2 class="mb-4">My Account</h2>

            <div class="row">
                <div class="col-md-3">
                    <!-- Account Navigation -->
                    <div class="list-group mb-4">
                        <button class="list-group-item list-group-item-action active" data-bs-toggle="tab" data-bs-target="#profile">
                            <i class="bi bi-person me-2"></i>Profile
                        </button>
                        <button class="list-group-item list-group-item-action" data-bs-toggle="tab" data-bs-target="#security">
                            <i class="bi bi-shield-lock me-2"></i>Security
                        </button>
                        <button class="list-group-item list-group-item-action" data-bs-toggle="tab" data-bs-target="#address">
                            <i class="bi bi-geo-alt me-2"></i>Address
                        </button>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="tab-content">
                        <!-- Profile Tab -->
                        <div class="tab-pane fade show active" id="profile">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Profile Information</h5>
                                    <form id="profileForm">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" id="firstName" name="first_name"
                                                    value="<?= htmlspecialchars($user['first_name']) ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="lastName" name="last_name"
                                                    value="<?= htmlspecialchars($user['last_name']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?= htmlspecialchars($user['email']) ?>" required>
                                            <small id="email-error" class="text-danger"></small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="<?= htmlspecialchars($user['phone']) ?>" required>
                                            <small id="phone-error" class="text-danger"></small>
                                        </div>
                                        <button type="submit" class="btn btn-techtrade-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Change Password</h5>
                                    <form id="passwordForm">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="currentPassword"
                                                name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="newPassword"
                                                name="new_password" required
                                                pattern="^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$"
                                                oninvalid="setCustomValidity('Password must be a minimum of 6 characters. At least 1 uppercase letter, 1 lowercase letter, and 1 number. No spaces.')"
                                                oninput="setCustomValidity('')">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirmPassword"
                                                name="confirm_password" required>
                                        </div>
                                        <p class="text-danger" id="password-error"></p>
                                        <button type="submit" class="btn btn-techtrade-primary">Update Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Address Tab -->
                        <div class="tab-pane fade" id="address">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Address Information</h5>
                                    <form id="addressForm">
                                        <div class="mb-3">
                                            <label class="form-label">Street Address</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?= htmlspecialchars($user['address'] ?? '') ?>" required>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city"
                                                    value="<?= htmlspecialchars($user['city'] ?? '') ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Region</label>
                                                <?php $regions = fetch_regions(); ?>
                                                <select id="region" name="region_id" class="form-select" required>
                                                    <option value="" disabled>Select your region</option>
                                                    <?php foreach ($regions as $region): ?>
                                                        <option value="<?= htmlspecialchars($region['id']) ?>"
                                                            <?= ($user['region_id'] ?? '') == $region['id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($region['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-techtrade-primary">Update Address</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
            <div id="toastContainer" class="toast align-items-center text-bg-primary border-0" role="alert" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastMessage"></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>

    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="../js/account.js"></script>
</body>

</html>