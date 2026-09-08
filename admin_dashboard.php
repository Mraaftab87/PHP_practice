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
    
</div>
</body>

</html>