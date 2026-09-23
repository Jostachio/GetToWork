<?php
session_start();

error_reporting(0);

include '../service/db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'business') {
    header("Location: ../auth/login.php");
    exit;
}

$role = $_SESSION['role'];
$name = $_SESSION['name']; 
$user_id = $_SESSION['user_id'];

if (!isset($_GET['job_id'])) {
    header("Location: ../dashboard.php");
    exit;
}
$job_id = $_GET['job_id'];

if (isset($_GET['aksi']) && isset($_GET['app_id'])) {
    
    $app_id_target = $_GET['app_id'];
    $aksi = $_GET['aksi'];
    
    $status_baru = '';
    if ($aksi == 'terima') {
        $status_baru = 'accepted';
    } elseif ($aksi == 'tolak') {
        $status_baru = 'rejected';
    }

    if ($status_baru != '') {
        $update_q = "UPDATE applications SET status = '$status_baru' WHERE id = '$app_id_target'";
        mysqli_query($db, $update_q);
        
        header("Location: view_applicants.php?job_id=$job_id");
        exit;
    }
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>view applicant</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>

<body>

<div class="container">
    
    <div class="card-header">
        <div>
            <h2 style="margin: 0; color: #333;">Applicant List</h2>
            <?php
            $q_job = mysqli_query($db, "SELECT title FROM gigs WHERE id='$job_id'");
            $d_job = mysqli_fetch_assoc($q_job);
            ?>
            <p style="margin: 5px 0 0 0; color: #666;">Position : <strong><?php echo $d_job['title'] ?? 'Job Title'; ?></strong></p>
        </div>
        <a href="../dashboard.php" class="btn-back">Back to dashboard</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Student name</th>
                    <th>Email</th>
                    <th>Message/Reason</th> 
                    <th>Apply Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT applications.*, users.username, users.email 
                          FROM applications 
                          JOIN users ON applications.student_id = users.id 
                          WHERE applications.job_id = '$job_id'";
                
                $data = mysqli_query($db, $query);
                $no = 1;

                if(mysqli_num_rows($data) > 0) {
                    while($d = mysqli_fetch_array($data)){
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $d['username']; ?></strong></td>
                        <td><?php echo $d['email']; ?></td>
                        
                        <td style="max-width: 250px; word-wrap: break-word; text-align: left; font-size: 13px;">
                            <?php echo htmlspecialchars($d['message']); ?>
                        </td>

                        <td><?php echo date('d M Y', strtotime($d['applied_at'])); ?></td>
                        
                        <td>
                            <span class="status-<?php echo strtolower($d['status']); ?>">
                                <?php echo ucfirst($d['status']); ?>
                            </span>
                        </td>

                        <td>
                            <?php if($d['status'] == 'pending') { ?>
                                <a href="view_applicants.php?job_id=<?php echo $job_id; ?>&aksi=terima&app_id=<?php echo $d['id']; ?>" 
                                   class="btn-aksi bg-terima" onclick="return confirm('Yakin Terima?')">Accept</a>
                                
                                <a href="view_applicants.php?job_id=<?php echo $job_id; ?>&aksi=tolak&app_id=<?php echo $d['id']; ?>" 
                                   class="btn-aksi bg-tolak" onclick="return confirm('Yakin Tolak?')">Reject</a>
                            <?php } else { ?>
                                <span style="color: #aaa; font-size: 13px;">Done</span>
                            <?php } ?>
                        </td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='7' align='center' style='padding:40px; color:#777;'>Belum ada pelamar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

</body>