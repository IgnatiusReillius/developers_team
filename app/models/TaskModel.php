<?php

class TaskModel {

    /**
     * Cada tarea tiene los siguientes atributos:
     * - Un título de la tarea
     * - Una descripción
     * - Una categoría
     * 
     * Y las funciones:
     * - Crear tarea                C ok - construct
     * - Leer tareas                R ok - accessAllData, showAllData
     * - Actualizar tarea           U ok - updateTaskByID, saveData, addNewData
     * - Eliminar tarea             D ok - deleteTask
     * - Leer tarea por categoría
     */

    public string $taskTitle, $taskDescription;
    public array $tasks;
    public stdClass $tagType;

    const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

    /**
     * Cuando el controller manda crear una nueva tarea, llama a este método pasándole los datos que ha introducido el usuario en el formulario
     */
    public function __construct(array $dataNewTask = []) {
        $this->taskTitle = $dataNewTask["taskTitle"];
        $this->taskDescription = $dataNewTask["taskDescription"];
        // categoría
    }
    
    public static function saveData(array $tasks) : void {
        file_put_contents( // Escribimos información, 
            self::FILE_PATH, // en la ruta establecida,
            json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) // en formato JSON (en formato legible que no sea una sola línea de código y manteniendo los caracteres especiales)
        );
    }

    public function addNewData() : void {
        $tasks = self::accessAllData();
        $tasks[] = $this;
        self::saveData($tasks);
    }

    public static function accessAllData() : array {
        // Si no existe ningún archivo JSON con datos en la ruta, devuelve un array vacío
        if (!file_exists(self::FILE_PATH)) {
            return [];
        }
        
        // obtenemos toda los datos del JSON
        $jsonData = file_get_contents(self::FILE_PATH); 

        // convertimos la cadena JSON en un array asociativo
        // ?? [] sirve para devolver un array vacío en caso de que $jsonData es null
        $decodedData = json_decode($jsonData, true) ?? []; 

        return array_map(fn($item) => (object) $item, $decodedData);
        // return $decodedData;
    }

    public static function showAllData() : void {
        $tasks = self::accessAllData();
        echo 'Showing all data:<pre>';
        print_r($tasks); 
        echo '</pre>';
    }

    public static function deleteTaskByID(int $id) : void {
        $tasks = self::accessAllData();
        unset($tasks[$id]);
        $tasksReordered = array_values($tasks);
        self::saveData($tasksReordered);
    }

    public static function updateTaskByID(int $id, string $newTaskTitle, string $newTaskDescription) {
        $tasks = self::accessAllData();

        if(!isset($tasks[$id])) {
            echo "no hay id";
            return;
        }

        $taskToUpdate = $tasks[$id]; 
        
        $taskToUpdate->taskTitle = $newTaskTitle;
        $taskToUpdate->taskDescription = $newTaskDescription;

        $tasks[$id] = $taskToUpdate;
        self::saveData($tasks);
    }

}

// echo "inicio <br>";
// TaskModel::showAllData();

// $tarea1 = new TaskModel(["taskTitle" => "Tarea número 1", "taskDescription" => "Esto es la descripción de la tarea número 1."]);
// $tarea2 = new TaskModel(["taskTitle" => "Tarea 2", "taskDescription" => "Te diré lo que quiero, lo que quiero de verdad"]);
// $tarea3 = new TaskModel(["taskTitle" => "3", "taskDescription" => "blabla"]);

// echo "añadir <br>";
// //$tarea1->addNewData();
// //$tarea2->addNewData();
// $tarea3->addNewData();

// echo "borrar <br>";
// TaskModel::deleteTask(1);

// echo "actualizar <br>";
// TaskModel::updateTaskByID(1, "bla", "buah");

// $test = $taskModel->addNewData();
//echo '<pre>'; print_r($newData); echo '</pre><br>';
