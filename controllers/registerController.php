<?php

require_once '../classes/User.php';

/**
 * Controller class for handling user registration functionality
 */
class RegisterController
{
    /** @var User Instance of User class */
    private $user;

    /**
     * Constructor initializes User instance
     */
    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Handles user registration process
     * Validates input data, checks for existing email, and creates new user
     * @param array $data Registration form data containing user details
     * @return array Response with status and any error messages
     */
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

    /**
     * Validates registration form data
     * Checks required fields, email format, password requirements, and phone format
     * @param array $data Form data to validate
     * @return array Array of validation error messages
     */
    private function validate_data($data)
    {
        $errors = [];

        // Validate names
        if (empty($data['first_name']) || empty($data['last_name'])) {
            $errors[] = 'First and last names are required.';
        }

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email address.';
        }

        // Validate password requirements
        if (strlen($data['password']) < 6 || !preg_match('/[A-Z]/', $data['password']) || !preg_match('/\d/', $data['password'])) {
            $errors[] = 'Password must be at least 6 characters long and include at least one uppercase letter and one number.';
        }

        // Check password confirmation match
        if ($data['password'] !== $data['password2']) {
            $errors[] = 'Passwords do not match.';
        }

        // Validate phone number format
        if (empty($data['phone']) || !preg_match('/^\+?\d+$/', $data['phone'])) {
            $errors[] = 'Phone number is invalid.';
        }

        // Validate region selection
        if (empty($data['region_id'])) {
            $errors[] = 'Please select a region.';
        }

        return $errors;
    }
}
