<?php
require_once '../settings/core.php';
require_once '../classes/Brand.php';

class BrandController {
    private $brandModel;

    public function __construct() {
        $this->brandModel = new Brand();
    }

    public function getAllBrands() {
        return $this->brandModel->get_all_brands();
    }

    // Add other controller methods as needed
}