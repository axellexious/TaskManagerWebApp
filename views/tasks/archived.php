<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Tasks | Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="public/css/styles.css" rel="stylesheet">
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
                        <a class="nav-link" href="index.php?action=completed_tasks">
                            <i class="bi bi-check-circle"></i> Completed Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?action=archived_tasks">
                            <i class="bi bi-archive"></i> Archived Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
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
    <div class="container mt-4 mb-5 pb-5">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    <?php
                    foreach ($_SESSION['errors'] as $error) {
                        echo "<li>$error</li>";
                    }
                    unset($_SESSION['errors']);
                    ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                        <h5 class="mb-0"><i class="bi bi-archive"></i> Archived Tasks</h5>
                        <div>
                            <a href="index.php?action=dashboard" class="btn btn-sm btn-primary">
                                <i class="bi bi-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($archivedTasks)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-archive" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">No archived tasks found</h5>
                                <p>Archived tasks will appear here.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title</th>
                                            <th>Due Date</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($archivedTasks as $task): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td><?php echo date('M j, Y', strtotime($task['due_date'])); ?></td>
                                                <td>
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
                                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $task['priority']; ?></span>
                                                </td>
                                                <td>
                                                    <?php if ($task['status'] == 'Completed'): ?>
                                                        <span class="badge bg-success">Completed</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?action=task_view&id=<?php echo $task['id']; ?>" class="btn btn-outline-primary" title="View details">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="index.php?action=task_restore&id=<?php echo $task['id']; ?>" class="btn btn-outline-success" title="Restore task">
                                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                                        </a>
                                                        <a href="index.php?action=task_delete&id=<?php echo $task['id']; ?>" class="btn btn-outline-danger" title="Permanently delete task"
                                                            onclick="return confirm('Are you sure you want to permanently delete this task? This action cannot be undone.')">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
                                <nav aria-label="Page navigation" class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo $pagination['currentPage'] <= 1 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="index.php?action=archived_tasks&page=<?php echo $pagination['currentPage'] - 1; ?>">Previous</a>
                                        </li>

                                        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                                            <li class="page-item <?php echo $pagination['currentPage'] == $i ? 'active' : ''; ?>">
                                                <a class="page-link" href="index.php?action=archived_tasks&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?php echo $pagination['currentPage'] >= $pagination['totalPages'] ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="index.php?action=archived_tasks&page=<?php echo $pagination['currentPage'] + 1; ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php endif; ?>
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