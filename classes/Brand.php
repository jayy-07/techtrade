<?php

require_once '../settings/db_class.php';

class Brand extends db_connection
{

    public function add_brand($brand_name)
    {
        // Add a new brand to the database
        $sql = "INSERT INTO brands (`name`) 
                VALUES ('$brand_name')";
        return $this->db_query($sql);
    }

    public function get_all_brands()
    {
        // Retrieve all brands from the database
        $sql = "SELECT * FROM brands ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }
}