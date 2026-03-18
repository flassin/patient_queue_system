<?php
include("config/db.php");
$result = $conn->query("SELECT queue_number, patient_name FROM patients WHERE status='waiting' ORDER BY id ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Live Queue Display</title>
    <meta http-equiv="refresh" content="10">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#0b1f3a;
            color:white;
            font-family:Arial, sans-serif;
        }
        h1{
            font-size:50px;
            font-weight:bold;
        }
        table{
            background:white;
            color:black;
            border-radius:10px;
            overflow:hidden;
        }
        th, td{
            text-align:center;
            padding:20px !important;
            font-size:28px;
        }
        th{
            background:#0d6efd;
            color:white;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Hospital Queue Display</h1>

    <table class="table table-bordered">
        <tr>
            <th>Queue Number</th>
            <th>Patient Name</th>
        </tr>

        <?php
        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>".$row['queue_number']."</td>
                    <td>".$row['patient_name']."</td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>