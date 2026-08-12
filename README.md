# Smart Hospital Management System

A simple, procedural PHP & MySQL web application for managing hospital operations, built with direct procedural MySQLi logic matching standard lab exercises.

## 🚀 Features & Modules

- **Dashboard (`index.php`)**: Real-time overview showing total counts of Patients, Doctors, Appointments, and Medicines, along with recent appointments and active doctor lists.
- **Patient Management (`patient/`)**: Register, view list, edit details, update, and delete patient records.
- **Doctor Management (`doctor/`)**: Add doctors with department assignments, view schedules, edit info, update, and delete.
- **Appointment Management (`appointment/`)**: Schedule patient appointments with doctors, manage dates/times, update statuses, and delete.
- **Medicine & Pharmacy (`medicine/`)**: Track medicine stock quantities, pricing, pharmaceutical companies, edit info, update, and delete.
- **Department Management (`department/`)**: Create and manage hospital medical departments (Cardiology, Neurology, Orthopedics, Pediatrics, Emergency, General Medicine).
- **User Authentication (`login.html` & `signup.html`)**: Simple procedural user registration and login.

## 📁 Directory Structure

```text
smart_hospital/
├── connection.php      # Database connection
├── nav.php             # Navigation bar component
├── style.css           # Custom layout stylesheet
├── index.php           # Main Dashboard
├── login.html & .php   # User Login
├── signup.html & .php  # User Registration
│
├── patient/            # Patient CRUD module
├── doctor/             # Doctor CRUD module
├── appointment/        # Appointment CRUD module
├── medicine/           # Pharmacy Medicine CRUD module
├── department/         # Department CRUD module
└── database/
    └── hospital_db.sql # Database schema & sample dataset
```

## 🛠️ Database Setup

1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Create a new database named `hospital_db`.
3. Import the `database/hospital_db.sql` file.
4. Access the application at `http://localhost/smart_hospital/`.
