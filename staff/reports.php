<?php
session_start();
include("../config/db.php");

$staff_id = $_SESSION['staff_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="mb-4">My Patient Reports</h3>

    <a href="dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

    <?php
    $result = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE staff_id='$staff_id'");
    $row = $result->fetch_assoc();
    ?>

    <div class="card p-3 mb-4">
        <h5>Total Patients I Registered</h5>
        <h2><?php echo $row['total']; ?></h2>
    </div>

    <div class="card p-3">
        <h5>My Registered Patients</h5>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>OPD</th>
                <th>Date</th>
                <th>Status</th>
            </tr>

            <?php
            $patients = $conn->query("SELECT * FROM patients WHERE staff_id='$staff_id' ORDER BY id DESC");

            while($p = $patients->fetch_assoc()){
                echo "<tr>
                        <td>".$p['id']."</td>
                        <td>".$p['patient_name']."</td>
                        <td>".$p['gender']."</td>
                        <td>".$p['opd']."</td>
                        <td>".$p['registration_date']."</td>
                        <td>".$p['status']."</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</div>

</body>
</html>