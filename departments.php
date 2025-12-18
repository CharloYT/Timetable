<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Manage Departments</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dept_name = $_POST['dept_name'];
    $dept_code = $_POST['dept_code'];

    $sql = "INSERT INTO departments (dept_name, dept_code) VALUES ('$dept_name', '$dept_code')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New department created successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM departments WHERE dept_id=$id");
    echo "<script>window.location.href='departments.php';</script>";
}
?>

<form method="post" action="">
    <h3>Add New Department</h3>
    <label>Department Name:</label>
    <input type="text" name="dept_name" required>
    
    <label>Department Code:</label>
    <input type="text" name="dept_code" required>
    
    <input type="submit" value="Add Department">
</form>

<h3>Existing Departments</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Code</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM departments");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["dept_id"]. "</td>
                    <td>" . $row["dept_name"]. "</td>
                    <td>" . $row["dept_code"]. "</td>
                    <td><a href='departments.php?delete=" . $row["dept_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No departments found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
