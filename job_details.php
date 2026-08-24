<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
            color: #728117;
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
            background-color: #e2cf6d;
            color: #6f631e;
            padding: 12px 25px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 30px;
            font-size: 16px;
        }

        .apply-btn:hover {
            background-color: #6f631e;
            color: #e2cf6d;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_GET['id'])) {
        $job_id = $_GET['id'];
        $conn = new mysqli("localhost", "root", "", "form_app");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM job_postings WHERE id = $job_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
    ?>
            <div class="page-container">
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
                        <?php echo $row['job_description']; ?>
                    </div>

                    <a href="#" class="apply-btn" id="showFormBtn">Apply Now</a>

                    <form id="applyForm" action="process_application.php" method="POST" enctype="multipart/form-data" style="display: none; margin-top: 30px; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                        <h3>Submit Your Application</h3>

                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Name:</label>
                            <input type="text" name="name" required style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Email:</label>
                            <input type="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Phone Number:</label>
                            <input type="tel" name="phone_number" required style="width: 100%; padding: 8px; border: 1px solid #ccc;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Upload Resume:</label>
                            <input type="file" name="resume" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="font-weight: bold; display: block; margin-bottom: 5px;">Cover Letter:</label>
                            <textarea name="cover_letter" rows="5" required style="width: 100%; padding: 8px; border: 1px solid #ccc; font-family: inherit;"></textarea>
                        </div>

                        <button type="submit" class="apply-btn" style="border: none; cursor: pointer; width: 100%;">Submit Application</button>
                    </form>
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

    <script>
        $(document).ready(function() {
            $('#showFormBtn').click(function(e) {
                e.preventDefault();
                $('#applyForm').slideToggle();
            });
        });
    </script>

</body>

</html>