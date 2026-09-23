<?php

include "../service/db.php";
session_start();

$notif_createjob = '';

if (isset($_POST['createJobs-btn'])){
    $employee_id = $_SESSION['user_id'];
    $title_jobs = mysqli_real_escape_string($db, $_POST['title_jobs']);
    $company = mysqli_real_escape_string($db, $_POST['company']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    
    $salary_input = $_POST['salary'];
    $salary_clean = str_replace('.', '', $salary_input);
    $salary = mysqli_real_escape_string($db, $salary_clean);

    $category = mysqli_real_escape_string($db, $_POST['category']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $working_hours = mysqli_real_escape_string($db, "$start_time - $end_time");

    if (empty($title_jobs) || empty($company) || empty($description) || empty($salary) || empty($category) || empty($location) || empty($start_time) || empty($end_time)){
        $notif_createjob = "Tolong Isi Semua Form";
    } elseif (strlen($description) < 6) {
        $notif_createjob = "Description Harus lebih dari 6";
    } else {
        $check_job = "SELECT * FROM gigs WHERE title = '$title_jobs' AND company_name = '$company'";
        $check_result = mysqli_query($db, $check_job);
    
        if (mysqli_num_rows($check_result) > 0) {
            $notif_createjob = "Job sudah pernah di post";
        } else {
            $query = "INSERT INTO gigs (employer_id, title, company_name, description, salary, category, location, working_hours) VALUES ('$employee_id', '$title_jobs', '$company', '$description', '$salary', '$category', '$location', '$working_hours')";
    
            if (mysqli_query($db, $query)) {
                echo "<script>alert('Job berhasil dibuat!'); window.location.href='../dashboard.php';</script>";
            } else {
                $notif_createjob = "Create Jobs Gagal: " . mysqli_error($db);
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

    <nav style="background:white; padding: 20px 100px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <a href="../dashboard.php" style="text-decoration:none; color:#333; font-weight:bold; font-family: 'Inter', sans-serif;">&larr; Back to Dashboard</a>
    </nav>

    <div id="container">
        <div class="img">
        </div>
        <div class="form_container">
            <form action="create_job.php" method='POST'>
                <h2>Create Jobs</h2>
                <p class="auth-subtitle">Join us to find gigs or hire talent</p>
                
                <div class="input-group">
                    <label for="username">Title Jobs</label>
                    <input type="text" name="title_jobs" id="title" required>
                </div>
                
                <div class="input-group">
                    <label for="company">Company Name</label>
                    <input type="text" name='company' id="company" required>
                </div>
                
                <div class="input-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" required></textarea>
                </div>
                
                <div class="input-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="Technology">Technology & Programming</option>
                        <option value="Design">Design & Creative</option>
                        <option value="Writing">Writing & Translation</option>
                        <option value="Marketing">Digital Marketing</option>
                        <option value="Finance">Finance & Business</option>
                        <option value="Education">Education & Training</option>
                        <option value="Admin">Admin & Support</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="salary">Salary / Fee</label>
                    <div class="salary-wrapper">
                        <span>Rp</span>
                        <input type="text" name='salary' id="salary" onkeyup="formatRupiah(this)" placeholder="0" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Working Hours</label>
                    <div class="time-group">
                        <div>
                            <small>Start</small>
                            <select name="start_time" required>
                                <?php 
                                    for($i=0; $i<24; $i++){
                                        $time = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
                                        echo "<option value='$time'>$time</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <small>End</small>
                            <select name="end_time" required>
                                <?php 
                                    for($i=0; $i<24; $i++){
                                        $time = str_pad($i, 2, "0", STR_PAD_LEFT) . ":00";
                                        echo "<option value='$time'>$time</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label for="location">Location</label>
                    <input type="text" name='location' id="location" required>
                </div>
                

                <p style="color: red; font-size: 14px; text-align: center;"><?php echo $notif_createjob ?></p>
        
                <button type="submit" name="createJobs-btn" id='button_register'>Post Job</button>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            
            if (value) {
                value = parseInt(value, 10).toLocaleString('id-ID');
            }
            
            input.value = value;
        }
    </script>
</body>
</html>