# Task Manager Web Application

A PHP & MySQL-based web application for managing tasks, with user authentication and task management features.

## Features

- User Registration and Authentication
- Task Management (Create, Read, Update, Delete)
- Task Prioritization
- Task Status Tracking
- Dashboard with Task Statistics
- Responsive Design with Bootstrap

## Project Structure

```
task-manager/
├── index.php              → Main entry point (routes)
├── config/
│   └── db.php             → DB connection
├── controllers/
│   ├── auth.php           → Authentication controller
│   └── tasks.php          → Tasks controller
├── models/
│   ├── User.php           → User model
│   └── Task.php           → Task model
├── views/
│   ├── login.php          → Login page
│   ├── register.php       → Registration page
│   ├── dashboard.php      → Dashboard page
│   └── tasks/             → Task-related views
│       ├── create.php     → Create task form
│       ├── edit.php       → Edit task form
│       └── list.php       → List tasks
├── public/
│   ├── css/               → CSS files
│   ├── js/                → JavaScript files
│   └── img/               → Image files
├── README.md              → Project documentation
├── db-setup.php           → Database Setup Script
└── database.sql           → Db
```

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Backend**: PHP (vanilla with MVC structure)
- **Database**: MySQL
- **Development Tools**: Git, VS Code, phpMyAdmin

## Installation & Setup

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache, Nginx, or PHP's built-in server)

### Steps

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/task-manager.git
   ```

2. Import the database schema:
   - Create a new MySQL database named `task_manager`
   - Import the schema from SQL files in the project

3. Configure the database connection:
   - Open `config/db.php`
   - Update the database credentials according to your setup

4. Set up the web server:
   - Point your web server to the project root directory
   - Alternatively, use PHP's built-in server for development:
     ```
     php -S localhost:8000
     ```

5. Access the application:
   - Open your browser and navigate to `http://localhost:8000`

## Usage

1. Register a new account
2. Log in with your credentials
3. From the dashboard, you can:
   - View task statistics
   - Create new tasks
   - Update existing tasks
   - Delete tasks
   - Mark tasks as completed

## Order of Functionalities Implemented

1. **Core Foundation**
   - Project structure setup with MVC architecture
   - Database schema creation
   - Configuration setup

2. **User Management**
   - User registration with validation
   - User login/logout system
   - Session management
   - Password hashing and security

3. **Basic Task Management**
   - Task creation
   - Task viewing
   - Task editing
   - Task deletion

4. **Advanced Task Features**
   - Task status toggling (complete/pending)
   - Task prioritization (Low, Medium, High)
   - Due date management
   - Task description support

5. **Dashboard Development**
   - Task listing with pagination
   - Task statistics (total, pending, completed)
   - Filtering by status and priority
   - Search functionality

6. **Additional Features**
   - Task archiving functionality
   - Archived tasks view
   - Completed tasks view
   - Activity logging (task creation, updates, etc.)
   - Recent activity display

7. **UI/UX Enhancements**
   - Responsive design implementation
   - Bootstrap integration
   - UI polish (cards, badges, icons)
   - Form validation and error handling

8. **Performance & Security**
   - Input sanitization
   - PDO for database operations
   - User permission checks
   - Session security

## Contributors

- James Allen M. Josue (axellexious)

## Acknowledgements

- Bootstrap for the frontend framework
- PHP and MySQL communities for documentation and support
- Myself for pushing through with this, I'll get out of this job in university and go back to the Industry wahahahahah, watch me
