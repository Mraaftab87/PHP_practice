<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Jobs</title>
    <style>
        body { 
            font-family: sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
        }

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
        .job-details { 
            padding: 15px; 
        }
        .job-details h3 { 
            margin: 0 0 10px 0; 
            color: #023683; 
            font-size: 16px; 
        }
        .job-details p { 
            margin: 5px 0; 
            font-size: 14px; 
            color: #555; 
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 30px;">Available Jobs</h2>
    <div style="margin-bottom: 30px; display: flex; justify-content: center;">
        <form method="GET" action="view_jobs.php" style="display: flex; gap: 10px;">
            <input type="text" name="search_name" placeholder="Search by Job name" style="padding: 10px; border: 1px solid #ccc;">
            
            <select name="search_category" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by category</option>
                <option value="Resorts" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Resorts') echo 'selected'; ?>>Resorts</option>
                <option value="All" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'All') echo 'selected'; ?>>All</option>
                <option value="Fast Casual" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Fast Casual') echo 'selected'; ?>>Fast Casual</option>
                <option value="Fine Dining" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Fine Dining') echo 'selected'; ?>>Fine Dining</option>
                <option value="Italian" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Italian') echo 'selected'; ?>>Italian</option>
                <option value="Mexican" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Mexican') echo 'selected'; ?>>Mexican</option>
                <option value="Seafood" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Seafood') echo 'selected'; ?>>Seafood</option>
                <option value="Sports Bar" <?php if(isset($_GET['search_category']) && $_GET['search_category'] == 'Sports Bar') echo 'selected'; ?>>Sports Bar</option>
            </select>

            <select name="search_state" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by state</option>
                <option value="Gujarat" <?php if(isset($_GET['search_state']) && $_GET['search_state'] == 'Gujarat') echo 'selected'; ?>>Gujarat</option>
                <option value="Rajasthan" <?php if(isset($_GET['search_state']) && $_GET['search_state'] == 'Rajasthan') echo 'selected'; ?>>Rajasthan</option>
                <option value="Maharashtra" <?php if(isset($_GET['search_state']) && $_GET['search_state'] == 'Maharashtra') echo 'selected'; ?>>Maharashtra</option>
            </select>

            <select name="search_city" style="padding: 10px; border: 1px solid #ccc;">
                <option value="">Search by city</option>
                <option value="Rajkot" <?php if(isset($_GET['search_city']) && $_GET['search_city'] == 'Rajkot') echo 'selected'; ?>>Rajkot</option>
                <option value="Ahmedabad" <?php if(isset($_GET['search_city']) && $_GET['search_city'] == 'Ahmedabad') echo 'selected'; ?>>Ahmedabad</option>
                <option value="Mumbai" <?php if(isset($_GET['search_city']) && $_GET['search_city'] == 'Mumbai') echo 'selected'; ?>>Mumbai</option>
            </select>

            <button type="submit" style="padding: 10px 20px; background-color: #b0c4c4; border: none; font-weight: bold; cursor: pointer;">SEARCH</button>
            </form>
    </div>
    <div class="job-grid">

        <?php
            $conn = new mysqli("localhost", "root", "", "form_app");
            if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

            $sql = "SELECT * FROM job_postings";
            $conditions = [];

            if (!empty($_GET['search_name'])) {
                $name = $conn->real_escape_string($_GET['search_name']);
                $conditions[] = "job_title LIKE '%$name%'";
            }
            if (!empty($_GET['search_category'])) {
                $category = $conn->real_escape_string($_GET['search_category']);
                $conditions[] = "category = '$category'";
            }
            if (!empty($_GET['search_state'])) {
                $state = $conn->real_escape_string($_GET['search_state']);
                $conditions[] = "state = '$state'";
            }
            if (!empty($_GET['search_city'])) {
                $city = $conn->real_escape_string($_GET['search_city']);
                $conditions[] = "city = '$city'";
            }

            if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }

            $sql .= " ORDER BY id DESC"; 

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $image_src = !empty($row['image_path']) ? $row['image_path'] : 'https://via.placeholder.com/250x150?text=No+Image';

                    echo '<a href="job_details.php?id=' . $row["id"] . '" style="text-decoration: none; color: inherit; display: block;">';
                    echo '<div class="job-card">';
                    echo '<img src="' . $image_src . '" alt="Job Cover" class="job-image">';
                    echo '<div class="job-details">';
                    
                    echo '<h3>' . htmlspecialchars($row["job_title"]) . '</h3>';
                    echo '<p><strong>Location:</strong> ' . htmlspecialchars($row["city"]) . '</p>';
                    echo '<p><strong>Salary:</strong> ' . htmlspecialchars($row["salary"]) . '</p>';
                    echo '<p><strong>Recruiter:</strong> ' . htmlspecialchars($row["recruiter"]) . '</p>';
                    
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo "<p style='grid-column: 1 / -1; text-align: center;'>No jobs found matching your criteria.</p>";
            }
            $conn->close();
        ?>

    </div>
</body>
</html>