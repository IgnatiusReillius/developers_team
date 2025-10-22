<?php 

class CategoryModel {
    private string $archivo = (ROOT_PATH . '/lib/data/categories.json');

    // --- READ ALL ---
    public function getAll(): array {
        if (!file_exists($this->archivo)) return [];

        $datos = json_decode(file_get_contents($this->archivo), true) ?? [];
        return array_map(fn($item) => (object) $item, $datos);
    }

    // --- READ ONE ---
    public function getById(int $id): ?object {
        foreach ($this->getAll() as $category) {
            if ($category->id === $id) return $category;
        }
        return null;
    }

}