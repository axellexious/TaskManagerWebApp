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

            return $stmt->execute();
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
}
