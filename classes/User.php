<?php
require_once '../settings/db_class.php';

/**
 * User class for managing user accounts and authentication
 * Handles CRUD operations for users and user-related data
 * Extends database connection class
 */
class User extends db_connection
{
    /**
     * Registers a new user in the database
     * @param array $data User details including first_name, last_name, email, phone, address, city, region_id, password
     * @return bool True on success, false on failure
     */
    public function register_user($data)
    {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, address, city, region_id, password, role) 
                VALUES ('{$data['first_name']}', '{$data['last_name']}', '{$data['email']}', '{$data['phone']}', 
                        '{$data['address']}', '{$data['city']}', '{$data['region_id']}', '{$data['password']}', 'customer')";
        return $this->db_query($sql);
    }

    /**
     * Checks if an email already exists in the database
     * @param string $email Email to check
     * @return array|bool User ID if email exists, false if not
     */
    public function email_exists($email)
    {
        $sql = "SELECT user_id FROM users WHERE email = '$email'";
        return $this->db_fetch_one($sql);
    }

    /**
     * Gets all regions from the database
     * @return array Array of all regions sorted by name
     */
    public function get_regions()
    {
        $sql = "SELECT * FROM regions ORDER BY name ASC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Authenticates a user based on email and password
     * @param string $email User's email
     * @param string $password User's password
     * @return array|bool User data if authentication successful, false if failed
     */
    public function authenticate_user($email, $password)
    {
        $sql = "SELECT user_id, first_name, email, phone, address, region_id, city, role, password FROM users WHERE email = '$email'";
        $result = $this->db_fetch_one($sql);

        if ($result && password_verify($password, $result['password'])) {
            // Return all necessary user data except password
            return [
                'user_id' => $result['user_id'],
                'first_name' => $result['first_name'],
                'email' => $result['email'],
                'phone' => $result['phone'],
                'address' => $result['address'],
                'region_id' => $result['region_id'],
                'city' => $result['city'],
                'role' => $result['role']
            ];
        }

        return false; // Authentication failed
    }

    /**
     * Gets all users from the database with their region names
     * @return array Array of all users sorted by creation date
     */
    public function get_all_users()
    {
        $sql = "SELECT users.*, regions.name as region_name 
            FROM users 
            LEFT JOIN regions ON users.region_id = regions.id 
            ORDER BY users.created_at DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Deletes a user from the database
     * @param int $user_id ID of user to delete
     * @return bool True on success, false on failure
     */
    public function delete_user($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = '$user_id'";
        return $this->db_query($sql);
    }

    /**
     * Updates a user's role in the database
     * @param int $user_id ID of user to update
     * @param string $role New role to assign
     * @return bool True on success, false on failure
     */
    public function update_user_role($user_id, $role)
    {
        $sql = "UPDATE users 
            SET role = '$role' 
            WHERE user_id = '$user_id'";
        return $this->db_query($sql);
    }

    /**
     * Gets a specific user by ID with their region name
     * @param int $userId ID of user to retrieve
     * @return array|bool User data if found, false if not
     */
    public function select_one_user($userId)
    {
        $sql = "SELECT users.*, regions.name as region_name 
                FROM users 
                LEFT JOIN regions ON users.region_id = regions.id 
                WHERE users.user_id = '$userId'";
        return $this->db_fetch_one($sql);
    }

    /**
     * Checks if an email exists, optionally excluding a specific user
     * @param string $email Email to check
     * @param int|null $excludeUserId Optional user ID to exclude from check
     * @return array|bool User ID if email exists, false if not
     */
    public function check_email_exists($email, $excludeUserId = null)
    {
        $sql = "SELECT user_id FROM users WHERE email = '$email'";
        if ($excludeUserId) {
            $sql .= " AND user_id != '$excludeUserId'";
        }
        return $this->db_fetch_one($sql);
    }

    /**
     * Updates a user's profile information
     * @param array $data User data including first_name, last_name, email, phone, user_id
     * @return bool True on success, false on failure
     */
    public function update_user_profile($data)
    {
        $sql = "UPDATE users 
                SET first_name = '{$data['first_name']}', 
                    last_name = '{$data['last_name']}', 
                    email = '{$data['email']}', 
                    phone = '{$data['phone']}' 
                WHERE user_id = '{$data['user_id']}'";
        return $this->db_query($sql);
    }

    /**
     * Gets a user's password hash by user ID
     * @param int $userId ID of user
     * @return array|bool Password hash if found, false if not
     */
    public function get_user_password($userId)
    {
        $sql = "SELECT password FROM users WHERE user_id = '$userId'";
        return $this->db_fetch_one($sql);
    }

    /**
     * Updates a user's password
     * @param int $userId ID of user
     * @param string $hashedPassword New hashed password
     * @return bool True on success, false on failure
     */
    public function update_user_password($userId, $hashedPassword)
    {
        $sql = "UPDATE users 
                SET password = '$hashedPassword' 
                WHERE user_id = '$userId'";
        return $this->db_query($sql);
    }

    /**
     * Updates a user's address information
     * @param array $data Address data including address, city, region_id, user_id
     * @return bool True on success, false on failure
     */
    public function update_user_address($data)
    {
        $sql = "UPDATE users 
                SET address = '{$data['address']}', 
                    city = '{$data['city']}', 
                    region_id = '{$data['region_id']}' 
                WHERE user_id = '{$data['user_id']}'";
        return $this->db_query($sql);
    }
}
