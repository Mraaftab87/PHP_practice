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
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_type = $_POST['job_type'];
    $experience = $_POST['experience'];
    $career_level = $_POST['career_level'];
    $salary = $_POST['salary'];
    $recruiter = $_POST['recruiter'];
    $email = $_POST['email'];
    $category = $_POST['category'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $image_path = "";
    
    if (isset($_FILES['job_image']) && $_FILES['job_image']['error'] == 0) {
        $target_dir = "uploads/";
        
        $target_file = $target_dir . basename($_FILES["job_image"]["name"]); 
        
        if (move_uploaded_file($_FILES["job_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO job_postings (job_title, job_description, job_type, experience, career_level, salary, recruiter, email, category, state, city, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssssss", $job_title, $job_description, $job_type, $experience, $career_level, $salary, $recruiter, $email, $category, $state, $city, $image_path);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>