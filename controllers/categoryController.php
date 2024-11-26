<?php

require_once '../classes/Category.php';

class CategoryController
{
    private $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function create()
    {
        // Handle category creation form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process and sanitize form data
            $category_name = $_POST['category_name'];

            // Add category to the database
            if ($this->category->add_category($category_name)) {
                // Redirect to success page or return success message
                return "Category added successfully!";
            } else {
                // Return error message
                return "Failed to add category.";
            }
        }
    }

    public function index()
    {
        // Handle fetching and displaying all categories
        $categories = $this->category->get_all_categories();
        return $categories;
    }
}