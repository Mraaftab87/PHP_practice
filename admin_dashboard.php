<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "form_app");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$page = isset($_GET['page']) ? $_GET['page'] : 'applications';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <style>
        .form-card {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group,
        .row {
            margin-bottom: 15px;
        }

        .row {
            display: flex;
            gap: 20px;
        }

        .col {
            flex: 1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-family: inherit;
            box-sizing: border-box;
        }

        .ck-editor__editable {
            min-height: 200px;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px 0;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 15px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #686818;
            color: white;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h3 style="text-align: center; color: #e2cf6d;">Admin Panel</h3>
        <a href="admin_dashboard.php?page=applications">View Applications</a>
        <a href="admin_dashboard.php?page=add_job">Add New Job</a>
        <a href="logout.php" style="color: #ff4c4c;">Logout</a>
    </div>
    <div class="main-content">
        <?php if ($page == 'applications'): ?>
            <h2>Job Applications</h2>
            <?php
            $sql = "SELECT applications.*, job_postings.job_title 
                    FROM applications 
                    JOIN job_postings ON applications.job_id = job_postings.id 
                    ORDER BY applications.id DESC";
            $result = $conn->query($sql);
            ?>
            <table>
                <tr>
                    <th>Job Title</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Cover Letter</th>
                    <th>Resume</th>
                    <th>Action</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                        echo "<td>" . nl2br(htmlspecialchars($row['cover_letter'])) . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['resume']) . "' target='_blank'>View Resume</a></td>";
                        echo "<td><a href='delete_application.php?id=" . $row['id'] . "' style='color: red; font-weight: bold; text-decoration: none;'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>There is no pending application.</td></tr>";
                }
                ?>
            </table>
        <?php elseif ($page == 'add_job' || $page == 'edit_job'): ?>

            <?php
            $job_data = null;
            if (isset($_GET['id'])) {
                $job_id = intval($_GET['id']);
                $edit_sql = "SELECT * FROM job_postings WHERE id = $job_id";
                $edit_result = $conn->query($edit_sql);
                if ($edit_result->num_rows > 0) {
                    $job_data = $edit_result->fetch_assoc();
                }
            }
            ?>

            <div class="form-card" style="background: white; padding: 20px; border-radius: 8px;">
                <h2><?php echo isset($job_data) ? 'Edit Job Details' : 'Add Job Details'; ?></h2>

                <form id="jobForm" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?php echo isset($job_data['id']) ? $job_data['id'] : ''; ?>">

                    <div class="form-group">
                        <label>Job Title</label>
                        <input type="text" name="job_title" placeholder="Enter the Job Title" required
                            value="<?php echo isset($job_data['job_title']) ? htmlspecialchars($job_data['job_title']) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label>Job Description</label>
                        <textarea name="job_description" id="job_desc" placeholder="Description"><?php echo isset($job_data['job_description']) ? htmlspecialchars($job_data['job_description']) : ''; ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Job Type</label>
                            <select name="job_type" required>
                                <option value="">Select Job Type</option>
                                <option value="Full-time" <?php echo (isset($job_data['job_type']) && $job_data['job_type'] == 'Full-time') ? 'selected' : ''; ?>>Full-time</option>
                                <option value="Part-time" <?php echo (isset($job_data['job_type']) && $job_data['job_type'] == 'Part-time') ? 'selected' : ''; ?>>Part-time</option>
                                <option value="Contract" <?php echo (isset($job_data['job_type']) && $job_data['job_type'] == 'Contract') ? 'selected' : ''; ?>>Contract</option>
                                <option value="Freelance" <?php echo (isset($job_data['job_type']) && $job_data['job_type'] == 'Freelance') ? 'selected' : ''; ?>>Freelance</option>
                                <option value="Internship" <?php echo (isset($job_data['job_type']) && $job_data['job_type'] == 'Internship') ? 'selected' : ''; ?>>Internship</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>Years of experience</label>
                            <input type="text" name="experience" placeholder="e.g. 1 or +1 years" required
                                value="<?php echo isset($job_data['experience']) ? htmlspecialchars($job_data['experience']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Career level</label>
                            <input type="text" name="career_level" placeholder="e.g. Manager" required
                                value="<?php echo isset($job_data['career_level']) ? htmlspecialchars($job_data['career_level']) : ''; ?>">
                        </div>
                        <div class="col">
                            <label>Salary</label>
                            <input type="text" name="salary" placeholder="e.g. $65,000-$70,000" required
                                value="<?php echo isset($job_data['salary']) ? htmlspecialchars($job_data['salary']) : ''; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Recruiter</label>
                            <input type="text" name="recruiter" placeholder="e.g. Curtis Monroe" required
                                value="<?php echo isset($job_data['recruiter']) ? htmlspecialchars($job_data['recruiter']) : ''; ?>">
                        </div>
                        <div class="col">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="e.g. curtis@hprosearch.com" required
                                value="<?php echo isset($job_data['email']) ? htmlspecialchars($job_data['email']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Categories</label>
                        <select name="category" required>
                            <option value="">Select Category</option>
                            <option value="All" <?php echo (isset($job_data['category']) && $job_data['category'] == 'All') ? 'selected' : ''; ?>>All</option>
                            <option value="Resorts" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Resorts') ? 'selected' : ''; ?>>Resorts</option>
                            <option value="Fast Casual" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Fast Casual') ? 'selected' : ''; ?>>Fast Casual</option>
                            <option value="Fine Dining" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Fine Dining') ? 'selected' : ''; ?>>Fine Dining</option>
                            <option value="Italian" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Italian') ? 'selected' : ''; ?>>Italian</option>
                            <option value="Mexican" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Mexican') ? 'selected' : ''; ?>>Mexican</option>
                            <option value="Seafood" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Seafood') ? 'selected' : ''; ?>>Seafood</option>
                            <option value="Sports Bar" <?php echo (isset($job_data['category']) && $job_data['category'] == 'Sports Bar') ? 'selected' : ''; ?>>Sports Bar</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>State</label>
                            <select name="state" required>
                                <option value="">Select State</option>
                                <option value="Gujarat" <?php echo (isset($job_data['state']) && $job_data['state'] == 'Gujarat') ? 'selected' : ''; ?>>Gujarat</option>
                                <option value="Rajasthan" <?php echo (isset($job_data['state']) && $job_data['state'] == 'Rajasthan') ? 'selected' : ''; ?>>Rajasthan</option>
                                <option value="Maharashtra" <?php echo (isset($job_data['state']) && $job_data['state'] == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                            </select>
                        </div>
                        <div class="col">
                            <label>City</label>
                            <select name="city" required>
                                <option value="">Select City</option>
                                <option value="Rajkot" <?php echo (isset($job_data['city']) && $job_data['city'] == 'Rajkot') ? 'selected' : ''; ?>>Rajkot</option>
                                <option value="Ahmedabad" <?php echo (isset($job_data['city']) && $job_data['city'] == 'Ahmedabad') ? 'selected' : ''; ?>>Ahmedabad</option>
                                <option value="Mumbai" <?php echo (isset($job_data['city']) && $job_data['city'] == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Upload Cover Image <?php echo isset($job_data) ? '(Leave empty to keep current image)' : ''; ?></label>
                        <input type="file" name="job_image" accept="image/*" <?php echo isset($job_data) ? '' : 'required'; ?>>

                        <?php if (isset($job_data['image_path']) && !empty($job_data['image_path'])): ?>
                            <p style="font-size: 12px; color: green;">Current Image: <?php echo htmlspecialchars(basename($job_data['image_path'])); ?></p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" style="width: 100%; padding: 12px; background-color: #0d6efd; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 15px;">
                        <?php echo isset($job_data) ? 'Update Job' : 'Submit Job'; ?>
                    </button>
                    <div id="message" style="text-align: center; margin-top: 15px; font-weight: bold;"></div>
                </form>
            </div>
            <script>
                $(document).ready(function() {
                    let myEditor;
                    ClassicEditor.create(document.querySelector('#job_desc'))
                        .then(editor => {
                            myEditor = editor;
                        })
                        .catch(error => {
                            console.error(error);
                        });
                    $('#jobForm').on('submit', function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $.ajax({
                            type: 'POST',
                            url: 'process_job.php',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.trim() == "success") {
                                    $('#message').html('<span style="color: green;">Job saved successfully!</span>');
                                    $('#jobForm')[0].reset();
                                    myEditor.setData('');
                                } else {
                                    $('#message').html('<span style="color: red;">Error: ' + response + '</span>');
                                }
                            }
                        });
                    });
                });
            </script>
        <?php endif; ?>
    </div>
</body>

</html>