# Task Management System - Backend Setup

## Database Structure

### Tables Created:
1. **users** - Stores all users (customers and developers)
   - Added `role` field: customer, frontend_developer, backend_developer, server_administrator

2. **projects** - Stores project information
   - id, name, description, timestamps

3. **project_members** - Assigns developers to projects
   - project_id, user_id, role
   - Unique constraint: Only 1 developer of each type per project

4. **tasks** - Stores tasks created by customers
   - project_id, customer_id, assigned_to, title, description, category, status
   - Categories: frontend, backend, server
   - Status: pending, in_progress, completed

## Models Created:
- **User** - With role-based methods (isCustomer, isDeveloper)
- **Project** - With relationships to tasks and members
- **ProjectMember** - Links users to projects with specific roles
- **Task** - Auto-assigns to developers based on category

## Key Features Implemented:

### 1. Customer Registration
- New users can register and are automatically assigned the 'customer' role
- Developers must be created manually by admin
- Registration form available at `/register`

### 2. Auto-Assignment Logic
When a customer creates a task, the system automatically assigns it to the appropriate developer based on:
- Task category (frontend/backend/server)
- Project membership (finds the developer assigned to that project)

### 3. Access Control
- **Customers**: Can only create tasks and view their own created tasks
- **Developers**: Can only view tasks assigned to them and update task status
- Customers CANNOT see who is assigned to their tasks

### 4. Controllers
- **TaskController**: Handles task CRUD operations with role-based access
- **ProjectController**: Displays projects and their details

### 5. Routes
```php
Route::resource('projects', ProjectController::class)->only(['index', 'show']);
Route::resource('tasks', TaskController::class);
```

## Sample Data Seeded:
- 1 Customer account (customer@example.com / password)
- 5 Frontend Developers (frontend1-5@example.com / password)
- 5 Backend Developers (backend1-5@example.com / password)
- 5 Server Administrators (server1-5@example.com / password)
- 5 Projects with 1 developer of each type assigned

## API Endpoints:

### Tasks
- GET /tasks - List tasks (filtered by user role)
- GET /tasks/create - Show create form (customers only)
- POST /tasks - Create new task (auto-assigns developer)
- GET /tasks/{id} - View task details
- PUT /tasks/{id} - Update task status (developers only)
- DELETE /tasks/{id} - Delete task (customer who created it only)

### Projects
- GET /projects - List all projects
- GET /projects/{id} - View project details with members and tasks

## Next Steps for Frontend:
1. Create task creation form with project and category selection
2. Display task list based on user role
3. Show task details (hide assignee from customers)
4. Allow developers to update task status
5. Display project list and details

## Testing Accounts:
- Register new customer at: /register (automatically gets 'customer' role)
- Existing customer: customer@example.com / password
- Frontend Dev: frontend1@example.com / password
- Backend Dev: backend1@example.com / password
- Server Admin: server1@example.com / password
