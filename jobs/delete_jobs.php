<?php

include '../service/db.php';
session_start();


if (isset($_GET['id'])){
    $job_id = $_GET['id'];
    

    $sql = "DELETE FROM gigs WHERE id = '$job_id'";

    if (mysqli_query($db, $sql)){
        if (mysqli_affected_rows($db) > 0){
            echo "<script>alert('Lowongan berhasil dihapus!'); window.location.href='../dashboard.php';</script>";
        } else {
            echo "<script>alert('Lowongan Gagal dihapus!'); window.location.href='../dashboard.php';</script>";
        }
    } else {
        echo "EROR" . mysqli_error();
    }
} else {

    header("Location: ../index.php");
}

?>