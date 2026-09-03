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
        <?php elseif ($page == 'add_job'): ?>
            <div class="form-card" style="background: white; padding: 20px; border-radius: 8px;">
                <h2>Add Job Details</h2>
                <form id="jobForm" enctype="multipart/form-data">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label>Job Title</label><br>
                        <input type="text" name="job_title" required style="width: 100%; padding: 8px;">
                    </div>
                    <button type="submit" style="padding: 10px 20px; background: #686818; color: white; border: none; cursor: pointer;">Submit</button>
                    <div id="message"></div>
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