<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Task Manager</title>
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
                        <a class="nav-link active" href="index.php?action=dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=completed_tasks">
                            <i class="bi bi-check-circle"></i> Completed Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=archived_tasks">
                            <i class="bi bi-archive"></i> Archived Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">
                            <i class="bi bi-person"></i> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </span>
                    </li>
                    <!-- Dark Mode Toggle Button -->
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title">Task Statistics</h4>
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <h3><?php echo $taskCounts['total'] ?? 0; ?></h3>
                                    <p class="mb-0">Total Tasks</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-warning bg-opacity-25 rounded">
                                    <h3><?php echo $taskCounts['pending'] ?? 0; ?></h3>
                                    <p class="mb-0">Pending Tasks</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-success bg-opacity-25 rounded">
                                    <h3><?php echo $taskCounts['completed'] ?? 0; ?></h3>
                                    <p class="mb-0">Completed Tasks</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                        <h5 class="mb-0">My Tasks</h5>
                        <div>
                            <a href="index.php?action=task_create" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle"></i> Add New Task
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Task filters -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form action="index.php" method="GET" class="row g-3">
                                    <input type="hidden" name="action" value="dashboard">
                                    <div class="col-md-3">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="">All Statuses</option>
                                            <option value="Pending" <?php echo isset($_GET['status']) && $_GET['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Completed" <?php echo isset($_GET['status']) && $_GET['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="priority" class="form-select form-select-sm">
                                            <option value="">All Priorities</option>
                                            <option value="Low" <?php echo isset($_GET['priority']) && $_GET['priority'] == 'Low' ? 'selected' : ''; ?>>Low</option>
                                            <option value="Medium" <?php echo isset($_GET['priority']) && $_GET['priority'] == 'Medium' ? 'selected' : ''; ?>>Medium</option>
                                            <option value="High" <?php echo isset($_GET['priority']) && $_GET['priority'] == 'High' ? 'selected' : ''; ?>>High</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                            <button class="btn btn-outline-secondary" type="submit">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php if (empty($tasks)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-journal-check" style="font-size: 3rem;"></i>
                                <h5 class="mt-3">No tasks found</h5>
                                <p>
                                    <?php if (isset($_GET['search']) || isset($_GET['status']) || isset($_GET['priority'])): ?>
                                        No tasks match your filters. <a href="index.php?action=dashboard">Clear filters</a>
                                    <?php else: ?>
                                        Create your first task to get started!
                                    <?php endif; ?>
                                </p>
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
                                        <?php foreach ($tasks as $task): ?>
                                            <?php
                                            // Check if task is overdue
                                            $isOverdue = false;
                                            if ($task['status'] != 'Completed') {
                                                $dueDate = new DateTime($task['due_date']);
                                                $today = new DateTime();
                                                $isOverdue = ($dueDate < $today);
                                            }
                                            ?>
                                            <tr<?php echo $isOverdue ? ' class="table-danger"' : ''; ?>>
                                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                                <td>
                                                    <?php echo date('M j, Y', strtotime($task['due_date'])); ?>
                                                    <?php if ($isOverdue): ?>
                                                        <span class="badge bg-danger ms-1">Overdue</span>
                                                    <?php endif; ?>
                                                </td>
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
                                                    <a href="index.php?action=task_toggle_status&id=<?php echo $task['id']; ?>" class="text-decoration-none">
                                                        <?php if ($task['status'] == 'Completed'): ?>
                                                            <span class="badge bg-success">Completed</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary">Pending</span>
                                                        <?php endif; ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="index.php?action=task_view&id=<?php echo $task['id']; ?>" class="btn btn-outline-primary" title="View details">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="index.php?action=task_edit&id=<?php echo $task['id']; ?>" class="btn btn-outline-secondary" title="Edit task">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <a href="index.php?action=task_archive&id=<?php echo $task['id']; ?>" class="btn btn-outline-secondary" title="Archive task">
                                                            <i class="bi bi-archive"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                </tr>
                                            <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination (if implemented in controller) -->
                            <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
                                <nav aria-label="Page navigation" class="mt-4">
                                    <ul class="pagination justify-content-center">
                                        <li class="page-item <?php echo $pagination['currentPage'] <= 1 ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="index.php?action=dashboard&page=<?php echo $pagination['currentPage'] - 1; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['priority']) ? '&priority=' . htmlspecialchars($_GET['priority']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>">Previous</a>
                                        </li>

                                        <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                                            <li class="page-item <?php echo $pagination['currentPage'] == $i ? 'active' : ''; ?>">
                                                <a class="page-link" href="index.php?action=dashboard&page=<?php echo $i; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['priority']) ? '&priority=' . htmlspecialchars($_GET['priority']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?php echo $pagination['currentPage'] >= $pagination['totalPages'] ? 'disabled' : ''; ?>">
                                            <a class="page-link" href="index.php?action=dashboard&page=<?php echo $pagination['currentPage'] + 1; ?><?php echo isset($_GET['status']) ? '&status=' . htmlspecialchars($_GET['status']) : ''; ?><?php echo isset($_GET['priority']) ? '&priority=' . htmlspecialchars($_GET['priority']) : ''; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row">
            <!-- Upcoming Tasks -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Upcoming Tasks</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($upcomingTasks) && !empty($upcomingTasks)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($upcomingTasks as $task): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="index.php?action=task_view&id=<?php echo $task['id']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($task['title']); ?>
                                            </a>
                                            <span class="badge <?php echo ($task['priority'] == 'High') ? 'bg-danger' : (($task['priority'] == 'Medium') ? 'bg-warning text-dark' : 'bg-info text-dark'); ?> ms-2"><?php echo $task['priority']; ?></span>
                                        </div>
                                        <span class="text-muted small"><?php echo date('M j', strtotime($task['due_date'])); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center text-muted my-4">No upcoming tasks</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-activity"></i> Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($recentActivity) && !empty($recentActivity)): ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($recentActivity as $activity): ?>
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo htmlspecialchars($activity['description']); ?></h6>
                                            <small class="text-muted"><?php echo $activity['time_ago']; ?></small>
                                        </div>
                                        <p class="mb-1 text-muted small"><?php echo $activity['task_title']; ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center text-muted my-4">No recent activity</p>
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