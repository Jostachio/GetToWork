<?php 

include './service/db.php';


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <h3>Hi, <b><?php echo $name ?></b> As <?php echo ucfirst ($role); ?></h3>
        </div>

        <div class="auth-links">
            <a href="logout.php" name="logout" id="register">Logout</a>
        </div>
    </header>
</body>
</html>