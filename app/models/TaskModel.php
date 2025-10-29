<?php

class TaskModel {

    public string $taskTitle, $taskDescription, $taskUser;
    public array $tasks;
    public stdClass $tagType;

    const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

    public function __construct(array $dataNewTask = []) {
        $this->taskTitle = $dataNewTask["taskTitle"] ?? "";
        $this->taskDescription = $dataNewTask["taskDescription"] ?? "";
        $this->taskUser = $dataNewTask["taskUser"] ?? $_SESSION["user"]["name"];
        self::addNewData();
    }

    public function addNewData(): void
    {
        // Leer todas las tareas (no filtradas)
        $tasks = [];

        if (file_exists(self::FILE_PATH)) {
            $jsonData = file_get_contents(self::FILE_PATH);
            $tasks = json_decode($jsonData, true) ?? [];
        }
        
        // Añadimos la nueva tarea
        $tasks[] = [
            "taskTitle" => $this->taskTitle,
            "taskDescription" => $this->taskDescription,
            "taskUser" => $this->taskUser
        ];

        // Guardamos
        self::saveData($tasks);
    }


    public static function deleteTaskByIndex(int $index): void
    {
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



    public static function saveData(array $tasks) : void {
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

        // Convertir a objetos pero sin perder los índices
        foreach ($filteredDataByUser as $key => &$task) {
            $task = (object) $task;
        }

        return $filteredDataByUser; // mantiene claves 0, 1, 4
    }

    public static function updateTaskByIndex(int $index, string $newTaskTitle, string $newTaskDescription): void {
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

        if ($allTasks[$index]["taskUser"] !== $currentUser) {
            echo "No tienes permiso para editar esta tarea";
            return;
        }

        // Actualizamos los campos
        $allTasks[$index]["taskTitle"] = $newTaskTitle;
        $allTasks[$index]["taskDescription"] = $newTaskDescription;

        // Guardamos todo el archivo
        self::saveData($allTasks);
    }
}
