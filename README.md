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
└── README.md              → Project documentation
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

## Development Timeline

### Week 1 (Done!)
- ✅ Project structure setup
- ✅ Database schema creation
- ✅ User authentication system (register/login/logout)
- ✅ Basic dashboard view

### Week 2 (Ongoing)
- ✅ Task CRUD operations
- Dashboard implementation
- Task listing and filtering

### Future Weeks
- Advanced filtering and sorting
- UI polish
- Security enhancements
- Deployment


## Contributors

- James Allen M. Josue (axellexious)

## Acknowledgements

- Bootstrap for the frontend framework
- PHP and MySQL communities for documentation and support
- Myself for pushing through with this, I'll get out of this job in university and go back to the Industry wahahahahah, watch me
