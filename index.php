<?php
session_start();
include 'service/db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GetToWork.com</title>
    <link rel="stylesheet" href="asset/css/style.css">

</head>
<body>
    <?php include 'layout/header.php'?>
    
    <main>
        <div class='content1'>
            <div class='text'>
                <h2>Find Campus Gigs that <span>Fit Your <br>Schedule</span></h2>
                <p>Connect with local businesses and campus opportunities. Flexible work for students, by students.</p>
            </div>
            
            <div class='stats' style="margin-top: 40px;">
                <div class='stat'>
                    <h3>500+</h3>
                    <p>Gigs Posted</p>
                </div>
                <div class='stat'>
                    <h3>200+</h3>
                    <p>Active Students</p>
                </div>
                <div class='stat'>
                    <h3>100+</h3>
                    <p>Businesses</p>
                </div>
            </div>
        </div>

        <div class='content2'>
            <div class='text2'>
                <h2>Featured Gigs</h2>
                <p>Discover the latest opportunities. Login to see full details.</p>
            </div>
            
            <div class='gig-cards'>
                <?php
                $query = "SELECT * FROM gigs ORDER BY created_at DESC LIMIT 3";
                $result = mysqli_query($db, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class='gig-card'>
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="company"><?php echo htmlspecialchars($row['company_name']); ?></p>
                        
                        <span class="salary">Rp <?php echo number_format($row['salary'], 0, ',', '.'); ?></span>
                        
                        <div class="info-row">
                            <span>🕒</span> <strong><?php echo htmlspecialchars($row['working_hours']); ?></strong>
                        </div>
                        
                        <div class="info-row">
                            <span>📍</span> <span><?php echo htmlspecialchars($row['location']); ?></span>
                        </div>

                        <p class="date">Posted: <?php echo date('d M Y', strtotime($row['created_at'])); ?></p>
                        
                        <a href="login.php" class="view-detail">View Detail</a>
                    </div>

                <?php
                    }
                } else {
                    echo "<p style='text-align:center; color:#777; width:100%;'>Belum ada lowongan saat ini.</p>";
                }
                ?>
            </div>
        </div>

        <div class='content3'>
            <div class='text3'>
                <h2>How it works</h2>
                <p>Get started in three simple steps</p>
            </div>
            <div class='steps'>
                <div class='step'>
                    <img src="asset/Profile.svg" alt="How it works">
                    <h3>1. Sign Up</h3>
                    <p>Create your profile and set your preferences.</p>
                </div>
                <div class='step'>
                    <img src="asset/Discovery.svg" alt="How it works">
                    <h3>2. Browse Gigs</h3>
                    <p>Explore available opportunities that match your skills.</p>
                </div>
                <div class='step'>
                    <img src="asset/Document.svg" alt="How it works">
                    <h3>3. Apply & Earn</h3>
                    <p>Apply for gigs, complete tasks, and get paid.</p>
                </div>
            </div>
        </div>

        <div class='content4'>
            <div class='text4'>
                <h2>Join GetToWork Today!</h2>
                <p>Sign up now and start finding gigs that fit your schedule.</p>
            </div>
            <div class='button-group'>
                
                <a href="login.php" style="text-decoration:none;">
                    <button type='button' id="register-student">Register As Student</button>
                </a>
                
                <a href="login.php" style="text-decoration:none;">
                    <button type='button' id='register-business'>Post a Job</button>
                </a>
                
            </div>
        </div>
    </main>

    <?php include 'layout/footer.php'?>
</body>
</html>