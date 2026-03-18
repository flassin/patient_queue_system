<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM patients WHERE id='$id'");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Queue Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body onload="window.print()" class="p-4">

<div class="card p-4 text-center" style="max-width:400px; margin:auto;">
    <h4>KUTRRH Queue Slip</h4>
    <hr>
    <p><strong>Patient:</strong> <?php echo $row['patient_name']; ?></p>
    <p><strong>Queue Number:</strong> <?php echo $row['queue_number']; ?></p>
    <p><strong>OPD:</strong> <?php echo $row['opd']; ?></p>
    <p><strong>Date:</strong> <?php echo $row['registration_date']; ?></p>
    <hr>
    <h5>Please wait to be called</h5>
</div>

</body>
</html>