<?php
// process_job.php

// Database credentials setup
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "form_app";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Connection error check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sari fields ko POST request se fetch karna (Nayi 'category' field ke sath)
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_type = $_POST['job_type'];
    $experience = $_POST['experience'];
    $career_level = $_POST['career_level'];
    $salary = $_POST['salary'];
    $recruiter = $_POST['recruiter'];
    $email = $_POST['email'];
    $category = $_POST['category']; // Nayi field yahan add hui
    $state = $_POST['state'];
    $city = $_POST['city'];

    // Prepared Statement (Total 11 '?' lagenge)
    $stmt = $conn->prepare("INSERT INTO job_postings (job_title, job_description, job_type, experience, career_level, salary, recruiter, email, category, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Total 11 strings hain, isliye 11 baar "s"
    $stmt->bind_param("sssssssssss", $job_title, $job_description, $job_type, $experience, $career_level, $salary, $recruiter, $email, $category, $state, $city);

    // Query Execute karna
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>