<?php
    require_once __DIR__ . '/../models/TaskModel.php';
    require_once __DIR__ . '/ApplicationController.php';

    class TaskController extends ApplicationController {

        /** 
         * Las funciones del controlador de tareas deberían ser:
         * - Crear tarea (recibe datos del usuario, validar y crear tarea)
         * - Mostrar todas las tareas       
         * - Mostrar tareas filtradas       
         * - Actualizar tareas              UpdateAction
         * - Eliminar tarea                 
         * - Completar tarea                
         * - Filtrar por estado             
        */ 

        // public function index() {
        //     $tareas = $this->modelo->obtenerTodas();
        //     require __DIR__ . '/../views/tareas/index.php';
        // }

        public function createTaskAction() {
            /**
             * probando sin métodos HTTP
             */
            echo "create Task Action" . PHP_EOL;
            $dataNewTask = [
                "taskTitle" => 'task Title',
                "taskDescription" => 'task Description'
            ];

            $newTask = new TaskModel($dataNewTask);
            $newTask->addNewData();

            /** 
            * $_SERVER = variable que contiene información sobre el entorno del servidor y la solicitud HTTP actual
            * REQUEST_METHOD = el método HTTP con el que se envió la solicitud al servidor
            * POST = Cuando envías datos por un formulario
            */
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { // ¿El método HTTP con el que se hizo esta solicitud es POST?
                $dataNewTask = [ // Haría falta validar campos
                    $taskTitle = $_POST['taskTitle'],
                    $taskDescription = $_POST['taskDescription']
                ];

                $newTask = new TaskModel($dataNewTask);
                $newTask->addNewData();

                //$this->modelo->crear($titulo, $descripcion); 

                //header("Location: index.php?controller=tarea&action=index"); // Envía una cabecera HTTP Location para redirigir al usuario a la lista de tareas (index del controlador tarea). Esta es una práctica común (Post/Redirect/Get) para evitar reenvíos del formulario si el usuario refresca la página después de enviar.
                exit;
            }
            //require __DIR__ . '/../views/tareas/crear.php';
        }

        public function viewTaskAction(){
            $tasks = TaskModel::accessAllData();
            //$this->view->tasks = $tasks;
            require __DIR__ . '/../views/scripts/task/taskView.phtml';
        }

        public function editAction() {

            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "Falta el ID de la tarea";
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $newTaskTitle = $_POST['taskTitle'];
                $newTaskDescription = $_POST['newTaskDescription'];
                TaskModel::updateById($id, $newTaskTitle, $newTaskDescription);
                //header("Location: index.php?controller=tarea&action=index");
                exit;
            }
            //require __DIR__ . '/../views/tareas/editar.php';
        }

        public function deleteAction() {

            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo "Falta el ID de la tarea";
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                TaskModel::deleteById($id);
                //header("Location: index.php?controller=tarea&action=index");
                exit;
            }
        }

        public function indexAction() {
            $this->tasks = $this->model->accessAllData();
        }
    }

    // $taskController = new TaskController();
    // $taskController->createTaskAction();
