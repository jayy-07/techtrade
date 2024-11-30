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
}
