<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Manage Timeslots</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "INSERT INTO timeslots (day_of_week, start_time, end_time) VALUES ('$day_of_week', '$start_time', '$end_time')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New timeslot added successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM timeslots WHERE timeslot_id=$id");
    echo "<script>window.location.href='timeslots.php';</script>";
}
?>

<form method="post" action="">
    <h3>Add New Timeslot</h3>
    <label>Day of Week:</label>
    <select name="day_of_week" required>
        <option value="Monday">Monday</option>
        <option value="Tuesday">Tuesday</option>
        <option value="Wednesday">Wednesday</option>
        <option value="Thursday">Thursday</option>
        <option value="Friday">Friday</option>
        <option value="Saturday">Saturday</option>
        <option value="Sunday">Sunday</option>
    </select>
    
    <label>Start Time:</label>
    <input type="time" name="start_time" required>
    
    <label>End Time:</label>
    <input type="time" name="end_time" required>
    
    <input type="submit" value="Add Timeslot">
</form>

<h3>Existing Timeslots</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Day</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["timeslot_id"]. "</td>
                    <td>" . $row["day_of_week"]. "</td>
                    <td>" . $row["start_time"]. "</td>
                    <td>" . $row["end_time"]. "</td>
                    <td><a href='timeslots.php?delete=" . $row["timeslot_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No timeslots found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
