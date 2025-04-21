<?php
require_once 'models/Task.php';
require_once 'controllers/auth.php';

class TasksController
{
    private $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    // Show dashboard with tasks
    public function showDashboard()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];

        // Get tasks for the user
        $tasks = $this->taskModel->getTasks($user_id);

        // Get task counts
        $taskCounts = $this->taskModel->getTaskCounts($user_id);

        // Include dashboard view
        include 'views/dashboard.php';
    }

    // Additional task methods will be implemented in Week 2
}
