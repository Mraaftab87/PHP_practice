<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <style>

        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .details-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .job-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .job-header {
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .job-header h1 {
            margin: 0;
            color: #023683;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #b0c4c4;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #023683;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "form_app");
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    $sql = "SELECT * FROM job_postings WHERE id = $job_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_src = !empty($row['image_path']) ? $row['image_path'] : 'https://via.placeholder.com/800x300?text=No+Image';

        echo '<div class="details-container">';
        echo '<img src="' . $image_src . '" alt="Job Image" class="job-image">';
        
        echo '<div class="job-header">';
        echo '<h1>' . htmlspecialchars($row['job_title']) . '</h1>';
        echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category']) . ' | <strong>Location:</strong> ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . '</p>';
        echo '</div>';

        echo '<div class="info-grid">';
        echo '<div class="info-box"><strong>Job Type:</strong><br>' . htmlspecialchars($row['job_type']) . '</div>';
        echo '<div class="info-box"><strong>Experience:</strong><br>' . htmlspecialchars($row['experience']) . '</div>';
        echo '<div class="info-box"><strong>Career Level:</strong><br>' . htmlspecialchars($row['career_level']) . '</div>';
        echo '<div class="info-box"><strong>Salary:</strong><br>' . htmlspecialchars($row['salary']) . '</div>';
        echo '<div class="info-box"><strong>Recruiter:</strong><br>' . htmlspecialchars($row['recruiter']) . '</div>';
        echo '<div class="info-box"><strong>Contact:</strong><br>' . htmlspecialchars($row['email']) . '</div>';
        echo '</div>';

        echo '<h3>Job Description</h3>';
        echo '<div style="margin-top: 15px; line-height: 1.6; background: #fff; padding: 20px; border-radius: 5px; border: 1px solid #ddd;">';
        echo $row['job_description'];
        echo '</div>';

        echo '<a href="view_jobs.php" class="back-btn">← Back to Jobs</a>';
        echo '</div>';
    } else {
        echo "<h2 style='text-align:center;'>Job not found!</h2>";
    }
    $conn->close();
} else {
    echo "<h2 style='text-align:center;'>No Job ID provided!</h2>";
}
?>

</body>
</html>