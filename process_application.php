<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $cover_letter = $_POST['cover_letter'];
    $resume_path = "";

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . time() . "_" . basename($_FILES["resume"]["name"]);

        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $resume_path = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO applications (job_id, name, email, phone_number, resume, cover_letter) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("isssss", $job_id, $name, $email, $phone_number, $resume_path, $cover_letter);

    if ($stmt->execute()) {
        echo "<script>alert('Your application has been submitted successfully!'); window.location.href='view_jobs.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
