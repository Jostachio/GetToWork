<?php


include "service/db.php";
session_start();

$notif_register = '';



if (isset($_POST['register-btn'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($email) || empty($password) || empty($role)){
        $notif_register = "Tolong Isi Semua Form";
    } elseif (strlen($password) < 6) {
        $notif_register = "Password Harus lebih dari 6";
    } else {
        $check_email = "SELECT * FROM users WHERE email = '$email' ";
        $check_result = mysqli_query($db, $check_email);
    
        if (mysqli_num_rows($check_result) > 0) {
            $notif_register = "Email sudah terdaftar! Coba Login";
        } else {
            $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    
            if (mysqli_query($db, $query)) {
                header("Location: login.php");
            } else {
                $notif_register = "Resgiter Gagal" . mysqli_error($db);
            }
        }
    }
    
}


?>


<?php include 'layout/header.php'?>

<div id="container">
    <div class="img">
    </div>
    <div class="form_container">
        <form action="register.php" method='POST'>
            <h2>Register</h2>
            <p class="auth-subtitle">Join us to find gigs or hire talent</p>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name='email' id="email">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="input-group">
                <label for="role-type">Role</label>
                <div class="radio-btn">
                    <input for="radio1" type="radio" name="role" id="student" value="student">
                    <label for="role-type">Student</label>
                    <input for="radio2"type="radio" name="role" id="business" value="business">
                    <label for="role-type">Busines</label>
                </div>

            </div>

            <?= $notif_register ?>
    
            <button type="submit" name="register-btn" id='button_register'>Register Now</button>
            <p class="auth-footer">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
</div>
