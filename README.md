# MVC User Management System

This project is a simple PHP-based user management application built with an MVC-style folder structure. It allows users to log in, create new users, view the full list, update existing records, and delete entries.

## Features

- User login with session-based authentication
- Create new users
- View all users in a table
- Update existing user details
- Delete users
- Bootstrap-based UI
- Status badges for quick visual recognition
- Responsive layout for different screen sizes

## Project Structure

- [config.php](config.php) - Database configuration values
- [database.php](database.php) - Database connection setup using MySQLi
- [controllers/UserController.php](controllers/UserController.php) - Controller logic for user actions
- [models/Users.php](models/Users.php) - Database model for CRUD operations
- [views/](views/) - All front-end pages for login, create, list, and update

## How It Works

1. The user opens the login page at [views/login.php](views/login.php).
2. If the credentials are valid, the app stores a session value and redirects the user to [views/index.php](views/index.php).
3. The create page submits user data to the controller/model layer, which inserts a new record into the database.
4. The list page at [views/display.php](views/display.php) fetches all users from the database and displays them in a table.
5. The edit page at [views/update.php](views/update.php) loads a single user by ID and allows updating the record.
6. Deleting a user removes the selected record from the database.
7. The app uses sessions to prevent access to protected pages unless a user is logged in.

## Application Flow

### Login Flow

- The user enters a username and password.
- The app checks the credentials.
- On success, it sets `$_SESSION['logged_in'] = true` and redirects to the dashboard-like create page.
- On failure, it displays an error message.

### Create User Flow

- The user fills out the name, description, and status fields.
- The form submits to [views/index.php](views/index.php).
- The controller calls the model method to insert the data into the `profiles` table.
- A success or error message is shown after submission.

### Display Users Flow

- The page retrieves all records from the database using the model.
- Each row is rendered in the table with an action column for editing and deleting.
- A modal confirmation appears before deletion.

### Update User Flow

- The edit button sends the selected user ID in the URL.
- [views/update.php](views/update.php) retrieves the record by ID.
- The form is pre-filled with the current values.
- On submit, the updated values are saved back to the database.

## Setup Instructions

### 1. Place the project in XAMPP

Copy the project folder into your XAMPP web server directory:

- Windows: `C:\xampp\htdocs\`

### 2. Start XAMPP services

Start Apache and MySQL from the XAMPP control panel.

### 3. Create the database

Create a MySQL database named `websys21` (or update the values in [config.php](config.php)).

You can create the required table with the following SQL:

```sql
CREATE TABLE profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL
);
```

### 4. Configure the database connection

Edit [config.php](config.php) if your MySQL credentials differ:

```php
return [
    'HOST' => 'localhost',
    'USER' => 'root',
    'PASSWORD' => '',
    'DATABASE' => 'websys21'
];
```

### 5. Open the site

Open the following URL in your browser:

```text
http://localhost/MVC/views/login.php
```

## Default Login

The demo login uses:

- Username: `admin`
- Password: `password`

## Example Usage

1. Open the login page.
2. Sign in with the demo credentials.
3. Create a new user.
4. View the record in the user list.
5. Edit or delete the record as needed.

## Notes

- The login page uses a simple hardcoded authentication check for demonstration purposes.
- The app uses Bootstrap for styling and MySQLi for database access.
- Sessions are used to protect pages from unauthorized access.
- This project is intended as a learning example and can be extended with real authentication and validation.

## Future Improvements

Possible enhancements for this project include:

- Replacing hardcoded login validation with a real database-backed authentication system
- Adding form validation and error handling improvements
- Adding search and pagination for larger datasets
- Implementing password hashing for better security
- Converting the project to a more advanced framework structure
