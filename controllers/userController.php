<?php
require_once '../classes/User.php';

class UserController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function getUserById($userId) {
        return $this->user->select_one_user($userId);
    }

    public function getAllUsers() {
        return $this->user->get_all_users();
    }

    public function emailExists($email, $excludeUserId = null) {
        return $this->user->check_email_exists($email, $excludeUserId);
    }

    public function updateProfile($data) {
        return $this->user->update_user_profile($data);
    }

    public function verifyPassword($userId, $password) {
        $result = $this->user->get_user_password($userId);
        if ($result && isset($result['password'])) {
            return password_verify($password, $result['password']);
        }
        return false;
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->user->update_user_password($userId, $hashedPassword);
    }

    public function updateAddress($data) {
        return $this->user->update_user_address($data);
    }

    public function updateUserRole($userId, $newRole) {
        try {
            // Validate inputs
            if (!$userId || !in_array($newRole, ['Customer', 'Seller', 'Administrator'])) {
                error_log("UserController - Invalid role update parameters: User ID = $userId, Role = $newRole");
                return false;
            }

            // Log the attempt
            error_log("UserController - Attempting to update role: User ID = $userId, New Role = $newRole");

            // Use the User class method to update the role
            $result = $this->user->update_user_role($userId, $newRole);

            if ($result) {
                error_log("UserController - Successfully updated user role");
                return true;
            } else {
                error_log("UserController - Failed to update user role");
                return false;
            }

        } catch (Exception $e) {
            error_log("UserController - Error updating user role: " . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId) {
        try {
            // Validate input
            if (!$userId) {
                error_log("UserController - Invalid user ID for deletion: $userId");
                return false;
            }

            // Log the attempt
            error_log("UserController - Attempting to delete user: User ID = $userId");

            // Use the User class method to delete the user
            $result = $this->user->delete_user($userId);
    

            if ($result) {
                error_log("UserController - Successfully deleted user");
                return true;
            } else {
                error_log("UserController - Failed to delete user");
                return false;
            }

        } catch (Exception $e) {
            error_log("UserController - Error deleting user: " . $e->getMessage());
            return false;
        }
    }
}
