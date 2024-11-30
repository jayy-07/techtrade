<?php
require_once '../settings/db_class.php';

class User extends db_connection
{
    // Add new user to the database
    public function register_user($data)
    {
        $sql = "INSERT INTO users (first_name, last_name, email, phone, address, city, region_id, password, role) 
                VALUES ('{$data['first_name']}', '{$data['last_name']}', '{$data['email']}', '{$data['phone']}', 
                        '{$data['address']}', '{$data['city']}', '{$data['region_id']}', '{$data['password']}', 'customer')";
        return $this->db_query($sql);
    }

    // Check if email already exists
    public function email_exists($email)
    {
        $sql = "SELECT user_id FROM users WHERE email = '$email'";
        return $this->db_fetch_one($sql);
    }

    public function get_regions()
    {
        $sql = "SELECT * FROM regions ORDER BY name ASC";
        return $this->db_fetch_all($sql);
    }

    // Authenticate user based on email and password
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

    public function get_all_users()
    {
        $sql = "SELECT users.*, regions.name as region_name 
            FROM users 
            LEFT JOIN regions ON users.region_id = regions.id 
            ORDER BY users.created_at DESC";
        return $this->db_fetch_all($sql);
    }

    public function delete_user($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = '$user_id'";
        return $this->db_query($sql);
    }

    public function update_user_role($user_id, $role)
    {
        $sql = "UPDATE users 
            SET role = '$role' 
            WHERE user_id = '$user_id'";
        return $this->db_query($sql);
    }
}
