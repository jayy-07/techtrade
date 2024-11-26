<?php
require_once '../settings/db_class.php';

class Category extends db_connection {
    public function add_category($name) {
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        return $this->db_query($sql);
    }

    public function get_all_categories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        return $this->db_fetch_all($sql);
    }

    // Add other methods for updating, deleting, etc. as needed
}