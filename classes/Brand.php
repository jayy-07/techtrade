<?php
require_once '../settings/db_class.php';

class Brand extends db_connection {
    public function add_brand($name) {
        $sql = "INSERT INTO brands (name) VALUES ('$name')";
        return $this->db_query($sql);
    }

    public function get_all_brands() {
        $sql = "SELECT * FROM brands ORDER BY name ASC";
        return $this->db_fetch_all($sql);
    }

    // Add other methods for updating, deleting, etc. as needed
}