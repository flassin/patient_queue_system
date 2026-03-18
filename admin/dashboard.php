<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin'){
    header("Location: ../index.php");
}

$date = date("Y-m-d");

$result = $conn->query("SELECT COUNT(*) as total FROM queue WHERE queue_date='$date'");
$row = $result->fetch_assoc();
?>

<h2>Admin Dashboard</h2>

<h3>Today's Total Patients: <?php echo $row['total']; ?></h3>

<a href="report.php">View Detailed Report</a>