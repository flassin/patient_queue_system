<!DOCTYPE html>
<html>
<head>

<title>Hospital Queue Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
font-family:'Poppins',sans-serif;
background: linear-gradient(135deg,#e3f2fd,#ffffff);
}

.navbar{
background:#0d6efd;
}

.card{
border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

.btn{
border-radius:10px;
}

</style>

</head>
<body>
    <nav class="navbar navbar-dark">
<div class="container">

<span class="navbar-brand">
🏥 Hospital Patient Queue System
</span>


</div>
</nav>
<div class="container mt-4">
    <?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'staff'){
    header("Location: ../index.php");
    exit();
}

$success = "";
$name = "";
$gender = "";
$opd = "";
$date = date("Y-m-d");
$queue_number = "";

if(isset($_POST['register'])){

    $name = $_POST['patient_name'];
    $gender = $_POST['gender'];
    $opd = $_POST['opd'];
    $staff_id = $_SESSION['staff_id'];


    // Insert patients
    
if(isset($_POST['register'])){

    $patient_name = trim($_POST['patient_name']);
    $gender = $_POST['gender'];
    $opd = $_POST['opd'];
    $staff_id = $_SESSION['staff_id'];
    $registration_date = date("Y-m-d");
    $status = "waiting";
$today_count_result = $conn->query("SELECT COUNT(*) AS total FROM patients WHERE registration_date = '$registration_date'");
$today_count_row = $today_count_result->fetch_assoc();
$next_number = $today_count_row['total'] + 1;
$queue_number = "A" . str_pad($next_number, 3, "0", STR_PAD_LEFT);

    $sql = "INSERT INTO patients (patient_name, gender, opd, registration_date, status, staff_id, queue_number)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssis", $patient_name, $gender, $opd, $registration_date, $status, $staff_id, $queue_number);
$stmt->execute();
$new_id = $conn->insert_id;
header("Location: slip.php?id=" . $new_id);
exit();
}

    $stmt->execute();

    $patient_id = $stmt->insert_id;

    // Get last queue number for today
    $result = $conn->query("SELECT MAX(queue_number) as last_number FROM queue WHERE queue_date='$date'");
    $row = $result->fetch_assoc();

    $queue_number = $row['last_number'] + 1;
    if(!$queue_number){
        $queue_number = 1;
    }

    // Insert queue
    $stmt2 = $conn->prepare("INSERT INTO queue (patient_id, queue_number, queue_date) VALUES (?, ?, ?)");
    $stmt2->bind_param("iis", $patient_id, $queue_number, $date);
    $stmt2->execute();

    $success = "Patient Registered Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard - KUTRRH</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="bg-light">

<div class="container mt-4">
<div class="row">

<!-- TOTAL PATIENTS -->
<div class="col-md-4">
<div class="card p-3">

<h5>Total Patients</h5>

<?php
$result = $conn->query("SELECT COUNT(*) as total FROM patients");
$row = $result->fetch_assoc();
?>

<h2><?php echo $row['total']; ?></h2>

</div>
</div>


<!-- WAITING -->
<div class="col-md-4">
<div class="card p-3">

<h5>Waiting</h5>

<?php
$result = $conn->query("SELECT COUNT(*) as total FROM patients WHERE status='waiting'");
$row = $result->fetch_assoc();
?>

<h2><?php echo $row['total']; ?></h2>

</div>
</div>


<!-- SERVED -->
<div class="col-md-4">
<div class="card p-3">

<h5>Served</h5>

<?php
$result = $conn->query("SELECT COUNT(*) as total FROM patients WHERE status='served'");
$row = $result->fetch_assoc();
?>

<h2><?php echo $row['total']; ?></h2>

</div>
</div>

</div>
<div class="mt-4">


</div>
<div class="card mt-4 p-3">

</table>

<div class="container mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Staff Dashboard</h3>
    <div>
        <a href="dashboard.php" class="btn btn-primary">Register Patient</a>
        <a href="reports.php" class="btn btn-success">View Reports</a>
    </div>
</div>

<?php if(isset($_GET['success'])){ ?>
    <div class="alert alert-success">
        Patient registered successfully.
    </div>
<?php } ?>

    <!-- Registration Card -->
    <div class="card p-4 mb-4">
    <h4 class="mb-3">Register New Patient</h4>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Patient Name</label>
            <input type="text" name="patient_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">OPD</label>
            <select name="opd" class="form-control" required>
                <option value="">Select OPD</option>
                <option value="General OPD">General OPD</option>
                <option value="Dental">Dental</option>
                <option value="Maternity">Maternity</option>
                <option value="Eye Clinic">Eye Clinic</option>
            </select>
        </div>

        <button type="submit" name="register" class="btn btn-primary">
            Register Patient
        </button>
    </form>
</div>
<div class="card p-4">
    <h4 class="mb-3">Today’s Registered Patients</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>OPD</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $staff_id = $_SESSION['staff_id'];
        $today = date("Y-m-d");

        $result = $conn->query("SELECT * FROM patients 
                                WHERE staff_id='$staff_id' 
                                AND registration_date='$today'
                                ORDER BY id DESC");

        while($row = $result->fetch_assoc()){
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['patient_name']."</td>
                    <td>".$row['gender']."</td>
                    <td>".$row['opd']."</td>
                    <td>".$row['status']."</td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
    <!-- Printable Slip -->
    <?php if($success != ""){ ?>

        <div class="card p-4 text-center mt-4" id="printArea">

<h5>KUTRRH</h5>
<p>Patient Queue Ticket</p>

<hr>

<p><strong>Patient:</strong> <?php echo $name; ?></p>
<p><strong>OPD:</strong> <?php echo $opd; ?></p>
<p><strong>Date:</strong> <?php echo $date; ?></p>

<hr>

<div class="queue-number">
<?php echo $queue_number; ?>
</div>

<p>Please wait until your number is called</p>

</div>

        <div class="text-center mt-3">
            <button onclick="printSlip()" class="btn btn-success">
                Print Slip
            </button>
        </div>

    <?php } ?>

</div>

<script>
function printSlip() {
    var content = document.getElementById("printArea").innerHTML;
    var original = document.body.innerHTML;

    document.body.innerHTML = content;
    window.print();
    document.body.innerHTML = original;
}
</script>

</body>
</html>
</div>

</body>
</html>