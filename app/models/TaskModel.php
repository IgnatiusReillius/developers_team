<?php

class TaskModel {

    public string $taskTitle, $taskDescription, $taskUser;
    public array $tasks;
    public ?int $categoryId = null;

    const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

    public function __construct(array $dataNewTask = []) {
        $this->taskTitle = $dataNewTask["taskTitle"] ?? "";
        $this->taskDescription = $dataNewTask["taskDescription"] ?? "";
        $this->taskUser = $dataNewTask["taskUser"] ?? $_SESSION["user"]["name"];
        $this->categoryId = isset($dataNewTask["categoryId"]) ? (int)$dataNewTask["categoryId"] : null;

        self::addNewData($this);
    }

    public static function addNewData(TaskModel $taskModel) : void {
        // Leer todas las tareas (no filtradas)
        $tasks = [];

        if (file_exists(self::FILE_PATH)) {
            $jsonData = file_get_contents(self::FILE_PATH);
            $tasks = json_decode($jsonData, true) ?? [];
        }
        
        // Añadimos la nueva tarea
        $tasks[] = [
            "taskTitle" => $taskModel->taskTitle,
            "taskDescription" => $taskModel->taskDescription,
            "taskUser" => $taskModel->taskUser,
            "categoryId" => $taskModel->categoryId
        ];

        // Guardamos
        self::saveData($tasks);
    }

    public static function saveData(array $tasks) : void {
        file_put_contents( 
            self::FILE_PATH,
            json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) 
        );
    }

    public static function deleteTaskByIndex(int $index) : void {
        if (!file_exists(self::FILE_PATH)) {
            return;
        }

        $jsonData = file_get_contents(self::FILE_PATH);
        $tasks = json_decode($jsonData, true) ?? [];

        // Usuario actual
        $currentUser = $_SESSION["user"]["name"] ?? null;
        if (!$currentUser) {
            return;
        }

        // Verificamos si el índice existe y pertenece al usuario logueado
        if (!isset($tasks[$index]) || $tasks[$index]["taskUser"] !== $currentUser) {
            return; // Evitamos borrar tareas de otros usuarios
        }

        // Borrar la tarea
        unset($tasks[$index]);

        // Guardar los cambios SIN reindexar (para mantener consistencia con los índices globales)
        file_put_contents(
            self::FILE_PATH,
            json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public static function accessFilteredData() : array {
        if (!file_exists(self::FILE_PATH)) {
            echo "No existe el archivo de tareas";
            return [];
        }
        
        // traer datos del json y decodificarlos
        $jsonData = file_get_contents(self::FILE_PATH);
        $decodedData = json_decode($jsonData, true) ?? [];

        // Comprobamos qué usuario está logueado
        $currentUser = $_SESSION["user"]["name"] ?? null; 

        // Verificar si el usuario está en sesión
        if (!$currentUser) {
            echo "no hay usuario";
            return [];
        }

        // Filtrar manteniendo las claves originales (índices reales)
        $filteredDataByUser = array_filter($decodedData, function ($task) use ($currentUser) {
            return isset($task["taskUser"]) && $task["taskUser"] === $currentUser;
        });

        // ======= Cargar categorías =======
        $categoriesPath = __DIR__ . "/../../lib/data/categories.json";
        $categories = [];
        if (file_exists($categoriesPath)) {
            $categories = json_decode(file_get_contents($categoriesPath), true) ?? [];
        }
        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat["id"]] = $cat["name"];
        }

        // Convertir a objetos pero sin perder los índices
        foreach ($filteredDataByUser as $key => &$task) {
            $taskObj = (object) $task;
            $categoryId = $task["categoryId"] ?? null;
            $taskObj->categoryName = $categoryId && isset($categoryMap[$categoryId])
                ? $categoryMap[$categoryId]
                : "Sin categoría";
            $task = $taskObj;
        }

        return $filteredDataByUser; // mantiene claves 0, 1, 4
    }

    public static function updateTaskByIndex(int $index, string $newTaskTitle, string $newTaskDescription, ?int $newCategoryId = null) : void {
        if (!file_exists(self::FILE_PATH)) {
            echo "No existe el archivo de tareas";
            return;
        }

        // Cargar todas las tareas
        $jsonData = file_get_contents(self::FILE_PATH);
        $allTasks = json_decode($jsonData, true) ?? [];

        // Usuario actual
        $currentUser = $_SESSION["user"]["name"] ?? null;
        if (!$currentUser) {
            echo "No hay usuario logueado";
            return;
        }

        if (!isset($allTasks[$index]) || $allTasks[$index]["taskUser"] !== $currentUser) {
            echo "No tienes permiso para editar esta tarea";
            return;
        }

        // Actualizamos los campos
        $allTasks[$index]["taskTitle"] = $newTaskTitle;
        $allTasks[$index]["taskDescription"] = $newTaskDescription;
        if ($newCategoryId !== null) {
            $allTasks[$index]["categoryId"] = $newCategoryId;
        }

        // Guardamos todo el archivo
        self::saveData($allTasks);
    }
}
