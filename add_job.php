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

$job_data = null;
if (isset($_GET['id'])) {
    $job_id = intval($_GET['id']);
    $edit_sql = "SELECT * FROM job_postings WHERE id = $job_id";
    $edit_result = $conn->query($edit_sql);
    if ($edit_result->num_rows > 0) {
        $job_data = $edit_result->fetch_assoc();
    }
}

require_once 'sidebar.php';
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
                        Swal.fire({
                            title: 'Success!',
                            text: 'Job details saved successfully.',
                            icon: 'success',
                            confirmButtonColor: '#686818'
                        });

                        <?php if (!isset($job_data)): ?>
                            $('#jobForm')[0].reset();
                            myEditor.setData('');
                        <?php endif; ?>
                    } else {
                        Swal.fire('Error!', response, 'error');
                    }
                }
            });
        });
    });
</script>

</div>
</body>

</html>