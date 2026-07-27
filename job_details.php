<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #fff;
            color: #333;
            margin: 0;
            padding: 40px;
        }

        .page-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            gap: 40px;
        }

        .main-content {
            flex: 3;
        }

        .sidebar {
            flex: 1;
            border-left: 1px solid #eee;
            padding-left: 20px;
        }

        .job-title {
            color: #5c698a;
            font-size: 32px;
            margin-top: 0;
            margin-bottom: 20px;
        }

        .job-meta {
            margin-bottom: 30px;
            line-height: 1.8;
            font-size: 15px;
        }

        .job-desc {
            line-height: 1.6;
            color: #444;
        }

        .search-box {
            display: flex;
            margin-bottom: 30px;
        }

        .search-box input {
            padding: 8px;
            border: 1px solid #ccc;
            flex: 1;
        }

        .search-box button {
            padding: 8px 15px;
            background: #ddd;
            border: 1px solid #ccc;
            cursor: pointer;
            color: #555;
        }

        .sidebar-title {
            color: #4542e4;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .sidebar-list {
            list-style: none;
            padding: 0;
            line-height: 2.2;
        }

        .sidebar-list a {
            text-decoration: none;
            color: #555;
        }

        .apply-btn {
            display: inline-block;
            background-color: #04075f;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 30px;
            border-radius: 4px;
            font-size: 16px;
        }

        .apply-btn:hover { 
            background-color: #0c2393; 
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
        ?>
        <div class="page-container">
            <!-- Left Column: Job Details -->
            <div class="main-content">
                <h1 class="job-title"><?php echo htmlspecialchars($row['job_title']); ?></h1>
                
                <div class="job-meta">
                    <strong><?php echo htmlspecialchars($row['city']); ?></strong><br>
                    Job Type: <?php echo htmlspecialchars($row['job_type']); ?><br>
                    Years of experience: <?php echo htmlspecialchars($row['experience']); ?><br>
                    Career level: <?php echo htmlspecialchars($row['career_level']); ?><br>
                    Salary: <?php echo htmlspecialchars($row['salary']); ?>
                </div>

                <div class="job-meta">
                </div>

                
                <div class="job-desc">
                    </div>
                    
                    <div class="job-desc">
                        <?php echo $row['job_description'];?>
                    </div>
                
                <a href="#" class="apply-btn">Apply Now</a>
            </div>

            <div class="sidebar">
                <div class="search-box">
                    <input type="text" placeholder="">
                    <button>Search</button>
                </div>
                
                <h3 class="sidebar-title">Categories</h3>
                <ul class="sidebar-list">
                    <li><a href="#">Fun Facts</a></li>
                    <li><a href="#">Hospitality Industry</a></li>
                    <li><a href="#">Job Interview Tips</a></li>
                    <li><a href="#">Manager Tips</a></li>
                    <li><a href="#">Recruiters</a></li>
                    <li><a href="#">Restaurants</a></li>
                    <li><a href="#">Service</a></li>
                    <li><a href="#">Uncategorized</a></li>
                </ul>
            </div>
        </div>
        <?php
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