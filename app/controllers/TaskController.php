<?php
    require_once __DIR__ . "/../models/TaskModel.php";
    require_once __DIR__ . "/ApplicationController.php";

    class TaskController extends ApplicationController {

        public function createTaskAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $dataNewTask = [ 
                    "taskTitle" => $_POST["taskTitle"] ?? "",
                    "taskDescription" => $_POST["taskDescription"] ?? ""
                ];

                $newTask = new TaskModel($dataNewTask);
                $newTask->addNewData();

                header("Location: " . WEB_ROOT);
                exit;
            }
        }

        public function viewTaskAction(){
            $tasks = TaskModel::accessAllData();
            require __DIR__ . "/../views/scripts/task/index.phtml";
        }

        public function editAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = (int) $_POST["taskId"];

                $newTaskTitle = trim($_POST["taskTitle"] ?? "");
                $newTaskDescription = trim($_POST["taskDescription"] ?? "");

                TaskModel::updateTaskById($id, $newTaskTitle, $newTaskDescription);
                header("Location: " . WEB_ROOT);
                exit;
            }
        }

        public function deleteAction() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $id = (int) $_POST["taskId"];
                TaskModel::deleteTaskById($id);
            }
            header("Location: " . WEB_ROOT);
            exit;
        }

        public function indexAction() {
            $this->tasks = TaskModel::accessAllData();
        }
    }
