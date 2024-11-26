<?php

require_once '../classes/User.php';

class RegisterController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register($data)
    {
        // Validate inputs
        $errors = $this->validate_data($data);
        if (!empty($errors)) {
            return ['status' => 'error', 'errors' => $errors];
        }

        // Check if email exists
        if ($this->user->email_exists($data['email'])) {
            return ['status' => 'error', 'errors' => ['Email is already registered']];
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // Register user
        if ($this->user->register_user($data)) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'error', 'errors' => ['Registration failed, please try again.']];
        }
    }

    private function validate_data($data)
    {
        $errors = [];

        if (empty($data['first_name']) || empty($data['last_name'])) {
            $errors[] = 'First and last names are required.';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }
        if (strlen($data['password']) < 6 || !preg_match('/[A-Z]/', $data['password']) || !preg_match('/\d/', $data['password'])) {
            $errors[] = 'Password must be at least 6 characters long and include at least one uppercase letter and one number.';
        }
        if ($data['password'] !== $data['password2']) {
            $errors[] = 'Passwords do not match.';
        }
        if (empty($data['phone']) || !preg_match('/^\+?\d+$/', $data['phone'])) {
            $errors[] = 'Phone number is invalid.';
        }

        if (empty($data['region_id'])) {
            $errors[] = 'Please select a region.';
        }

        return $errors;
    }
}
