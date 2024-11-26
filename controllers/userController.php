<?php
require_once '../settings/core.php';
require_once '../classes/User.php';

class UserController {
    private $User;

    public function __construct() {
        $this->User = new User();
    }

    public function updateUserRole($userId, $role) {
        return $this->User->update_user_role($userId, $role);
    }

    public function deleteUser($userId) {
        return $this->User->delete_user($userId);
    }

    public function getAllUsers() {
        return $this->User->get_all_users();
    }
}
