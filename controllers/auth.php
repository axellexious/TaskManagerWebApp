<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Display register form
    public function showRegister()
    {
        include 'views/register.php';
    }

    // Display login form
    public function showLogin()
    {
        include 'views/login.php';
    }

    // Process registration
    public function register()
    {
        // Sanitize and validate inputs
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $errors = [];

        // Validate username
        if (empty($username)) {
            $errors[] = "Username is required";
        }

        // Validate email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required";
        }

        // Validate password
        if (empty($password)) {
            $errors[] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters long";
        }

        // Check if passwords match
        if ($password != $confirm_password) {
            $errors[] = "Passwords do not match";
        }

        // If no errors, proceed with registration
        if (empty($errors)) {
            $result = $this->userModel->register($username, $email, $password);

            if ($result['success']) {
                $_SESSION['message'] = "Registration successful! You can now log in.";
                header('Location: index.php?action=login');
                exit();
            } else {
                $errors[] = $result['message'];
            }
        }

        // If there are errors, show the registration form again with errors
        $_SESSION['errors'] = $errors;
        header('Location: index.php?action=register');
        exit();
    }

    // Process login
    public function login()
    {
        // Sanitize inputs
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $_POST['password'];

        $result = $this->userModel->login($username, $password);

        if ($result['success']) {
            // Set session variables
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['logged_in'] = true;

            $_SESSION['message'] = "Welcome back, " . $result['username'] . "!";
            header('Location: index.php?action=dashboard');
            exit();
        } else {
            $_SESSION['errors'] = [$result['message']];
            header('Location: index.php?action=login');
            exit();
        }
    }

    // Process logout
    public function logout()
    {
        // Unset all session variables
        $_SESSION = [];

        // Destroy the session
        session_destroy();

        // Redirect to login page
        header('Location: index.php?action=login');
        exit();
    }

    // Check if user is logged in
    public static function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    // Require login to access a page
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            $_SESSION['message'] = "You must log in to access that page";
            header('Location: index.php?action=login');
            exit();
        }
    }
}
