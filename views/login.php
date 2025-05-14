<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/styles.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Task Manager - Login</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success">
                                <?php
                                echo $_SESSION['message'];
                                unset($_SESSION['message']);
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['errors'])): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php
                                    foreach ($_SESSION['errors'] as $error) {
                                        echo "<li>$error</li>";
                                    }
                                    unset($_SESSION['errors']);
                                    ?>
                                    <!-- Dark Mode Toggle Button -->
                                    <li class="nav-item">
                                        <a class="nav-link dark-mode-toggle" id="darkModeToggle" title="Switch to Dark Mode">
                                            <i class="bi bi-moon" id="darkModeIcon"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="index.php?action=login" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Don't have an account? <a href="index.php?action=register">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/main.js"></script>
</body>

</html>