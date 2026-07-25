-- ============================================================================
-- Smart Hospital Management System - Clean InfinityFree Database Import
-- (No CREATE DATABASE or USE statements - 100% compatible with free hosts)
-- ============================================================================

-- Drop existing views/triggers/procedures/tables if re-importing
DROP TRIGGER IF EXISTS `reduce_medicine_stock_after_prescription`;
DROP PROCEDURE IF EXISTS `sp_get_patient_history`;
DROP VIEW IF EXISTS `vw_patient_billing_summary`;
DROP VIEW IF EXISTS `vw_active_admissions`;
DROP VIEW IF EXISTS `vw_doctor_details`;

DROP TABLE IF EXISTS `bills`;
DROP TABLE IF EXISTS `prescriptions`;
DROP TABLE IF EXISTS `admissions`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `medicines`;
DROP TABLE IF EXISTS `doctors`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------------------------------------------------------
-- 1. USERS TABLE (Admin authentication)
-- ----------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'Admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$wT3wY20y9TlywYjX2M/v6.RkE2Yc1Z9gG4X2QvW.7l5h1A7.6E64i', 'Admin');

-- ----------------------------------------------------------------------------
-- 2. DEPARTMENTS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `departments` (`id`, `name`, `description`) VALUES
(1, 'Cardiology', 'Heart and cardiovascular system medical care.'),
(2, 'Neurology', 'Diagnosis and treatment of nervous system disorders.'),
(3, 'Orthopedics', 'Musculoskeletal system, bones, and joint care.'),
(4, 'Pediatrics', 'Comprehensive medical care for infants, children, and adolescents.'),
(5, 'Emergency', '24/7 acute emergency and trauma care.'),
(6, 'General Medicine', 'General diagnosis and primary adult healthcare.');

-- ----------------------------------------------------------------------------
-- 3. DOCTORS TABLE
-- ----------------------------------------------------------------------------
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
(1, 1, 'Dr. Robert Chen', '+1 (555) 234-5678', 'robert.chen@hospital.org', 'Mon, Wed, Fri'),
(2, 2, 'Dr. Sarah Jenkins', '+1 (555) 345-6789', 'sarah.jenkins@hospital.org', 'Tue, Thu, Sat'),
(3, 3, 'Dr. Michael Vance', '+1 (555) 456-7890', 'michael.vance@hospital.org', 'Mon, Tue, Thu'),
(4, 4, 'Dr. Emily Watson', '+1 (555) 567-8901', 'emily.watson@hospital.org', 'Mon, Wed, Sat'),
(5, 5, 'Dr. James Wilson', '+1 (555) 678-9012', 'james.wilson@hospital.org', 'Everyday (Shift A)'),
(6, 6, 'Dr. Linda Taylor', '+1 (555) 789-0123', 'linda.taylor@hospital.org', 'Wed, Thu, Fri');

-- ----------------------------------------------------------------------------
-- 4. PATIENTS TABLE
-- ----------------------------------------------------------------------------
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
(1, 'John Doe', 'Male', 45, '+1 (555) 111-2233', '123 Pine St, Suite 4B, Metro City', 'A+'),
(2, 'Jane Smith', 'Female', 32, '+1 (555) 222-3344', '456 Oak Avenue, Springfield', 'O+'),
(3, 'David Miller', 'Male', 58, '+1 (555) 333-4455', '789 Maple Drive, Riverdale', 'B-'),
(4, 'Alice Johnson', 'Female', 24, '+1 (555) 444-5566', '321 Elm Street, Lakeshore', 'AB+'),
(5, 'Robert Brown', 'Male', 67, '+1 (555) 555-6677', '654 Cedar Road, Hilltop', 'O-');

-- ----------------------------------------------------------------------------
-- 5. APPOINTMENTS TABLE
-- ----------------------------------------------------------------------------
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

-- ----------------------------------------------------------------------------
-- 6. ROOMS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_number` VARCHAR(20) NOT NULL UNIQUE,
  `room_type` ENUM('General', 'Private', 'ICU', 'VIP') NOT NULL,
  `floor` INT NOT NULL,
  `status` ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available',
  `charge_per_day` DECIMAL(10,2) NOT NULL DEFAULT 500.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `rooms` (`id`, `room_number`, `room_type`, `floor`, `status`, `charge_per_day`) VALUES
(1, 'R-101', 'General', 1, 'Occupied', 500.00),
(2, 'R-102', 'General', 1, 'Available', 500.00),
(3, 'P-201', 'Private', 2, 'Occupied', 1500.00),
(4, 'P-202', 'Private', 2, 'Available', 1500.00),
(5, 'ICU-301', 'ICU', 3, 'Available', 4500.00),
(6, 'VIP-401', 'VIP', 4, 'Maintenance', 3000.00);

-- ----------------------------------------------------------------------------
-- 7. ADMISSIONS TABLE
-- ----------------------------------------------------------------------------
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

-- ----------------------------------------------------------------------------
-- 8. MEDICINES TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE `medicines` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `company` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_quantity` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `medicines` (`id`, `name`, `company`, `price`, `stock_quantity`) VALUES
(1, 'Amoxicillin 500mg', 'Pfizer', 15.50, 150),
(2, 'Paracetamol 650mg', 'GlaxoSmithKline', 5.00, 500),
(3, 'Atorvastatin 20mg', 'Novartis', 42.00, 80),
(4, 'Metformin 500mg', 'Sanofi', 12.00, 220),
(5, 'Ibuprofen 400mg', 'Bayer', 8.50, 300),
(6, 'Omeprazole 20mg', 'AstraZeneca', 25.00, 95);

-- ----------------------------------------------------------------------------
-- 9. PRESCRIPTIONS TABLE
-- ----------------------------------------------------------------------------
CREATE TABLE `prescriptions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `medicine_id` INT NOT NULL,
  `dosage` VARCHAR(100) NOT NULL,
  `duration` VARCHAR(50) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_prescriptions_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prescriptions_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_prescriptions_medicine` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `prescriptions` (`id`, `patient_id`, `doctor_id`, `medicine_id`, `dosage`, `duration`) VALUES
(1, 1, 1, 3, '1 tablet daily after dinner', '30 Days'),
(2, 1, 1, 2, '1 tablet thrice daily', '5 Days'),
(3, 3, 2, 1, '1 capsule twice daily', '7 Days');

-- ----------------------------------------------------------------------------
-- 10. BILLS TABLE
-- ----------------------------------------------------------------------------
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
(1, 3, 2, 150.00, 12000.00, 12150.00, 'Paid'),
(2, 1, 1, 85.00, 2000.00, 2085.00, 'Pending');

-- ============================================================================
-- SQL ADVANCED FEATURES (VIEWS, TRIGGER, STORED PROCEDURE)
-- ============================================================================

-- VIEW 1: Doctor Details
CREATE VIEW `vw_doctor_details` AS
SELECT 
    d.id AS doctor_id,
    d.name AS doctor_name,
    dep.name AS department_name,
    d.phone,
    d.email,
    d.available_days,
    COUNT(a.id) AS total_appointments
FROM doctors d
JOIN departments dep ON d.department_id = dep.id
LEFT JOIN appointments a ON d.id = a.doctor_id
GROUP BY d.id, d.name, dep.name, d.phone, d.email, d.available_days;

-- VIEW 2: Active Admissions
CREATE VIEW `vw_active_admissions` AS
SELECT 
    adm.id AS admission_id,
    p.id AS patient_id,
    p.name AS patient_name,
    p.phone AS patient_phone,
    r.room_number,
    r.room_type,
    r.charge_per_day,
    adm.admission_date,
    DATEDIFF(CURRENT_DATE(), adm.admission_date) + 1 AS total_days
FROM admissions adm
JOIN patients p ON adm.patient_id = p.id
JOIN rooms r ON adm.room_id = r.id
WHERE adm.status = 'Admitted';

-- VIEW 3: Patient Billing Summary
CREATE VIEW `vw_patient_billing_summary` AS
SELECT 
    b.id AS bill_id,
    p.name AS patient_name,
    adm.id AS admission_id,
    b.medicine_cost,
    b.room_charge,
    b.total_amount,
    b.payment_status,
    b.created_at AS bill_date
FROM bills b
JOIN patients p ON b.patient_id = p.id
JOIN admissions adm ON b.admission_id = adm.id;
