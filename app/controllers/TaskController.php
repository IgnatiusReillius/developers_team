<?php
    require_once __DIR__ . "/../models/TaskModel.php";
    require_once __DIR__ . "/ApplicationController.php";

    class TaskController extends ApplicationController {

        public function createTaskAction() {
            if ($_SERVER["REQUEST_METHOD"] === "GET") {
                $dataNewTask = [ 
                    "taskTitle" => "Nueva tarea",
                    "taskDescription" => "Descripción de la nueva tarea.",
                    "taskUser" => $_SESSION["user"]["name"]
                ];

                new TaskModel($dataNewTask);

                header("Location: " . WEB_ROOT . "/home");
                exit;
            }
        }

        public function viewTaskAction(){
            require_once __DIR__ . '/../models/CategoryModel.php';
            $categoryModel = new CategoryModel();

            $tasks = TaskModel::accessFilteredData();
            $categories = $categoryModel->getAll();

            // if(empty($tasks)) {
            //     echo "no hay tareas ";
            // }

            require __DIR__ . "/../views/scripts/task/index.phtml";
        }

        public function updateAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = (int) $_POST["taskId"];

                $newTaskTitle = trim($_POST["taskTitle"] ?? "");
                $newTaskDescription = trim($_POST["taskDescription"] ?? "");
                $newCategoryId = isset($_POST["categoryId"]) ? (int)$_POST["categoryId"] : null;

                TaskModel::updateTaskByIndex($id, $newTaskTitle, $newTaskDescription, $newCategoryId);
                header("Location: " . WEB_ROOT . "/home");
                exit;
            }
        }

        public function deleteAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = (int) $_POST["taskId"];
                TaskModel::deleteTaskByIndex($id);
            }
            header("Location: " . WEB_ROOT . "/home");
            exit;
        }

    }
