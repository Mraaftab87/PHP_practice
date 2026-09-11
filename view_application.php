<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Application ID missing.");
}

$app_id = intval($_GET['id']);
$conn = new mysqli("localhost", "root", "", "form_app");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT applications.*, job_postings.job_title 
        FROM applications 
        JOIN job_postings ON applications.job_id = job_postings.id 
        WHERE applications.id = $app_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Application not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Application: <?php echo htmlspecialchars($row['name']); ?></title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 2px solid #686818;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .info-box {
            flex: 1;
            min-width: 200px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #eee;
        }

        .info-box strong {
            display: block;
            color: #686818;
            margin-bottom: 5px;
        }

        .cover-letter {
            background: #fffde7;
            padding: 20px;
            border-left: 4px solid #e2cf6d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .resume-container {
            width: 100%;
            height: 700px;
            border: 1px solid #ccc;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Application for <?php echo htmlspecialchars($row['job_title']); ?></h1>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <strong>Applicant Name</strong>
                <?php echo htmlspecialchars($row['name']); ?>
            </div>
            <div class="info-box">
                <strong>Email Address</strong>
                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>"><?php echo htmlspecialchars($row['email']); ?></a>
            </div>
            <div class="info-box">
                <strong>Phone Number</strong>
                <?php echo htmlspecialchars($row['phone_number']); ?>
            </div>
        </div>

        <h3>Cover Letter</h3>
        <div class="cover-letter">
            <?php echo nl2br(htmlspecialchars($row['cover_letter'])); ?>
        </div>

        <h3>Resume</h3>
        <div class="resume-container">
            <!-- Iframe ke through PDF ya Image yahi par show ho jayegi -->
            <iframe src="<?php echo htmlspecialchars($row['resume']); ?>"></iframe>
        </div>

        <div style="margin-top: 15px; text-align: center;">
            <a href="<?php echo htmlspecialchars($row['resume']); ?>" target="_blank" style="padding: 10px 20px; background: #686818; color: white; text-decoration: none; border-radius: 5px;">Open Resume in Full Screen</a>
        </div>
    </div>

</body>

</html>