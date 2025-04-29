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

        // Get filter parameters
        $status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
        $priority = filter_input(INPUT_GET, 'priority', FILTER_SANITIZE_SPECIAL_CHARS);
        $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?: 1;
        $limit = 10; // Number of tasks per page

        // Get filtered tasks
        $tasks = $this->taskModel->getFilteredTasks($user_id, $status, $priority, $search, $page, $limit);

        // Get task counts
        $taskCounts = $this->taskModel->getTaskCounts($user_id);

        // Get upcoming tasks
        $upcomingTasks = $this->taskModel->getUpcomingTasks($user_id, 5);

        // Get recent activity
        $recentActivity = $this->taskModel->getRecentActivities($user_id, 5);

        // Get total count for pagination
        $totalTasks = $this->taskModel->countFilteredTasks($user_id, $status, $priority, $search);
        $totalPages = ceil($totalTasks / $limit);

        // Prepare pagination data
        $pagination = [
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTasks' => $totalTasks,
            'limit' => $limit
        ];

        // Include dashboard view
        include 'views/dashboard.php';
    }



    // Show create task form
    public function showCreateForm()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        include 'views/tasks/create.php';
    }

    // Process task creation
    public function createTask()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        // Validate and sanitize input
        $user_id = $_SESSION['user_id'];
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $due_date = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_SPECIAL_CHARS);
        $priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_SPECIAL_CHARS);

        $errors = [];

        // Validate inputs
        if (empty($title)) {
            $errors[] = "Title is required";
        }

        if (empty($due_date)) {
            $errors[] = "Due date is required";
        }

        if (!in_array($priority, ['Low', 'Medium', 'High'])) {
            $priority = 'Medium'; // Default to Medium if invalid
        }

        // If no errors, create task
        if (empty($errors)) {
            $task_id = $this->taskModel->createTask($user_id, $title, $description, $due_date, $priority);

            if ($task_id) {
                // Log the activity after successful creation
                $this->taskModel->logActivity($user_id, $task_id, 'create');

                $_SESSION['message'] = "Task created successfully!";
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                $errors[] = "Failed to create task. Please try again.";
            }
        }

        // If there were errors, show the form again with errors
        $_SESSION['errors'] = $errors;
        header('Location: index.php?action=task_create');
        exit();
    }

    public function updateTask()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];
        $task_id = filter_input(INPUT_POST, 'task_id', FILTER_SANITIZE_NUMBER_INT);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
        $due_date = filter_input(INPUT_POST, 'due_date', FILTER_SANITIZE_SPECIAL_CHARS);
        $priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_SPECIAL_CHARS);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        $errors = [];

        // Validate inputs
        if (empty($task_id)) {
            $errors[] = "Task ID is required";
        }

        if (empty($title)) {
            $errors[] = "Title is required";
        }

        if (empty($due_date)) {
            $errors[] = "Due date is required";
        }

        if (!in_array($priority, ['Low', 'Medium', 'High'])) {
            $priority = 'Medium'; // Default to Medium if invalid
        }

        if (!in_array($status, ['Pending', 'Completed'])) {
            $status = 'Pending'; // Default to Pending if invalid
        }

        // Get the original task to check if status changed
        $originalTask = $this->taskModel->getTaskById($task_id, $user_id);
        $statusChanged = ($originalTask['status'] != $status);
        $action = 'update';

        // If status changed to completed, log it as 'complete'
        if ($statusChanged && $status == 'Completed') {
            $action = 'complete';
        }
        // If status changed to pending (from completed), log it as 'reopen'
        else if ($statusChanged && $status == 'Pending') {
            $action = 'reopen';
        }

        // If no errors, update task
        if (empty($errors)) {
            if ($this->taskModel->updateTask($task_id, $user_id, $title, $description, $due_date, $priority, $status)) {
                // Log the activity after successful update
                $this->taskModel->logActivity($user_id, $task_id, $action);

                $_SESSION['message'] = "Task updated successfully!";
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                $errors[] = "Failed to update task. Please try again.";
            }
        }

        // If there were errors, redirect back to edit form
        $_SESSION['errors'] = $errors;
        header('Location: index.php?action=task_edit&id=' . $task_id);
        exit();
    }

    // Show task details
    public function viewTask()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];
        $task_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$task_id) {
            $_SESSION['errors'] = ['Invalid task ID'];
            header('Location: index.php?action=dashboard');
            exit();
        }

        $task = $this->taskModel->getTaskById($task_id, $user_id);

        if (!$task) {
            $_SESSION['errors'] = ['Task not found or you do not have permission to view it'];
            header('Location: index.php?action=dashboard');
            exit();
        }

        include 'views/tasks/list.php';
    }

    // Show edit task form
    public function showEditForm()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];
        $task_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$task_id) {
            $_SESSION['errors'] = ['Invalid task ID'];
            header('Location: index.php?action=dashboard');
            exit();
        }

        $task = $this->taskModel->getTaskById($task_id, $user_id);

        if (!$task) {
            $_SESSION['errors'] = ['Task not found or you do not have permission to edit it'];
            header('Location: index.php?action=dashboard');
            exit();
        }

        include 'views/tasks/edit.php';
    }

    // Process task update


    // Delete task
    public function deleteTask()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];
        $task_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!$task_id) {
            $_SESSION['errors'] = ['Invalid task ID'];
            header('Location: index.php?action=dashboard');
            exit();
        }

        // Get task details before deletion for activity log
        $task = $this->taskModel->getTaskById($task_id, $user_id);

        if ($task && $this->taskModel->deleteTask($task_id, $user_id)) {
            // Log the activity (need to log before deletion to retain task reference)
            $this->taskModel->logActivity($user_id, $task_id, 'delete');

            $_SESSION['message'] = "Task deleted successfully!";
        } else {
            $_SESSION['errors'] = ['Failed to delete task. Please try again.'];
        }

        header('Location: index.php?action=dashboard');
        exit();
    }

    // Toggle task status
    public function toggleTaskStatus()
    {
        // Ensure user is logged in
        AuthController::requireLogin();

        $user_id = $_SESSION['user_id'];
        $task_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Get task details before status change
        $task = $this->taskModel->getTaskById($task_id, $user_id);
        $action = ($task['status'] == 'Completed') ? 'reopen' : 'complete';

        if ($this->taskModel->toggleTaskStatus($task_id, $user_id)) {
            // Log the activity
            $this->taskModel->logActivity($user_id, $task_id, $action);

            $_SESSION['message'] = "Task status updated successfully!";
        } else {
            $_SESSION['errors'] = ['Failed to update task status. Please try again.'];
        }

        header('Location: index.php?action=dashboard');
        exit();
    }
}
