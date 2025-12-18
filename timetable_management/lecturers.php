<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Manage Lecturers</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $dept_id = $_POST['dept_id'];

    $sql = "INSERT INTO lecturers (first_name, last_name, email, dept_id) VALUES ('$first_name', '$last_name', '$email', '$dept_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New lecturer added successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM lecturers WHERE lecturer_id=$id");
    echo "<script>window.location.href='lecturers.php';</script>";
}
?>

<form method="post" action="">
    <h3>Add New Lecturer</h3>
    <label>First Name:</label>
    <input type="text" name="first_name" required>
    
    <label>Last Name:</label>
    <input type="text" name="last_name" required>
    
    <label>Email:</label>
    <input type="email" name="email" required>
    
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
    
    <input type="submit" value="Add Lecturer">
</form>

<h3>Existing Lecturers</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Action</th>
    </tr>
    <?php
    $sql = "SELECT l.*, d.dept_name FROM lecturers l LEFT JOIN departments d ON l.dept_id = d.dept_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["lecturer_id"]. "</td>
                    <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                    <td>" . $row["email"]. "</td>
                    <td>" . $row["dept_name"]. "</td>
                    <td><a href='lecturers.php?delete=" . $row["lecturer_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No lecturers found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
