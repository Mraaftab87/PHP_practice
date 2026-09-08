<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $conn = new mysqli("localhost", "root", "", "form_app");
    
    $sql = "DELETE FROM job_postings WHERE id = $id";
    $conn->query($sql);
    
    $conn->close();
}

header("Location: manage_jobs.php");
exit;
?>