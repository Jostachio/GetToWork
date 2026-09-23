<?php
session_start();
error_reporting(0);

include '../service/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$name = $_SESSION['name'];

if (!isset($_GET['id'])) {
    header("Location: ../dashboard.php");
    exit;
}

$id = mysqli_real_escape_string($db, $_GET['id']);

$query = "SELECT * FROM gigs WHERE id = '$id' AND employer_id = '$user_id'";
$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Data tidak ditemukan atau Anda tidak punya akses!'); window.location='../dashboard.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $salary = mysqli_real_escape_string($db, $_POST['salary']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    $hours = mysqli_real_escape_string($db, $_POST['hours']); 

    $update_query = "UPDATE gigs SET 
                     title = '$title', 
                     salary = '$salary', 
                     location = '$location', 
                     working_hours = '$hours' 
                     WHERE id = '$id'";

    if (mysqli_query($db, $update_query)) {
        echo "<script>alert('Berhasil mengupdate lowongan!'); window.location='../dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal update: " . mysqli_error($db) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jobs</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

    <nav style="background:white; padding: 20px 100px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <a href="../dashboard.php" style="text-decoration:none; color:#333; font-weight:bold; font-family: 'Inter', sans-serif;">&larr; Back to Dashboard</a>
    </nav>

    <div class="form-container">
        <h2 style="margin-bottom: 30px;">Edit Jobs</h2>
        
        <form action="" method="POST">
            
            <div class="input-group">
                <label>Job Title</label>
                <input type="text" name="title" value="<?php echo $data['title']; ?>" required>
            </div>

            <div class="input-group">
                <label>Fee (Rp)</label>
                <div class="salary-wrapper">
                    <span>Rp</span>
                    <input type="number" name="salary" value="<?php echo $data['salary']; ?>" required>
                </div>
            </div>

            <div class="input-group">
                <label>Location</label>
                <input type="text" name="location" value="<?php echo $data['location']; ?>" required>
            </div>

            <div class="input-group">
                <label>working_hours</label>
                <input type="text" name="hours" value="<?php echo $data['working_hours']; ?>" required>
            </div>

            <button type="submit" name="update" class="btn-submit">Save</button>
            
            <a href="../dashboard.php" class="btn-cancel">Cancel</a>

        </form>
    </div>

</body>
</html>