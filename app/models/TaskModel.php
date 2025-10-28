<?php

class TaskModel {

    public string $taskTitle, $taskDescription;
    public array $tasks;
    public stdClass $tagType;

    const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

    public function __construct(array $dataNewTask = []) {
        $this->taskTitle = $dataNewTask["taskTitle"] ?? "";
        $this->taskDescription = $dataNewTask["taskDescription"] ?? "";
    }
    
    public static function saveData(array $tasks) : void {
        file_put_contents( 
            self::FILE_PATH,
            json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) 
        );
    }

    public function addNewData() : void {
        $tasks = self::accessAllData();
        $tasks[] = $this;
        self::saveData($tasks);
    }

    public static function accessAllData() : array {
        if (!file_exists(self::FILE_PATH)) {
            return [];
        }
        
        $jsonData = file_get_contents(self::FILE_PATH); 

        $decodedData = json_decode($jsonData, true) ?? []; 

        return array_map(fn($item) => (object) $item, $decodedData);
    }

    public static function showAllData() : void {
        $tasks = self::accessAllData();
        echo 'Showing all data:<pre>';
        print_r($tasks); 
        echo '</pre>';
    }

    public static function deleteTaskById(int $id) : void {
        $tasks = self::accessAllData();
        unset($tasks[$id]);
        $tasksReordered = array_values($tasks);
        self::saveData($tasksReordered);
    }

    public static function updateTaskById(int $id, string $newTaskTitle, string $newTaskDescription) : void {
        $tasks = self::accessAllData();

        if(!isset($tasks[$id])) {
            echo "No existe una tarea con ese ID";
            return;
        }
        
        $tasks[$id]->taskTitle = $newTaskTitle;
        $tasks[$id]->taskDescription = $newTaskDescription;

        self::saveData($tasks);
    }

}
