<?php

include "service/db.php";
session_start();
$notif_login = "";


if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['login-btn'])){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE  email = '$email' AND password = '$password' ";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $data["id"];
        $_SESSION['name'] = $data["username"];
        $_SESSION['role'] = $data["role"];

        header('Location: dashboard.php');
        exit;
    } else {
        $notif_login = "Email Belum terdaftar";
    }
}

?>


<?php include 'layout/header.php'?>



<div id="container">
    <div class="img">
    </div>
    <div class="form_container">
        <form action="login.php" method='POST'>
            <h2>Login</h2>
            <p class="auth-subtitle">Welcome back, find a new jobs</p>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name='email' id="email">
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password">
            </div>
    
            <p class="error-message">
                <?= $notif_login ?>
            </p>
    
            <button type="submit" name="login-btn" id='button_register'>login</button>
            <p class="auth-footer">
                Don't Have Account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>