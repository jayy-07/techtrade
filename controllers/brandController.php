<?php

require_once '../classes/Brand.php';

/**
 * Controller class for managing brand operations
 */
class BrandController
{
    /** @var Brand Instance of Brand class */
    private $brand;

    /**
     * Constructor initializes Brand instance
     */
    public function __construct()
    {
        $this->brand = new Brand();
    }

    /**
     * Handles brand creation from form submission
     * Processes POST data and adds new brand to database
     * @return string Success/failure message
     */
    public function create()
    {
        // Handle brand creation form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process and sanitize form data
            $brand_name = $_POST['brand_name'];

            // Add brand to the database
            if ($this->brand->add_brand($brand_name)) {
                // Redirect to success page or return success message
                return "Brand added successfully!";
            } else {
                // Return error message
                return "Failed to add brand.";
            }
        }
    }

    /**
     * Gets the Brand instance
     * @return Brand The brand instance
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Retrieves all brands from database
     * @return array Array of all brands
     */
    public function index()
    {
        // Handle fetching and displaying all brands
        $brands = $this->brand->get_all_brands();
        return $brands;
    }
}
