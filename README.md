# Laravel To-Do Application

A simple, professional, and responsive To-Do Management web application built with Laravel, Blade, Bootstrap, authentication, validation, authorization, and user-specific task management.

## Features

- User Registration
- User Login and Logout
- Dashboard with task summary
- Create, View, Update, and Delete To-Dos
- User-specific task access only
- Form Request validation
- Authorization using Policy
- Filter tasks by status
- Sort tasks by due date, latest, or oldest
- Search tasks by title
- Responsive Bootstrap UI
- Profile management
- Soft Deletes for To-Dos

## Tech Stack

- Laravel
- Blade
- Bootstrap
- MySQL
- Laravel Breeze
- Eloquent ORM

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and NPM
- MySQL

## Installation

1. Clone the repository:

git clone https://github.com/ashishskorasoft/TodoApp.git

2. Go to the project folder:
   cd todo-app

3. Install PHP dependencies:
   composer install

4. Install frontend dependencies:
   npm install

5. Copy the environment file:
   cp .env.example .env

6. Generate the application key:
   php artisan key:generate

7. Update the `.env` file with your database credentials:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_app
   DB_USERNAME=root
   DB_PASSWORD=

8. Run the migrations:
   php artisan migrate

9. Build frontend assets:
   npm run build

10. Start the development server:
    php artisan serve

11. Open the application in your browser:
    http://127.0.0.1:8000

## Authentication

This project uses Laravel Breeze for authentication.

Available authentication features:
- Register
- Login
- Logout
- Profile Update
- Password Update

## To-Do Fields

Each To-Do includes:
- Title (required, max 255 characters)
- Description (optional)
- Status (pending or completed)
- Due Date (optional)

## Validation

Validation is implemented using:
- StoreTodoRequest
- UpdateTodoRequest

## Authorization

Authorization is implemented using:
- TodoPolicy

Users can only:
- view their own tasks
- edit their own tasks
- delete their own tasks

## Main Routes

### Authentication Routes
- GET /login - Login page
- POST /login - Authenticate user
- GET /register - Register page
- POST /register - Create account
- POST /logout - Logout user

### Dashboard Route
- GET /dashboard - User dashboard

### Profile Routes
- GET /profile - Profile edit page
- PATCH /profile - Update profile
- DELETE /profile - Delete account

### To-Do Routes
- GET /todos - List all logged-in user tasks
- GET /todos/create - Create task form
- POST /todos - Store new task
- GET /todos/{todo} - View single task
- GET /todos/{todo}/edit - Edit task form
- PUT /todos/{todo} - Update task
- DELETE /todos/{todo} - Delete task

## Filtering, Sorting, and Search

Supported query parameters on the To-Do listing page:

- Filter by status:
  /todos?status=pending
  /todos?status=completed

- Sort by due date:
  /todos?sort=due_date

- Sort by latest:
  /todos?sort=latest

- Sort by oldest:
  /todos?sort=oldest

- Search by title:
  /todos?search=meeting

## Security Notes

- Only authenticated users can access the dashboard and to-do module
- Users cannot access another user’s to-dos
- Authorization is enforced using policy
- Validation is handled using Form Requests
- CSRF protection is enabled by Laravel

## Notes

- The application is fully responsive
- The UI is built with Bootstrap and custom professional styling
- The project follows Laravel MVC conventions
- Authorization methods in controllers use AuthorizesRequests

## Author

Developed as part of a Laravel Technical Assessment.



## Installation

1. Clone the repository:
   git clone https://github.com/ashishskorasoft/TodoApp.git

2. Go to the project folder:
   cd TodoApp

3. Install PHP dependencies:
   composer install

4. Install frontend dependencies:
   npm install

5. Copy the environment file:
   cp .env.example .env

6. Generate the application key:
   php artisan key:generate

7. Update the `.env` file with your database credentials:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo_app
   DB_USERNAME=root
   DB_PASSWORD=

8. Run the migrations:
   php artisan migrate

9. Build frontend assets:
   npm run build

10. Start the development server:
    php artisan serve

11. Open the application in your browser:
    http://127.0.0.1:8000