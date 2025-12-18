<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Manage Rooms</h2>

<?php
// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_name = $_POST['room_name'];
    $capacity = $_POST['capacity'];
    $room_type = $_POST['room_type'];

    $sql = "INSERT INTO rooms (room_name, capacity, room_type) VALUES ('$room_name', '$capacity', '$room_type')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>New room added successfully</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM rooms WHERE room_id=$id");
    echo "<script>window.location.href='rooms.php';</script>";
}
?>

<form method="post" action="">
    <h3>Add New Room</h3>
    <label>Room Name:</label>
    <input type="text" name="room_name" required>
    
    <label>Capacity:</label>
    <input type="number" name="capacity" required>
    
    <label>Room Type:</label>
    <select name="room_type" required>
        <option value="Lecture Hall">Lecture Hall</option>
        <option value="Laboratory">Laboratory</option>
        <option value="Seminar Room">Seminar Room</option>
    </select>
    
    <input type="submit" value="Add Room">
</form>

<h3>Existing Rooms</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Capacity</th>
        <th>Type</th>
        <th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM rooms");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["room_id"]. "</td>
                    <td>" . $row["room_name"]. "</td>
                    <td>" . $row["capacity"]. "</td>
                    <td>" . $row["room_type"]. "</td>
                    <td><a href='rooms.php?delete=" . $row["room_id"] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No rooms found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
