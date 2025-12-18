<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Master Timetable</h2>

<div style="overflow-x: auto;">
    <table>
        <tr>
            <th>Day</th>
            <th>Time</th>
            <th>Course</th>
            <th>Lecturer</th>
            <th>Room</th>
        </tr>
        <?php
        $sql = "SELECT c.course_code, c.course_title, l.first_name, l.last_name, r.room_name, t.day_of_week, t.start_time, t.end_time 
                FROM schedule_allocations sa
                JOIN courses c ON sa.course_id = c.course_id
                JOIN lecturers l ON sa.lecturer_id = l.lecturer_id
                JOIN rooms r ON sa.room_id = r.room_id
                JOIN timeslots t ON sa.timeslot_id = t.timeslot_id
                ORDER BY FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), t.start_time";
                
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $current_day = "";
            while($row = $result->fetch_assoc()) {
                // Add a separator row for new days
                if ($current_day != $row["day_of_week"]) {
                    $current_day = $row["day_of_week"];
                    echo "<tr style='background-color: #bdc3c7; font-weight: bold;'><td colspan='5'>" . $current_day . "</td></tr>";
                }
                
                echo "<tr>
                        <td>" . $row["day_of_week"]. "</td>
                        <td>" . $row["start_time"] . " - " . $row["end_time"] . "</td>
                        <td>" . $row["course_code"] . " (" . $row["course_title"] . ")</td>
                        <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                        <td>" . $row["room_name"]. "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No classes scheduled</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'footer.php'; ?>
