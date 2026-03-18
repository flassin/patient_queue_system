<?php
session_start();
include("config/db.php");

$error = "";

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){

        $user = $result->fetch_assoc();

        if($password == $user['password']){

$_SESSION['user'] = $user;

/* ADD THESE LINES */
$_SESSION['staff_id'] = $user['id'];
$_SESSION['staff_name'] = $user['username'];

if($user['role'] == 'admin'){
    header("Location: admin/dashboard.php");
}else{
    header("Location: staff/dashboard.php");
}

exit();
}

        }else{
            $error = "Invalid Password!";
        }

    }else{
        $error = "User not found!";
    }

?>

<!DOCTYPE html>
<html>
<head>

<title>KUTRRH Queue System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

<style>

body{
font-family:'Inter',sans-serif;
background:linear-gradient(120deg,#eef5ff,#f7fbff);
height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

.card{
border:none;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

</style>

</head>

<body>

<div class="card p-4" style="width:350px;">

<h4 class="text-center mb-4 text-primary">
KUTRRH Queue System
</h4>

<?php if($error!=""){ ?>

<div class="alert alert-danger">
<?php echo $error; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">
<input type="text" name="username" class="form-control" placeholder="Username" required>
</div>

<div class="mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
</div>

<button type="submit" name="login" class="btn btn-primary w-100">
Login
</button>

</form>

</div>

</body>
</html>