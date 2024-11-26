<?php

require_once '../settings/db_class.php';

class Category extends db_connection
{

    public function add_category($category_name)
    {
        // Add a new category to the database
        $sql = "INSERT INTO categories (`name`) 
                VALUES ('$category_name')";
        return $this->db_query($sql);
    }

    public function get_all_categories()
    {
        // Retrieve all categories from the database
        $sql = "SELECT * FROM categories ORDER BY `name` ASC";
        return $this->db_fetch_all($sql);
    }
}