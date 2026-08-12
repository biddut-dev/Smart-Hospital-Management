<?php
include "../connection.php";

if (isset($_GET['id'])) {
    $admission_id = intval($_GET['id']);
    
    // Fetch admission details
    $sql_adm = "SELECT admissions.*, rooms.charge_per_day, rooms.id AS room_id 
                FROM admissions 
                JOIN rooms ON admissions.room_id = rooms.id 
                WHERE admissions.id = $admission_id";
    $res_adm = mysqli_query($conn, $sql_adm);
    
    if ($res_adm && mysqli_num_rows($res_adm) > 0) {
        $adm = mysqli_fetch_assoc($res_adm);
        $patient_id = $adm['patient_id'];
        $room_id = $adm['room_id'];
        $charge_per_day = $adm['charge_per_day'];
        
        $admission_date = strtotime($adm['admission_date']);
        $today = strtotime(date('Y-m-d'));
        
        // Calculate days stayed (minimum 1 day)
        $datediff = $today - $admission_date;
        $days_stayed = max(1, round($datediff / (60 * 60 * 24)));
        
        $room_total_charge = $days_stayed * $charge_per_day;
        $medicine_cost = 250.00; // Standard base medicine cost
        $total_amount = $room_total_charge + $medicine_cost;
        
        $today_str = date('Y-m-d');
        
        // 1. Update Admission status
        mysqli_query($conn, "UPDATE admissions SET discharge_date = '$today_str', status = 'Discharged' WHERE id = $admission_id");
        
        // 2. Free up the room
        mysqli_query($conn, "UPDATE rooms SET status = 'Available' WHERE id = $room_id");
        
        // 3. Create Bill record if not already created
        $check_bill = mysqli_query($conn, "SELECT id FROM bills WHERE admission_id = $admission_id");
        if (mysqli_num_rows($check_bill) == 0) {
            $sql_bill = "INSERT INTO bills (patient_id, admission_id, medicine_cost, room_charge, total_amount, payment_status) 
                         VALUES ($patient_id, $admission_id, $medicine_cost, $room_total_charge, $total_amount, 'Pending')";
            mysqli_query($conn, $sql_bill);
        }
        
        header("Location: ../bill/index.php");
        exit();
    }
}
header("Location: index.php");
exit();
?>
