<?php

require_once '../classes/Category.php';

/**
 * Controller class for managing category operations
 */
class CategoryController
{
    /** @var Category Instance of Category class */
    private $category;

    /**
     * Constructor initializes Category instance
     */
    public function __construct()
    {
        $this->category = new Category();
    }

    /**
     * Handles category creation from form submission
     * Processes POST data and adds new category to database
     * @return string Success/failure message
     */
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

    /**
     * Retrieves all categories from database
     * @return array Array of all categories
     */
    public function index()
    {
        // Handle fetching and displaying all categories
        $categories = $this->category->get_all_categories();
        return $categories;
    }

    /**
     * Gets the Category instance
     * @return Category The category instance
     */
    public function getCategory() {
        return $this->category;
    }
}