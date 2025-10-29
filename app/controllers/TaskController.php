<?php
    require_once __DIR__ . "/../models/TaskModel.php";
    require_once __DIR__ . "/ApplicationController.php";

    class TaskController extends ApplicationController {

        public function createTaskAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $dataNewTask = [ 
                    "taskTitle" => trim($_POST["taskTitle"] ?? ""),
                    "taskDescription" => trim($_POST["taskDescription"] ?? ""),
                    "taskUser" => $_SESSION["user"]["name"]
                ];

                $newTask = new TaskModel($dataNewTask);
                $newTask->addNewData();

                header("Location: " . WEB_ROOT . "/home");
                exit;
            }
        }

        public function viewTaskAction(){
            $tasks = TaskModel::accessFilteredData();
            // echo $_SESSION["user"]["name"];
            require __DIR__ . "/../views/scripts/task/index.phtml";
        }

        public function updateAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = (int) $_POST["taskId"];

                $newTaskTitle = trim($_POST["taskTitle"] ?? "");
                $newTaskDescription = trim($_POST["taskDescription"] ?? "");

                TaskModel::updateTaskByIndex($id, $newTaskTitle, $newTaskDescription);
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

        public function indexAction() {
            $this->tasks = TaskModel::accessAllData();
        }
    }
