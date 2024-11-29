<?php
require_once '../classes/Section.php';

class SectionController {
    private $section;

    public function __construct() {
        $this->section = new Section();
    }

    public function getSection() {
        return $this->section;
    }

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