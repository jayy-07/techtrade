<?php

require_once '../classes/Brand.php';

class BrandController
{
    private $brand;

    public function __construct()
    {
        $this->brand = new Brand();
    }

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

    public function index()
    {
        // Handle fetching and displaying all brands
        $brands = $this->brand->get_all_brands();
        return $brands;
    }
}