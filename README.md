# Task Management System
### A simple Task Management System built with PHP, MySQL, and XAMPP that allows users (admin and employees) to manage tasks, assign roles, track task progress, and receive notifications.


## Full Tutorial

[On Youtube](https://www.youtube.com/playlist?list=PL2WFgdVk-usHC-HHC0SkpsmHquwHB0Aiy)

## DEMO

[DEMO](https://youtu.be/Ff3ug0eqrWI)

## Features

+ User Role Management: Supports two roles - Admin and Employee.
+ Task Management: Create, update, delete, and view tasks.
+ Task Categorization: Filter tasks by status, and due date.
+ Authentication and Authorization: Secure login system with role-based access control.
+ Notifications: Notify users of assigned tasks and important updates.
+ Task Filtering: Filter tasks by priority, status, deadline.
+ Task Deadlines: Track due dates and overdue tasks.

## Requirements

+ XAMPP (PHP, MySQL, Apache)
+ PHP 7.4 or higher
+ MySQL 5.7 or higher

## Login Credentials:

### Default Admin User:

+ Username: admin
+ Password: 123
### Default Employee User:

+ Username: john 
+ Password: 123

## Contributing

If you'd like to contribute, please fork the repository and submit a pull request. Any contributions are welcome.

## License

This project is open-source and available under the MIT License.

## File Upload for Completed Tasks (new)

+ When an employee marks a task as **completed**, a **Submit your work** box appears on the task's edit page where they can upload **PDF, DOC, DOCX, JPG, JPEG or PNG** files (max 5 MB each, up to 10 per task).
+ The admin sees the uploaded files in **All Tasks -> Submitted Files** (and on the task's edit page) and can **View** (PDF/images) or **Download** them. The admin also gets a notification when a file is uploaded.
+ Files are stored in `uploads/task_files/` under random names. That folder is blocked from direct browser access (`.htaccess`); files are served only through `download-file.php`, which checks who is asking (admin = all files, employee = only their own).
+ Deleting a task also deletes its uploaded files.

### Setup for the upload feature
1. Import `add_file_uploads.sql` into your existing `task_management_db` database (phpMyAdmin -> SQL tab). Fresh installs can just import `task_management_db.sql`, which already includes it.
2. Make sure the `uploads/task_files/` folder is writable by the web server (automatic on XAMPP/Windows).
3. Apache must allow `.htaccess` for the project folder (`AllowOverride All`, the XAMPP default). On Nginx, deny access to `/uploads/` in the server config.
