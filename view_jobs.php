<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Jobs</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 20px; }
        .job-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .job-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .job-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        .job-details { padding: 15px; }
        .job-details h3 { margin: 0 0 10px 0; color: #023683; font-size: 16px; }
        .job-details p { margin: 5px 0; font-size: 14px; color: #555; }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 30px;">Available Jobs</h2>
    <div style="margin-bottom: 30px; display: flex; justify-content: center;">
        <form method="GET" action="view_jobs.php" style="display: flex; gap: 10px;">
            <input type="text" name="search_name" placeholder="Search by Job name" style="padding: 10px; border: 1px solid #ccc;">
            
            <select name="search_category" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by category</option>
                <option value="Resorts">Resorts</option>
                <option value="Fast Casual">Fast Casual</option>
                <option value="Fine Dining">Fine Dining</option>
                <option value="Italian">Italian</option>
            </select>

            <select name="search_state" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by state</option>
                <option value="Gujarat">Gujarat</option>
                <option value="Rajasthan">Rajasthan</option>
                <option value="Maharashtra">Maharashtra</option>
            </select>

            <select name="search_city" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by city</option>
                <option value="Rajkot">Rajkot</option>
                <option value="Ahmedabad">Ahmedabad</option>
                <option value="Mumbai">Mumbai</option>
            </select>

            <button type="submit" style="padding: 10px 20px; background-color: #b0c4c4; border: none; font-weight: bold; cursor: pointer;">SEARCH</button>
            </form>
    </div>
    <div class="job-grid">

        <?php
        $conn = new mysqli("localhost", "root", "", "form_app");
        if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

        $sql = "SELECT * FROM job_postings ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $image_src = !empty($row['image_path']) ? $row['image_path'] : 'https://via.placeholder.com/250x150?text=No+Image';

                echo '<div class="job-card">';
                echo '<img src="' . $image_src . '" alt="Job Cover" class="job-image">';
                echo '<div class="job-details">';
                
                echo '<h3>' . htmlspecialchars($row["job_title"]) . '</h3>';
                echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["city"]) . '</p>';
                echo '<p><strong>Salary:</strong> ' . htmlspecialchars($row["salary"]) . '</p>';
                echo '<p><strong>Recruiter:</strong> ' . htmlspecialchars($row["recruiter"]) . '</p>';
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No jobs found.</p>";
        }
        $conn->close();
        ?>

    </div>
</body>
</html>