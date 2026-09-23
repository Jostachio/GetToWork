<?php
session_start();
include '../service/db.php';

// Cek sesi login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Base Query
$sql = "SELECT * FROM gigs WHERE 1=1"; 

// Filter: Search Keyword
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($db, $_GET['search']);
    $sql .= " AND (title LIKE '%$search%' OR company_name LIKE '%$search%')";
}

// Filter: Location
if (isset($_GET['location']) && !empty($_GET['location'])) {
    $loc = mysqli_real_escape_string($db, $_GET['location']);
    $sql .= " AND location LIKE '%$loc%'";
}

// Filter: Working Hours
if (isset($_GET['hours']) && !empty($_GET['hours'])) {
    $hours = mysqli_real_escape_string($db, $_GET['hours']);
    $sql .= " AND working_hours LIKE '%$hours%'";
}

// Sorting terbaru
$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Jobs - GetToWork</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body>

    <nav style="background:white; padding: 20px 5%; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display:flex; justify-content:space-between; align-items:center;">
        <a href="../dashboard.php" style="font-weight:bold; color:#333;">&larr; Back to Dashboard</a>
        <span style="color: #777; font-size: 14px;">Hello, <b><?php echo htmlspecialchars($_SESSION['name']); ?></b></span>
    </nav>

    <div class="filter-section">
        <form action="" method="GET" class="filter-form">
            
            <div class="input-group">
                <label>Find jobs</label>
                <input type="text" name="search" placeholder="Positions / Company..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            
            <div class="input-group">
                <label>Location</label>
                <select name="location">
                    <option value="">All Locations</option>
                    <?php 
                        $locations = ["Jakarta", "Depok", "Tangerang", "Bekasi", "Bogor", "Remote"];
                        foreach ($locations as $city) {
                            $selected = (isset($_GET['location']) && $_GET['location'] == $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <label>Working Hours</label>
                <input type="text" name="hours" placeholder="e.g. 14:00, Shift, Pagi..." 
                       value="<?php echo isset($_GET['hours']) ? htmlspecialchars($_GET['hours']) : ''; ?>">
            </div>

            <button type="submit" class="btn-search">Filter</button>
            
            <?php if(isset($_GET['search']) || isset($_GET['location']) || isset($_GET['hours'])): ?>
                <a href="list.php" class="btn-reset">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="main-content" style="padding: 0 20px 50px 20px;">
        <h2 style="text-align: center; color: #333; margin-bottom: 40px; font-weight: 700;">Available Gigs</h2>

        <div class="gig-cards">
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    
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
                        
                        <a href="apply_jobs.php?gigs_id=<?php echo $row['id']; ?>" class="view-detail">Apply Now</a>
                    </div>

                <?php endwhile; ?>
            <?php else : ?>
                <div style="text-align:center; padding: 50px; width: 100%; grid-column: 1 / -1;">
                    <h3 style="color: #666; margin-bottom:10px;">No jobs found.</h3>
                    <p style="color: #999;">Try adjusting your search criteria or clear filters.</p>
                    <a href="list.php" style="color:#007BFF; font-weight:bold; margin-top:10px; display:inline-block;">View All Jobs</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>