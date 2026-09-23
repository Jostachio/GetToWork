<?php

session_start();
include '../service/db.php';
$notif_applyjob = '';
$gigs_id = '';
$user_id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['gigs_id'])){
    $gigs_id = $_GET['gigs_id'];
    $user_id = $_SESSION['user_id'];

    $query_job = "SELECT * FROM gigs WHERE id = '$gigs_id'";
    $query_user = "SELECT * FROM users WHERE id = '$user_id'";
    $result_job = mysqli_query($db, $query_job);
    $job = mysqli_fetch_assoc($result_job);
    $result_user = mysqli_query($db, $query_user);
    $user = mysqli_fetch_assoc($result_user);

    if (!$job){
        echo "<script>alert('Job not found!'); window.location.href='../dashboard.php';</script>";
        exit;
    }
} elseif (isset($_POST['gigs_id'])) {
    $gigs_id = $_POST['gigs_id'];
    $user_id = $_SESSION['user_id'];
    
    $query_job = "SELECT * FROM gigs WHERE id = '$gigs_id'";
    $query_user = "SELECT * FROM users WHERE id = '$user_id'";
    $result_job = mysqli_query($db, $query_job);
    $job = mysqli_fetch_assoc($result_job);
    $result_user = mysqli_query($db, $query_user);
    $user = mysqli_fetch_assoc($result_user);
}


if (isset($_POST['applyjobs-btn'])){
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    $gigs_id_post = $_POST['gigs_id']; 

    $document = '';
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $document = addslashes(file_get_contents($_FILES['document']['tmp_name']));
    }

    if (empty($description) || empty($location) || empty($document)){
        $notif_applyjob = "Please fill in all fields and upload CV.";
    } else {
        $check_application = "SELECT * FROM applications WHERE job_id = '$gigs_id_post' AND student_id = '$user_id'";
        $check_result = mysqli_query($db, $check_application);
    
        if (mysqli_num_rows($check_result) > 0) {
            $notif_applyjob = "You have already applied for this job.";
        } else {
            $create_jobs = "INSERT INTO applications (job_id, student_id, message, location, document, status) VALUES ('$gigs_id_post', '$user_id', '$description', '$location', '$document', 'pending')";
            
            if (mysqli_query($db, $create_jobs)) {
                echo "<script>alert('Application submitted successfully!'); window.location.href='../dashboard.php';</script>";
            } else {
                $notif_applyjob = "Failed to submit application: " . mysqli_error($db);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body>
    <div id="container">
        <div class="img"></div>
        <div class="form_container">
            <form action="apply_jobs.php" method='POST' enctype="multipart/form-data">
                <h2>Apply a Job</h2>
                <p class="auth-subtitle">Fill in the form below to apply</p>
                
                <div class="job-info-box">
                    <h3>Job Title: <span><?php echo $job['title']; ?></span></h3>
                    <h3>Company: <span><?php echo $job['company_name']; ?></span></h3>
                </div>

                <input type="hidden" name="gigs_id" value="<?php echo $gigs_id; ?>">

                <div class="input-group">
                    <label>Applicant Name</label>
                    <input type="text" value="<?php echo $user['username']; ?>" disabled style="background-color: #eee;">
                </div>

                <div class="input-group">
                    <label for="description">Tell Anything About You</label>
                    <textarea name="description" id="description" placeholder="Write a brief introduction or cover letter..." required></textarea>
                </div>

                <div class="input-group">
                    <label for="location">Current Location</label>
                    <input type="text" name='location' id="location" placeholder="e.g. Jakarta Selatan" required>
                </div>

                <div class="input-group">
                    <label for="document">Upload Your CV (PDF/Doc)</label>
                    <input type="file" name='document' id="document" required>
                </div>

                <p class="error-msg"><?php echo $notif_applyjob; ?></p>
        
                <button type="submit" name="applyjobs-btn" id='button_register'>Submit Application</button>
            </form>
        </div>
    </div>
</body>
