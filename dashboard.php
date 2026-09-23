<?php

session_start();
include 'service/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard.com</title>
    <link rel="stylesheet" href="asset/css/style.css">
</head>

<?php include 'layout/header_dash.php'; ?>


<body>
    <main>
        <?php if($role == 'student') : ?>
            <div class='content1'>
                <div class='text'>
                    <h2>Find Campus Gigs that <span>Fit Your <br>Schedule</span></h2>
                    <p>Connect with local businesses and campus opportunities. Flexible work for students, by students.</p>
                </div>
                <div class='search-bar'>
                    <a href="jobs/list.php">Apply a Job</a>
                </div>
            </div>
    
            <div class='content2'>
                <h2>Your History Application</h2>
                <?php
                $query = "SELECT applications.*, gigs.title, gigs.company_name 
                      FROM applications 
                      JOIN gigs ON applications.job_id = gigs.id 
                      WHERE applications.student_id = '$user_id' 
                      ORDER BY applied_at DESC";
                $result = mysqli_query($db, $query);
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Jobs</th>
                            <th>Company</th>
                            <th>Apply Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) :?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo date('d M Y', strtotime($row['applied_at'])); ?></td>
                                <td>
                                    <?php 
                                        $status = strtolower($row['status']); 
                                        $statusClass = '';
                                        
                                        if($status == 'pending') {
                                            $statusClass = 'status-pending';
                                        } elseif($status == 'accepted') {
                                            $statusClass = 'status-accepted';
                                        } elseif($status == 'rejected') {
                                            $statusClass = 'status-rejected';
                                        }
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else :?>
                            <tr><td colspan="4" style="text-align:center;">No job applications yet. Lets look for a job!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


        <?php elseif($role == 'business') : ?>
            <div class='content1'>
                <div class='text'>
                    <h2>Hire Energetic Talent, <br> <span>Fast & on Budget</span></h2>
                    <p>Connect with motivated students ready to work part-time. The perfect solution <br>for flexible schedules and peak-hour support.</p>
                </div>
                <div class='search-bar'>
                    <a href="jobs/create_job.php">Create Jobs</a>
                </div>
            </div>

            <div class='content2'>
                <h2>Your Company Active Job</h2>
                <?php
                $query = "SELECT gigs.*, 
                          (SELECT COUNT(*) FROM applications WHERE applications.job_id = gigs.id) as applicant_count
                          FROM gigs 
                          WHERE employer_id = '$user_id' 
                          ORDER BY created_at DESC";
                $result = mysqli_query($db, $query);
                ?>

                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Salary / Fee</th>
                            <th>Location</th>
                            <th>Action</th>
                            <th>Job Applicant</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) :?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td>Rp<?php echo number_format($row['salary']); ?></td>
                                <td><?php echo $row['location']; ?></td>
                                
                                <td class="action-links">
                                    <a href="jobs/edit_job.php?id=<?php echo $row['id'];?>">Edit</a> | 
                                    <a href="jobs/delete_jobs.php?id=<?php echo $row['id'];?>" onclick="return confirm('Yakin hapus?')">Delete</a>
                                </td>

                                <td>
                                    <a href="jobs/view_applicants.php?job_id=<?php echo $row['id']; ?>" class="btn-Job Applicant">
                                        see Job Applicant (<?php echo $row['applicant_count']; ?>)
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else :?>
                            <tr><td colspan="5" style="text-align:center;">No job yet. Lets create a job opening!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </main>
</body>

</html>