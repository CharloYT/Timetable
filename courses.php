<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Manage Courses</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_code = $_POST['course_code'];
    $course_title = $_POST['course_title'];
    $credits = $_POST['credits'];
    $dept_id = $_POST['dept_id'];

    $sql = "INSERT INTO courses (course_code, course_title, credits, dept_id) VALUES ('$course_code', '$course_title', '$credits', '$dept_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New course created successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM courses WHERE course_id=$id");
    echo "<script>window.location.href='courses.php';</script>";
}
?>

<form method="post" action="">
    <h3>Add New Course</h3>
    <label>Course Code:</label>
    <input type="text" name="course_code" required>
    
    <label>Course Title:</label>
    <input type="text" name="course_title" required>
    
    <label>Credits:</label>
    <input type="number" name="credits" required>
    
    <label>Department:</label>
    <select name="dept_id" required>
        <option value="">Select Department</option>
        <?php
        $dept_result = $conn->query("SELECT * FROM departments");
        while($dept = $dept_result->fetch_assoc()) {
            echo "<option value='" . $dept['dept_id'] . "'>" . $dept['dept_name'] . "</option>";
        }
        ?>
    </select>
    
    <input type="submit" value="Add Course">
</form>

<h3>Existing Courses</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Title</th>
        <th>Credits</th>
        <th>Department</th>
        <th>Action</th>
    </tr>
    <?php
    $sql = "SELECT c.*, d.dept_name FROM courses c LEFT JOIN departments d ON c.dept_id = d.dept_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["course_id"]. "</td>
                    <td>" . $row["course_code"]. "</td>
                    <td>" . $row["course_title"]. "</td>
                    <td>" . $row["credits"]. "</td>
                    <td>" . $row["dept_name"]. "</td>
                    <td><a href='courses.php?delete=" . $row["course_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No courses found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
