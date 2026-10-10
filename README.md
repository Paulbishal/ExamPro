# ExamPro — Advanced Online Examination System

ExamPro is a web-based examination management system developed as part of my MCA project. It provides a centralized platform for managing users, question banks, online examinations, automated evaluation, and results.

## Features

- Role-based access for Administrator, Teacher, and Student
- User registration, login, and OTP verification workflows
- Subject and question-bank management
- Configurable examinations and randomized question selection
- Timed examination interface with question navigation
- Automatic evaluation and result generation
- Result and performance reports
- Database-backed application using MySQL

## Technology Stack

- Backend: PHP 8.x with PDO
- Database: MySQL 8.x
- Frontend: HTML5, CSS3, Bootstrap 5.3
- JavaScript: ES6
- Development environment: XAMPP
- Code editor: Visual Studio Code

## Local Installation

1. Install XAMPP with PHP and MySQL/MariaDB support.
2. Clone or copy this repository into your XAMPP `htdocs` directory.
3. Create a database for ExamPro using phpMyAdmin.
4. Import `database.sql` into the database.
5. Copy `config/db.example.php` to `config/db.php`.
6. Configure the local database name, username, and password in `config/db.php`.
7. Start Apache and MySQL from XAMPP.
8. Open `http://localhost/ExamPro/` in your browser.

Adjust the folder name and database settings to match your local setup.

## Security Notes

- Never commit real credentials, private keys, or personal user data.
- Configure credentials locally; use placeholder values in example files.
- Review the security limitations documented in the project before deployment.

## Project Status

Academic MCA project. The application is intended for local demonstration and evaluation; production deployment requires additional security and operational hardening.

## Future Enhancements

- Real email-based OTP delivery
- Stronger brute-force protection
- HTTPS and security-header configuration
- Expanded assessment formats
- Mobile-friendly enhancements and analytics

## Author

Bishal Paul

MCA Student | Aspiring Data Analyst & Software Developer
