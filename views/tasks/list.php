<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task | Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="public/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=dashboard">Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link dark-mode-toggle" id="darkModeToggle" title="Switch to Dark Mode">
                            <i class="bi bi-moon" id="darkModeIcon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Task Details</h5>
                        <div>
                            <a href="index.php?action=task_edit&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="index.php?action=task_delete&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this task?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($task['title']); ?></h4>

                        <div class="mb-3">
                            <span class="badge <?php echo ($task['status'] == 'Completed') ? 'bg-success' : 'bg-secondary'; ?>">
                                <?php echo $task['status']; ?>
                            </span>

                            <?php
                            $badgeClass = '';
                            switch ($task['priority']) {
                                case 'High':
                                    $badgeClass = 'bg-danger';
                                    break;
                                case 'Medium':
                                    $badgeClass = 'bg-warning text-dark';
                                    break;
                                case 'Low':
                                    $badgeClass = 'bg-info text-dark';
                                    break;
                            }
                            ?>
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $task['priority']; ?> Priority</span>
                        </div>

                        <div class="mb-3">
                            <strong>Due Date:</strong> <?php echo date('F j, Y', strtotime($task['due_date'])); ?>
                        </div>

                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p class="mt-2"><?php echo nl2br(htmlspecialchars($task['description'] ?: 'No description provided.')); ?></p>
                        </div>

                        <div class="mb-3">
                            <strong>Created:</strong> <?php echo date('F j, Y, g:i a', strtotime($task['created_at'])); ?>
                        </div>

                        <div class="mt-4">
                            <a href="index.php?action=dashboard" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                            <a href="index.php?action=task_toggle_status&id=<?php echo $task['id']; ?>" class="btn <?php echo ($task['status'] == 'Completed') ? 'btn-warning' : 'btn-success'; ?>">
                                <?php if ($task['status'] == 'Completed'): ?>
                                    <i class="bi bi-x-circle"></i> Mark as Pending
                                <?php else: ?>
                                    <i class="bi bi-check-circle"></i> Mark as Completed
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-5 fixed-bottom">
        <div class="container">
            <p class="mb-0">Task Manager &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/main.js"></script>
</body>

</html>