import os
import ftplib

FTP_HOST = "ftpupload.net"
FTP_USER = "if0_42496609"
DB_HOST = "sql104.infinityfree.com"
DB_USER = "if0_42496609"
DB_NAME = "if0_42496609_hospital_db"

print("==================================================================")
print(" Smart Hospital Management System - Automated FTP & Auto-DB Setup")
print("==================================================================")
ftp_pass = input("Enter your InfinityFree Password (from eye icon): ").strip()

if not ftp_pass:
    print("Error: Password cannot be empty!")
    exit(1)

try:
    print(f"\nConnecting to FTP Server {FTP_HOST}...")
    ftp = ftplib.FTP(FTP_HOST, FTP_USER, ftp_pass)
    print("Connected & Authenticated successfully!")

    ftp.cwd("/htdocs")
    project_dir = os.path.dirname(os.path.abspath(__file__))
    skip_files = ["upload_to_infinityfree.py", ".git", "_remote_db.php"]

    def upload_directory(local_dir):
        for item in os.listdir(local_dir):
            if item in skip_files or item.startswith("."):
                continue

            local_path = os.path.join(local_dir, item)
            rel_path = os.path.relpath(local_path, project_dir).replace("\\", "/")

            if os.path.isdir(local_path):
                try:
                    ftp.mkd(item)
                except ftplib.error_perm:
                    pass
                
                ftp.cwd(item)
                upload_directory(local_path)
                ftp.cwd("..")
            else:
                if item == "db.php" and "includes" in local_dir.lower():
                    print("Uploading bulletproof remote db.php with auto-table creator...")
                    remote_db_content = f"""<?php
/**
 * Smart Hospital Management System - Online Database Connection & Auto-Setup
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_host = '{DB_HOST}';
$db_user = '{DB_USER}';
$db_pass = '{ftp_pass}';
$db_name = '{DB_NAME}';

try {{
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Check if doctors table exists
    $table_check = false;
    try {{
        $table_check = $pdo->query("SHOW TABLES LIKE 'doctors'")->fetch();
    }} catch (Exception $e) {{}}

    if (!$table_check) {{
        $queries = [
            "CREATE TABLE IF NOT EXISTS `users` (`id` INT AUTO_INCREMENT PRIMARY KEY, `username` VARCHAR(50) NOT NULL UNIQUE, `password` VARCHAR(255) NOT NULL, `role` VARCHAR(20) NOT NULL DEFAULT 'Admin', `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `users` (`id`, `username`, `password`, `role`) VALUES (1, 'admin', '$2y$10$wT3wY20y9TlywYjX2M/v6.RkE2Yc1Z9gG4X2QvW.7l5h1A7.6E64i', 'Admin')",

            "CREATE TABLE IF NOT EXISTS `departments` (`id` INT AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(100) NOT NULL UNIQUE, `description` TEXT, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `departments` (`id`, `name`, `description`) VALUES (1, 'Cardiology', 'Heart medical care.'), (2, 'Neurology', 'Nervous system disorders.'), (3, 'Orthopedics', 'Bones and joint care.'), (4, 'Pediatrics', 'Children care.'), (5, 'Emergency', '24/7 emergency.'), (6, 'General Medicine', 'General diagnosis.')",

            "CREATE TABLE IF NOT EXISTS `doctors` (`id` INT AUTO_INCREMENT PRIMARY KEY, `department_id` INT NOT NULL, `name` VARCHAR(100) NOT NULL, `phone` VARCHAR(20) NOT NULL, `email` VARCHAR(100) NOT NULL, `available_days` VARCHAR(100) NOT NULL, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `doctors` (`id`, `department_id`, `name`, `phone`, `email`, `available_days`) VALUES (1, 1, 'Dr. Robert Chen', '+1 (555) 234-5678', 'robert.chen@hospital.org', 'Mon, Wed, Fri'), (2, 2, 'Dr. Sarah Jenkins', '+1 (555) 345-6789', 'sarah.jenkins@hospital.org', 'Tue, Thu, Sat'), (3, 3, 'Dr. Michael Vance', '+1 (555) 456-7890', 'michael.vance@hospital.org', 'Mon, Tue, Thu'), (4, 4, 'Dr. Emily Watson', '+1 (555) 567-8901', 'emily.watson@hospital.org', 'Mon, Wed, Sat'), (5, 5, 'Dr. James Wilson', '+1 (555) 678-9012', 'james.wilson@hospital.org', 'Everyday'), (6, 6, 'Dr. Linda Taylor', '+1 (555) 789-0123', 'linda.taylor@hospital.org', 'Wed, Thu, Fri')",

            "CREATE TABLE IF NOT EXISTS `patients` (`id` INT AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(100) NOT NULL, `gender` ENUM('Male', 'Female', 'Other') NOT NULL, `age` INT NOT NULL, `phone` VARCHAR(20) NOT NULL, `address` TEXT NOT NULL, `blood_group` VARCHAR(10) NOT NULL, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `patients` (`id`, `name`, `gender`, `age`, `phone`, `address`, `blood_group`) VALUES (1, 'John Doe', 'Male', 45, '+1 (555) 111-2233', '123 Pine St', 'A+'), (2, 'Jane Smith', 'Female', 32, '+1 (555) 222-3344', '456 Oak Ave', 'O+'), (3, 'David Miller', 'Male', 58, '+1 (555) 333-4455', '789 Maple Dr', 'B-'), (4, 'Alice Johnson', 'Female', 24, '+1 (555) 444-5566', '321 Elm St', 'AB+'), (5, 'Robert Brown', 'Male', 67, '+1 (555) 555-6677', '654 Cedar Rd', 'O-')",

            "CREATE TABLE IF NOT EXISTS `appointments` (`id` INT AUTO_INCREMENT PRIMARY KEY, `patient_id` INT NOT NULL, `doctor_id` INT NOT NULL, `appointment_date` DATE NOT NULL, `appointment_time` TIME NOT NULL, `status` ENUM('Scheduled', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Scheduled', `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`) VALUES (1, 1, 1, CURRENT_DATE(), '10:00:00', 'Scheduled'), (2, 2, 4, CURRENT_DATE(), '11:30:00', 'Scheduled'), (3, 3, 2, CURRENT_DATE() - INTERVAL 1 DAY, '14:00:00', 'Completed'), (4, 4, 3, CURRENT_DATE() + INTERVAL 2 DAY, '09:15:00', 'Scheduled'), (5, 5, 6, CURRENT_DATE() - INTERVAL 3 DAY, '15:45:00', 'Completed')",

            "CREATE TABLE IF NOT EXISTS `rooms` (`id` INT AUTO_INCREMENT PRIMARY KEY, `room_number` VARCHAR(20) NOT NULL UNIQUE, `room_type` ENUM('General', 'Private', 'ICU', 'VIP') NOT NULL, `floor` INT NOT NULL, `status` ENUM('Available', 'Occupied', 'Maintenance') NOT NULL DEFAULT 'Available', `charge_per_day` DECIMAL(10,2) NOT NULL DEFAULT 500.00, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `rooms` (`id`, `room_number`, `room_type`, `floor`, `status`, `charge_per_day`) VALUES (1, 'R-101', 'General', 1, 'Occupied', 500.00), (2, 'R-102', 'General', 1, 'Available', 500.00), (3, 'P-201', 'Private', 2, 'Occupied', 1500.00), (4, 'P-202', 'Private', 2, 'Available', 1500.00), (5, 'ICU-301', 'ICU', 3, 'Available', 4500.00), (6, 'VIP-401', 'VIP', 4, 'Maintenance', 3000.00)",

            "CREATE TABLE IF NOT EXISTS `admissions` (`id` INT AUTO_INCREMENT PRIMARY KEY, `patient_id` INT NOT NULL, `room_id` INT NOT NULL, `admission_date` DATE NOT NULL, `discharge_date` DATE DEFAULT NULL, `status` ENUM('Admitted', 'Discharged') NOT NULL DEFAULT 'Admitted', `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `admissions` (`id`, `patient_id`, `room_id`, `admission_date`, `discharge_date`, `status`) VALUES (1, 1, 1, CURRENT_DATE() - INTERVAL 4 DAY, NULL, 'Admitted'), (2, 3, 3, CURRENT_DATE() - INTERVAL 10 DAY, CURRENT_DATE() - INTERVAL 2 DAY, 'Discharged')",

            "CREATE TABLE IF NOT EXISTS `medicines` (`id` INT AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(100) NOT NULL, `company` VARCHAR(100) NOT NULL, `price` DECIMAL(10,2) NOT NULL, `stock_quantity` INT NOT NULL DEFAULT 0, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `medicines` (`id`, `name`, `company`, `price`, `stock_quantity`) VALUES (1, 'Amoxicillin 500mg', 'Pfizer', 15.50, 150), (2, 'Paracetamol 650mg', 'GlaxoSmithKline', 5.00, 500), (3, 'Atorvastatin 20mg', 'Novartis', 42.00, 80), (4, 'Metformin 500mg', 'Sanofi', 12.00, 220), (5, 'Ibuprofen 400mg', 'Bayer', 8.50, 300), (6, 'Omeprazole 20mg', 'AstraZeneca', 25.00, 95)",

            "CREATE TABLE IF NOT EXISTS `prescriptions` (`id` INT AUTO_INCREMENT PRIMARY KEY, `patient_id` INT NOT NULL, `doctor_id` INT NOT NULL, `medicine_id` INT NOT NULL, `dosage` VARCHAR(100) NOT NULL, `duration` VARCHAR(50) NOT NULL, `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `prescriptions` (`id`, `patient_id`, `doctor_id`, `medicine_id`, `dosage`, `duration`) VALUES (1, 1, 1, 3, '1 tablet daily', '30 Days'), (2, 1, 1, 2, '1 tablet thrice daily', '5 Days'), (3, 3, 2, 1, '1 capsule twice daily', '7 Days')",

            "CREATE TABLE IF NOT EXISTS `bills` (`id` INT AUTO_INCREMENT PRIMARY KEY, `patient_id` INT NOT NULL, `admission_id` INT NOT NULL, `medicine_cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00, `room_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00, `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00, `payment_status` ENUM('Pending', 'Paid') NOT NULL DEFAULT 'Pending', `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "INSERT IGNORE INTO `bills` (`id`, `patient_id`, `admission_id`, `medicine_cost`, `room_charge`, `total_amount`, `payment_status`) VALUES (1, 3, 2, 150.00, 12000.00, 12150.00, 'Paid'), (2, 1, 1, 85.00, 2000.00, 2085.00, 'Pending')",

            "CREATE OR REPLACE VIEW `vw_doctor_details` AS SELECT d.id AS doctor_id, d.name AS doctor_name, dep.name AS department_name, d.phone, d.email, d.available_days, COUNT(a.id) AS total_appointments FROM doctors d JOIN departments dep ON d.department_id = dep.id LEFT JOIN appointments a ON d.id = a.doctor_id GROUP BY d.id, d.name, dep.name, d.phone, d.email, d.available_days",
            "CREATE OR REPLACE VIEW `vw_active_admissions` AS SELECT adm.id AS admission_id, p.id AS patient_id, p.name AS patient_name, p.phone AS patient_phone, r.room_number, r.room_type, r.charge_per_day, adm.admission_date, DATEDIFF(CURRENT_DATE(), adm.admission_date) + 1 AS total_days FROM admissions adm JOIN patients p ON adm.patient_id = p.id JOIN rooms r ON adm.room_id = r.id WHERE adm.status = 'Admitted'",
            "CREATE OR REPLACE VIEW `vw_patient_billing_summary` AS SELECT b.id AS bill_id, p.name AS patient_name, adm.id AS admission_id, b.medicine_cost, b.room_charge, b.total_amount, b.payment_status, b.created_at AS bill_date FROM bills b JOIN patients p ON b.patient_id = p.id JOIN admissions adm ON b.admission_id = adm.id"
        ];

        foreach ($queries as $q) {{
            try {{ $pdo->exec($q); }} catch (Exception $e) {{}}
        }}
    }}
}} catch (PDOException $e) {{
    die("<div style='font-family:sans-serif; padding:20px; background:#fee2e2; border:1px solid #ef4444; border-radius:8px; margin:20px; color:#991b1b;'>
            <h3>Database Connection Failed</h3>
            <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
         </div>");
}}
"""
                    temp_file = os.path.join(project_dir, "_remote_db.php")
                    with open(temp_file, "w", encoding="utf-8") as f:
                        f.write(remote_db_content)
                    
                    with open(temp_file, "rb") as f:
                        ftp.storbinary(f"STOR {item}", f)
                    
                    if os.path.exists(temp_file):
                        os.remove(temp_file)
                else:
                    print(f"Uploading: {rel_path}")
                    with open(local_path, "rb") as f:
                        ftp.storbinary(f"STOR {item}", f)

    upload_directory(project_dir)

    ftp.quit()
    print("\n==================================================================")
    print(" BULLETPROOF REMOTE DB CONFIG UPLOADED SUCCESSFULLY!")
    print("==================================================================")

except Exception as e:
    print(f"\nFTP Upload Error: {e}")
