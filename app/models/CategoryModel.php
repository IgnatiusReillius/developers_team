<?php 

class CategoryModel {
    private string $archivo = (ROOT_PATH . '/lib/data/categories.json');

    
    public function getAll(): array {
        if (!file_exists($this->archivo)) return [];

        $datos = json_decode(file_get_contents($this->archivo), true) ?? [];
        return array_map(fn($item) => (object) $item, $datos);
    }

   
    public function getById(int $id): ?object {
        foreach ($this->getAll() as $category) {
            if ($category->id === $id) return $category;
        }
        return null;
    }
    
    public function create(array $data): object {
        $categories = $this->getAll();

        // Generar ID único
        $maxId = empty($categories) ? 0 : max(array_map(fn($c) => $c->id, $categories));
        $newCategory = (object) [
            'id' => $maxId + 1,
            'name' => $data['name'] ?? 'Sin nombre'
        ];

        $categories[] = $newCategory;
        $this->save($categories);

        return $newCategory;
    }

    private function save(array $categories): void {
        // Convertimos objetos a arrays antes de guardar
        $array = array_map(fn($c) => (array) $c, $categories);
        file_put_contents($this->archivo, json_encode($array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

}