<?php include 'header.php'; include 'db_connect.php'; ?>

<h2>Lecturer Workload Report</h2>

<table>
    <tr>
        <th>Lecturer Name</th>
        <th>Department</th>
        <th>Total Courses Taught</th>
        <th>Total Hours/Week</th>
    </tr>
    <?php
    // Complex query to aggregate workload
    $sql = "SELECT l.first_name, l.last_name, d.dept_name, 
            COUNT(sa.allocation_id) as total_classes,
            SUM(TIME_TO_SEC(TIMEDIFF(t.end_time, t.start_time))/3600) as total_hours
            FROM lecturers l
            LEFT JOIN departments d ON l.dept_id = d.dept_id
            LEFT JOIN schedule_allocations sa ON l.lecturer_id = sa.lecturer_id
            LEFT JOIN timeslots t ON sa.timeslot_id = t.timeslot_id
            GROUP BY l.lecturer_id";
            
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hours = $row["total_hours"] ? round($row["total_hours"], 1) : 0;
            echo "<tr>
                    <td>" . $row["first_name"] . " " . $row["last_name"] . "</td>
                    <td>" . $row["dept_name"]. "</td>
                    <td>" . $row["total_classes"]. "</td>
                    <td>" . $hours . " hrs</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No data found</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
