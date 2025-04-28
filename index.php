<?php
session_start();

// Include controllers
require_once 'controllers/auth.php';
require_once 'controllers/tasks.php';

// Create controller instances
$authController = new AuthController();
$tasksController = new TasksController();

// Simple router
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Handle POST actions first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'register':
            $authController->register();
            break;
        case 'login':
            $authController->login();
            break;
        case 'task_create':
            // Require login
            AuthController::requireLogin();
            // Call method to handle task creation
            $tasksController->createTask();
            break;
        // Add other task-related POST actions here
        case 'task_update':
            AuthController::requireLogin();
            $tasksController->updateTask();
            break;
        default:
            // Redirect to login if action not found
            header('Location: index.php?action=login');
            exit();
    }
}

// Handle GET actions
switch ($action) {
    case 'register':
        $authController->showRegister();
        break;
    case 'login':
        $authController->showLogin();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'dashboard':
        // Require login for dashboard access
        AuthController::requireLogin();
        $tasksController->showDashboard();
        break;
    case 'task_create':
        $tasksController->showCreateForm();
        break;
    case 'task_view':
        $tasksController->viewTask();
        break;
    case 'task_edit':
        $tasksController->showEditForm();
        break;
    case 'task_delete':
        $tasksController->deleteTask();
        break;
    case 'task_toggle_status':
        $tasksController->toggleTaskStatus();
        break;
    default:
        // Default to login page
        $authController->showLogin();
        break;
}
