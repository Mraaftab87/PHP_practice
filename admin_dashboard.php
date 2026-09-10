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

require_once 'sidebar.php';
?>

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
        <th>Application Details</th>
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

            echo "<td><a href='view_application.php?id=" . $row['id'] . "' target='_blank' style='color: #0d6efd; font-weight: bold; text-decoration: none;'>View Details</a></td>";

            echo "<td><a href='delete_application.php?id=" . $row['id'] . "' class='delete-btn' style='color: red; font-weight: bold; text-decoration: none;'>Delete</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center; padding: 20px;'>There is no pending application.</td></tr>";
    }
    ?>
</table>

</div>
</body>

</html>