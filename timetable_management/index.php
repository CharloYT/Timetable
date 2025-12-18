<?php include 'auth_check.php'; ?>
<?php include 'header.php'; ?>

<h2>Welcome to the Timetable Management System</h2>
<p>Use the navigation menu above to manage your data and create schedules.</p>

<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    <div class="dashboard-card">
        <h3>Quick Links</h3>
        <ul>
            <li><a href="schedule.php">Create New Schedule Allocation</a></li>
            <li><a href="view_timetable.php">View Timetable</a></li>
            <li><a href="lecturers.php">Manage Lecturers</a></li>
        </ul>
    </div>
    
    <div class="dashboard-card">
        <h3>System Status</h3>
        <p>Database Connection: 
            <?php 
            include 'db_connect.php';
            if($conn) { echo "<span style='color: #2ecc71; font-weight: bold;'>Connected</span>"; } 
            else { echo "<span style='color: #e74c3c; font-weight: bold;'>Failed</span>"; }
            ?>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
