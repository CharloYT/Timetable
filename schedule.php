<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Schedule Allocations</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $lecturer_id = $_POST['lecturer_id'];
    $room_id = $_POST['room_id'];
    $timeslot_id = $_POST['timeslot_id'];

    $sql = "INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES ('$course_id', '$lecturer_id', '$room_id', '$timeslot_id')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Class scheduled successfully!</div>";
    } else {
        // Check for specific error codes for better user feedback
        if ($conn->errno == 1062) {
            if (strpos($conn->error, 'unique_room_booking') !== false) {
                echo "<div class='alert alert-danger'>Error: Room is already booked for this time!</div>";
            } elseif (strpos($conn->error, 'unique_lecturer_booking') !== false) {
                echo "<div class='alert alert-danger'>Error: Lecturer is already teaching at this time!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: Duplicate entry found.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM schedule_allocations WHERE allocation_id=$id");
    echo "<script>window.location.href='schedule.php';</script>";
}
?>

<form method="post" action="">
    <h3>Create New Allocation</h3>
    
    <label>Course:</label>
    <select name="course_id" required>
        <option value="">Select Course</option>
        <?php
        $result = $conn->query("SELECT * FROM courses");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['course_id'] . "'>" . $row['course_code'] . " - " . $row['course_title'] . "</option>";
        }
        ?>
    </select>
    
    <label>Lecturer:</label>
    <select name="lecturer_id" required>
        <option value="">Select Lecturer</option>
        <?php
        $result = $conn->query("SELECT * FROM lecturers");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['lecturer_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
        }
        ?>
    </select>
    
    <label>Room:</label>
    <select name="room_id" required>
        <option value="">Select Room</option>
        <?php
        $result = $conn->query("SELECT * FROM rooms");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['room_id'] . "'>" . $row['room_name'] . " (" . $row['room_type'] . ")</option>";
        }
        ?>
    </select>
    
    <label>Timeslot:</label>
    <select name="timeslot_id" required>
        <option value="">Select Timeslot</option>
        <?php
        $result = $conn->query("SELECT * FROM timeslots ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), start_time");
        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['timeslot_id'] . "'>" . $row['day_of_week'] . " " . $row['start_time'] . " - " . $row['end_time'] . "</option>";
        }
        ?>
    </select>
    
    <input type="submit" value="Allocate Class">
</form>

<h3>Current Allocations</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Course</th>
        <th>Lecturer</th>
        <th>Room</th>
        <th>Time</th>
        <th>Action</th>
    </tr>
    <?php
    $sql = "SELECT sa.allocation_id, c.course_code, l.first_name, l.last_name, r.room_name, t.day_of_week, t.start_time, t.end_time 
            FROM schedule_allocations sa
            JOIN courses c ON sa.course_id = c.course_id
            JOIN lecturers l ON sa.lecturer_id = l.lecturer_id
            JOIN rooms r ON sa.room_id = r.room_id
            JOIN timeslots t ON sa.timeslot_id = t.timeslot_id
            ORDER BY t.day_of_week, t.start_time";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["allocation_id"]. "</td>
                    <td>" . $row["course_code"]. "</td>
                    <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                    <td>" . $row["room_name"]. "</td>
                    <td>" . $row["day_of_week"] . " " . $row["start_time"] . "-" . $row["end_time"] . "</td>
                    <td><a href='schedule.php?delete=" . $row["allocation_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No allocations found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
