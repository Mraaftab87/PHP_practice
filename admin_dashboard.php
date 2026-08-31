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

$sql = "SELECT applications.*, job_postings.job_title 
        FROM applications 
        JOIN job_postings ON applications.job_id = job_postings.id 
        ORDER BY applications.id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
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
    <h2>Admin Dashboard - Job Applications</h2>
    <a href="logout.php" style="float: right; color: red;">Logout</a>

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
                echo "<td><a href='" . htmlspecialchars($row['resume']) . "' target='_blank' class='view-btn'>View Resume</a></td>";
                echo "<td><a href='delete_application.php?id=" . $row['id'] . "' style='color: red; font-weight: bold; text-decoration: none;'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center; padding: 20px;'>There is not pending applicaion.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>

</html>