<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timetable Scheduling Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Timetable Scheduling Management System</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="departments.php">Departments</a></li>
        <li><a href="courses.php">Courses</a></li>
        <li><a href="lecturers.php">Lecturers</a></li>
        <li><a href="rooms.php">Rooms</a></li>
        <li><a href="timeslots.php">Timeslots</a></li>
        <li><a href="schedule.php">Schedule Allocations</a></li>
        <li><a href="view_timetable.php">View Timetable</a></li>
        <li><a href="lecturer_load.php">Lecturer Load</a></li>
        <?php if(isset($_SESSION['username'])): ?>
            <li><a href="logout.php" style="color: #ff7675;">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
        <?php endif; ?>
    </ul>
</nav>

<div class="container">
