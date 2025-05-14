<?php
require_once 'config/db.php';

class Task
{
    private $db;

    public function __construct()
    {
        $this->db = connectDB();
    }

    // Get all tasks for a user
    public function getTasks($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY due_date ASC");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Get filtered tasks for a user
    public function getFilteredTasks($user_id, $status = null, $priority = null, $search = null, $page = 1, $limit = 10)
    {
        $query = "SELECT * FROM tasks WHERE user_id = :user_id AND archived = 0";
        $params = [':user_id' => $user_id];

        // Add filters
        if ($status) {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($priority) {
            $query .= " AND priority = :priority";
            $params[':priority'] = $priority;
        }

        if ($search) {
            $query .= " AND (title LIKE :search OR description LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Add sorting
        $query .= " ORDER BY due_date ASC";

        // Add pagination
        $offset = ($page - 1) * $limit;
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        // Bind pagination parameters
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Count filtered tasks for pagination
    public function countFilteredTasks($user_id, $status = null, $priority = null, $search = null)
    {
        $query = "SELECT COUNT(*) FROM tasks WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        // Add filters
        if ($status) {
            $query .= " AND status = :status";
            $params[':status'] = $status;
        }

        if ($priority) {
            $query .= " AND priority = :priority";
            $params[':priority'] = $priority;
        }

        if ($search) {
            $query .= " AND (title LIKE :search OR description LIKE :search)";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Get upcoming tasks (tasks due within the next 7 days)
    public function getUpcomingTasks($user_id, $limit = 5)
    {
        $today = date('Y-m-d');
        $nextWeek = date('Y-m-d', strtotime('+7 days'));

        $stmt = $this->db->prepare("
        SELECT * FROM tasks 
        WHERE user_id = :user_id 
        AND status = 'Pending' 
        AND due_date BETWEEN :today AND :next_week
        ORDER BY due_date ASC
        LIMIT :limit
    ");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':today', $today);
        $stmt->bindParam(':next_week', $nextWeek);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Log user activity
    public function logActivity($user_id, $task_id, $action)
    {
        try {
            $stmt = $this->db->prepare("
            INSERT INTO activities (user_id, task_id, action, created_at)
            VALUES (:user_id, :task_id, :action, NOW())
        ");

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':task_id', $task_id);
            $stmt->bindParam(':action', $action);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get recent activities with real-time timestamps
    public function getRecentActivities($user_id, $limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT a.id, a.action, a.created_at, t.title as task_title, t.id as task_id
        FROM activities a
        JOIN tasks t ON a.task_id = t.id
        WHERE a.user_id = :user_id
        ORDER BY a.created_at DESC
        LIMIT :limit
    ");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $activities = $stmt->fetchAll();

        // Set default timezone to match your server configuration
        date_default_timezone_set('Asia/Manila'); // Adjust to match your local timezone (Philippines)

        // Format the activity data with real-time timestamps
        foreach ($activities as &$activity) {
            // Get current time and created time as DateTime objects with timezone consideration
            $created = new DateTime($activity['created_at']);
            $now = new DateTime();

            // Debug timestamps to check for timezone issues
            // Uncomment if needed: error_log("Created: " . $created->format('Y-m-d H:i:s') . " | Now: " . $now->format('Y-m-d H:i:s'));

            $interval = $now->getTimestamp() - $created->getTimestamp();

            // Calculate time ago with a simple approach to ensure accuracy
            if ($interval < 10) {
                $activity['time_ago'] = 'just now';
            } elseif ($interval < 60) {
                $activity['time_ago'] = $interval . ' seconds ago';
            } elseif ($interval < 3600) {
                $minutes = floor($interval / 60);
                $activity['time_ago'] = $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
            } elseif ($interval < 86400) {
                $hours = floor($interval / 3600);
                $activity['time_ago'] = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
            } elseif ($interval < 172800) {
                $activity['time_ago'] = 'yesterday';
            } elseif ($interval < 604800) {
                $days = floor($interval / 86400);
                $activity['time_ago'] = $days . ' days ago';
            } elseif ($interval < 2629743) {
                $weeks = floor($interval / 604800);
                $activity['time_ago'] = $weeks . ' week' . ($weeks > 1 ? 's' : '') . ' ago';
            } elseif ($interval < 31556926) {
                $months = floor($interval / 2629743);
                $activity['time_ago'] = $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
            } else {
                $years = floor($interval / 31556926);
                $activity['time_ago'] = $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
            }

            // Format the action description
            switch ($activity['action']) {
                case 'create':
                    $activity['description'] = 'Created task';
                    break;
                case 'update':
                    $activity['description'] = 'Updated task';
                    break;
                case 'delete':
                    $activity['description'] = 'Deleted task';
                    break;
                case 'complete':
                    $activity['description'] = 'Completed task';
                    break;
                case 'reopen':
                    $activity['description'] = 'Reopened task';
                    break;
                case 'archive':
                    $activity['description'] = 'Archived task';
                    break;
                case 'restore':
                    $activity['description'] = 'Restored task';
                    break;
                default:
                    $activity['description'] = 'Activity on task';
            }
        }

        return $activities;
    }

    // Get a single task by ID
    public function getTaskById($task_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $task_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Create a new task
    public function createTask($user_id, $title, $description, $due_date, $priority)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO tasks (user_id, title, description, due_date, priority)
                VALUES (:user_id, :title, :description, :due_date, :priority)
            ");

            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':due_date', $due_date);
            $stmt->bindParam(':priority', $priority);

            $result = $stmt->execute();

            if ($result) {
                // Return the last inserted task ID
                return $this->db->lastInsertId();
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Update an existing task
    public function updateTask($task_id, $user_id, $title, $description, $due_date, $priority, $status)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET title = :title, description = :description, due_date = :due_date, 
                    priority = :priority, status = :status
                WHERE id = :id AND user_id = :user_id
            ");

            $stmt->bindParam(':id', $task_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':due_date', $due_date);
            $stmt->bindParam(':priority', $priority);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Delete a task
    public function deleteTask($task_id, $user_id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
            $stmt->bindParam(':id', $task_id);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Toggle task status (complete/incomplete)
    public function toggleTaskStatus($task_id, $user_id)
    {
        try {
            $task = $this->getTaskById($task_id, $user_id);
            $new_status = ($task['status'] == 'Completed') ? 'Pending' : 'Completed';

            $stmt = $this->db->prepare("
                UPDATE tasks 
                SET status = :status
                WHERE id = :id AND user_id = :user_id
            ");

            $stmt->bindParam(':id', $task_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':status', $new_status);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get task counts by status
    public function getTaskCounts($user_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) as completed
            FROM tasks
            WHERE user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetch();
    }

    // Archive a task instead of deleting it
    public function archiveTask($task_id, $user_id)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE tasks 
            SET archived = 1
            WHERE id = :id AND user_id = :user_id
        ");
            $stmt->bindParam(':id', $task_id);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Restore an archived task
    public function restoreTask($task_id, $user_id)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE tasks 
            SET archived = 0
            WHERE id = :id AND user_id = :user_id
        ");
            $stmt->bindParam(':id', $task_id);
            $stmt->bindParam(':user_id', $user_id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get archived tasks for a user
    public function getArchivedTasks($user_id, $page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;

        $stmt = $this->db->prepare("
        SELECT * FROM tasks 
        WHERE user_id = :user_id AND archived = 1
        ORDER BY due_date DESC
        LIMIT :limit OFFSET :offset
    ");

        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Count archived tasks for pagination
    public function countArchivedTasks($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM tasks 
        WHERE user_id = :user_id AND archived = 1
    ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    // Get completed tasks with filtering
    public function getCompletedTasks($user_id, $priority = null, $date_range = null, $page = 1, $limit = 10)
    {
        $query = "
        SELECT * FROM tasks 
        WHERE user_id = :user_id AND status = 'Completed' AND archived = 0
    ";

        $params = [':user_id' => $user_id];

        // Add priority filter if specified
        if ($priority) {
            $query .= " AND priority = :priority";
            $params[':priority'] = $priority;
        }

        // Add date range filter if specified
        if ($date_range) {
            $query .= " AND due_date BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $date_range['start'];
            $params[':end_date'] = $date_range['end'];
        }

        $query .= " ORDER BY due_date DESC LIMIT :limit OFFSET :offset";

        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare($query);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Count completed tasks for pagination
    public function countCompletedTasks($user_id, $priority = null, $date_range = null)
    {
        $query = "
        SELECT COUNT(*) FROM tasks 
        WHERE user_id = :user_id AND status = 'Completed' AND archived = 0
    ";

        $params = [':user_id' => $user_id];

        // Add priority filter if specified
        if ($priority) {
            $query .= " AND priority = :priority";
            $params[':priority'] = $priority;
        }

        // Add date range filter if specified
        if ($date_range) {
            $query .= " AND due_date BETWEEN :start_date AND :end_date";
            $params[':start_date'] = $date_range['start'];
            $params[':end_date'] = $date_range['end'];
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
