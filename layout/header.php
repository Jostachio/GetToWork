<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetToWork</title>
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <?php if(isset($_SESSION['user_id'])): ?>
                <h3 style="margin:0;">Hi, <b><?php echo $_SESSION['name']; ?></b></h3>
            <?php else: ?>
                <img src="asset/logo.png" alt="GetToWork Logo">
            <?php endif; ?>
        </div>

        <div class="hamburger" id="hamburger-btn">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <div class="nav-container" id="nav-menu">
            <nav>
                <ul>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="jobs/list.php">Browse Gigs</a></li>
                        <?php if($_SESSION['role'] == 'business'): ?>
                            <li><a href="jobs/create_job.php">Post a Gig</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="register.php">Browse Gigs</a></li>
                        <li><a href="register.php">Post a Gig</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="auth-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="login.php" id="login">Login</a>
                    <a href="register.php" id="register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <script>
        const hamburger = document.getElementById('hamburger-btn');
        const navMenu = document.getElementById('nav-menu');

        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('toggle');
        });
    </script>
</body>
</html>