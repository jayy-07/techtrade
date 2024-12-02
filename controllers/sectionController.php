<?php
require_once '../classes/Section.php';

// SectionController handles operations related to sections
class SectionController {
    private $section;

    // Constructor initializes the Section object
    public function __construct() {
        $this->section = new Section();
    }

    // Returns the Section object
    public function getSection() {
        return $this->section;
    }

    // Retrieves all sections along with their associated products
    public function getAllSectionsWithProducts() {
        $sections = $this->section->getAllSections();
        if (!$sections) return [];

        $sectionsWithProducts = [];
        foreach ($sections as $section) {
            $products = $this->section->getProductsBySection($section['id']);
            if ($products) {
                $sectionsWithProducts[] = [
                    'section' => $section,
                    'products' => $products
                ];
            }
        }
        return $sectionsWithProducts;
    }
}