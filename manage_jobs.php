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

<h2>Manage Jobs</h2>
<?php
$sql = "SELECT * FROM job_postings ORDER BY id DESC";
$result = $conn->query($sql);
?>
<table>
    <tr>
        <th>Job Title</th>
        <th>Category</th>
        <th>Job Type</th>
        <th>City</th>
        <th>Salary</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['job_title']) . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>" . htmlspecialchars($row['job_type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['city']) . "</td>";
            echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
            echo "<td>
                    <div class='action-btns'>
                        <a href='add_job.php?id=" . $row['id'] . "' class='btn-action btn-edit'>Edit</a>
                        <a href='delete_job.php?id=" . $row['id'] . "' class='btn-action btn-delete delete-btn'>Delete</a>
                    </div>
                  </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6' style='text-align:center; padding: 20px;'>No jobs found.</td></tr>";
    }
    ?>
</table>

</div>
</body>

</html>