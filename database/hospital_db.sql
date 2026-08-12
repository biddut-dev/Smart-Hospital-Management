CREATE DATABASE IF NOT EXISTS `hospital_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hospital_db`;

DROP TABLE IF EXISTS `hospital_db`.`bills`;
DROP TABLE IF EXISTS `hospital_db`.`admissions`;
DROP TABLE IF EXISTS `hospital_db`.`appointments`;
DROP TABLE IF EXISTS `hospital_db`.`rooms`;
DROP TABLE IF EXISTS `hospital_db`.`medicines`;
DROP TABLE IF EXISTS `hospital_db`.`doctors`;
DROP TABLE IF EXISTS `hospital_db`.`departments`;
DROP TABLE IF EXISTS `hospital_db`.`patients`;
DROP TABLE IF EXISTS `hospital_db`.`users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'Admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'admin', '12345', 'admin@smarthospital.bd', 'Admin'),
(2, 'student', '11111', 'biddut@gmail.com', 'User');

CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `departments` (`id`, `name`, `description`) VALUES
(1, 'Cardiology (হৃদরোগ)', 'Heart and cardiovascular care and surgery.'),
(2, 'Neurology (স্নায়ুরোগ)', 'Diagnosis and treatment of brain and nervous system disorders.'),
(3, 'Orthopedics (অস্থিশল্য)', 'Bone, joint, and musculoskeletal system care.'),
(4, 'Pediatrics (শিশু রোগ)', 'Specialized medical care for newborns, children, and teens.'),
(5, 'Emergency (জরুরী)', '24/7 acute trauma and emergency intensive care.'),
(6, 'General Medicine (মেডিসিন)', 'Primary healthcare and diagnosis for adult internal diseases.');

CREATE TABLE `doctors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `department_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `available_days` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_doctors_department` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `doctors` (`id`, `department_id`, `name`, `phone`, `email`, `available_days`) VALUES
(1, 1, 'Dr. Tanvir Ahmed', '+880 1711-234567', 'tanvir.cardio@smarthospital.bd', 'Sun, Tue, Thu'),
(2, 2, 'Dr. Nazia Nuzhat', '+880 1819-345678', 'nazia.neuro@smarthospital.bd', 'Sat, Mon, Wed'),
(3, 3, 'Dr. Rafiqul Islam', '+880 1912-456789', 'rafiqul.ortho@smarthospital.bd', 'Sun, Mon, Wed'),
(4, 4, 'Dr. Nusrat Jahan', '+880 1613-567890', 'nusrat.pedia@smarthospital.bd', 'Sat, Tue, Thu'),
(5, 5, 'Dr. Mahbubur Rahman', '+880 1514-678901', 'mahbub.er@smarthospital.bd', 'Everyday (24/7 Shift A)'),
(6, 6, 'Dr. Farhana Chowdhury', '+880 1715-789012', 'farhana.med@smarthospital.bd', 'Mon, Wed, Fri');

CREATE TABLE `patients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `gender` ENUM('Male', 'Female', 'Other') NOT NULL,
  `age` INT NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NOT NULL,
  `blood_group` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `patients` (`id`, `name`, `gender`, `age`, `phone`, `address`, `blood_group`) VALUES
(1, 'Abu Fahad Biddut', 'Male', 24, '01712-345678', 'House 45, Road 7/A, Dhanmondi, Dhaka', 'B+'),
(2, 'Rahat Hossain', 'Male', 32, '01819-876543', 'Sector 4, Uttara, Dhaka', 'O+'),
(3, 'Nusrat Sharmin', 'Female', 28, '01911-223344', 'Agrabad Commercial Area, Chittagong', 'A+'),
(4, 'Anisur Rahman', 'Male', 54, '01615-556677', 'Zindabazar, Sylhet', 'AB+'),
(5, 'Sabiha Chowdhury', 'Female', 42, '01516-998877', 'Block-D, Mirpur-10, Dhaka', 'O-');

CREATE TABLE `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `appointment_date` DATE NOT NULL,
  `appointment_time` TIME NOT NULL,
  `status` ENUM('Scheduled', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Scheduled',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_appointments_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_appointments_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`) VALUES
(1, 1, 1, CURRENT_DATE(), '10:00:00', 'Scheduled'),
(2, 2, 4, CURRENT_DATE(), '11:30:00', 'Scheduled'),
(3, 3, 2, CURRENT_DATE() - INTERVAL 1 DAY, '14:00:00', 'Completed'),
(4, 4, 3, CURRENT_DATE() + INTERVAL 2 DAY, '09:15:00', 'Scheduled'),
(5, 5, 6, CURRENT_DATE() - INTERVAL 3 DAY, '15:45:00', 'Completed');

CREATE TABLE `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(20) NOT NULL UNIQUE,
  `room_type` ENUM('General', 'Private', 'ICU', 'VIP') NOT NULL,
  `floor` INT NOT NULL,
  `status` ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available',
  `charge_per_day` DECIMAL(10,2) NOT NULL DEFAULT 800.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `floor`, `status`, `charge_per_day`) VALUES
(1, 'General-101', 'General', 1, 'Occupied', 800.00),
(2, 'General-102', 'General', 1, 'Available', 800.00),
(3, 'Cabin-201', 'Private', 2, 'Occupied', 2500.00),
(4, 'Cabin-202', 'Private', 2, 'Available', 2500.00),
(5, 'ICU-301', 'ICU', 3, 'Available', 8000.00),
(6, 'VIP-401', 'VIP', 4, 'Maintenance', 5000.00);

CREATE TABLE `admissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `admission_date` DATE NOT NULL,
  `discharge_date` DATE DEFAULT NULL,
  `status` ENUM('Admitted', 'Discharged') NOT NULL DEFAULT 'Admitted',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_admissions_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_admissions_room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admissions` (`id`, `patient_id`, `room_id`, `admission_date`, `discharge_date`, `status`) VALUES
(1, 1, 1, CURRENT_DATE() - INTERVAL 4 DAY, NULL, 'Admitted'),
(2, 3, 3, CURRENT_DATE() - INTERVAL 10 DAY, CURRENT_DATE() - INTERVAL 2 DAY, 'Discharged');

CREATE TABLE `medicines` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `company` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `medicines` (`id`, `name`, `company`, `price`, `stock_quantity`) VALUES
(1, 'Napa Extra 500mg', 'Beximco Pharmaceuticals', 2.50, 500),
(2, 'Seclo 20mg', 'Square Pharmaceuticals', 7.00, 350),
(3, 'Ace 500mg', 'Square Pharmaceuticals', 2.00, 600),
(4, 'Sergel 20mg', 'Healthcare Pharmaceuticals', 7.00, 400),
(5, 'Ciprocin 500mg', 'Square Pharmaceuticals', 15.00, 200),
(6, 'Monas 10mg', 'Incepta Pharmaceuticals', 16.00, 250);

CREATE TABLE `bills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_id` INT NOT NULL,
  `admission_id` INT NOT NULL,
  `medicine_cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `room_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` ENUM('Pending', 'Paid') NOT NULL DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_bills_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bills_admission` FOREIGN KEY (`admission_id`) REFERENCES `admissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `bills` (`id`, `patient_id`, `admission_id`, `medicine_cost`, `room_charge`, `total_amount`, `payment_status`) VALUES
(1, 3, 2, 350.00, 20000.00, 20350.00, 'Paid'),
(2, 1, 1, 150.00, 3200.00, 3350.00, 'Pending');
