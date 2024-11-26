<?php
require_once '../settings/core.php';
require_once '../classes/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function updateUserRole($userId, $role) {
        return $this->userModel->update_user_role($userId, $role);
    }

    public function deleteUser($userId) {
        return $this->userModel->delete_user($userId);
    }

    public function getAllUsers() {
        return $this->userModel->get_all_users();
    }
}
