<?php
require_once '../classes/User.php';

class LoginController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function login($data)
    {
        // Validate inputs
        $errors = $this->validate_data($data);
        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        // Authenticate user
        $user = $this->user->authenticate_user($data['email'], $data['password']);
        if ($user) {
            // Store user info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['user_address'] = $user['address'];
            $_SESSION['user_phone'] = $user['phone'];
            $_SESSION['user_region'] = $user['region_id'];
            $_SESSION['user_city'] = $user['city'];


            // Define redirection URL based on user role
            if ($user['role'] == 'Administrator') {
                $redirect_url = '../admin/users.php'; // Redirect to admin dashboard
            } else {
                $redirect_url = '../view/home.php'; // Redirect to customer dashboard
            }

            return ['status' => 'success', 'redirect_url' => $redirect_url];
        } else {
            return ['status' => 'error', 'errors' => ['Invalid email or password.']];
        }
    }

    private function validate_data($data)
    {
        $errors = [];

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        if (empty($data['password'])) {
            $errors[] = 'Password is required.';
        }

        return $errors;
    }
}
