-- Create Database
CREATE DATABASE IF NOT EXISTS timetable_db;
USE timetable_db;

-- 1. Departments Table
CREATE TABLE departments (
    dept_id INT AUTO_INCREMENT,
    dept_name VARCHAR(100) NOT NULL,
    dept_code VARCHAR(10) NOT NULL UNIQUE,
    PRIMARY KEY (dept_id)
);

-- 2. Courses Table
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_title VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    dept_id INT,
    PRIMARY KEY (course_id)
);

-- 3. Lecturers Table
CREATE TABLE lecturers (
    lecturer_id INT AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    dept_id INT,
    PRIMARY KEY (lecturer_id)
);

-- 4. Rooms Table
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT,
    room_name VARCHAR(50) NOT NULL UNIQUE,
    capacity INT NOT NULL,
    room_type ENUM('Lecture Hall', 'Laboratory', 'Seminar Room') NOT NULL,
    PRIMARY KEY (room_id)
);

-- 5. Timeslots Table
CREATE TABLE timeslots (
    timeslot_id INT AUTO_INCREMENT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    PRIMARY KEY (timeslot_id),
    CONSTRAINT unique_timeslot UNIQUE (day_of_week, start_time, end_time)
);

-- 6. Schedule Allocations Table
CREATE TABLE schedule_allocations (
    allocation_id INT AUTO_INCREMENT,
    course_id INT NOT NULL,
    lecturer_id INT NOT NULL,
    room_id INT NOT NULL,
    timeslot_id INT NOT NULL,
    PRIMARY KEY (allocation_id)
);

-- 7. Users Table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
);

-- Add Foreign Keys using ALTER TABLE as requested
ALTER TABLE courses
ADD CONSTRAINT fk_course_dept
FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE SET NULL;

ALTER TABLE lecturers
ADD CONSTRAINT fk_lecturer_dept
FOREIGN KEY (dept_id) REFERENCES departments(dept_id) ON DELETE SET NULL;

ALTER TABLE schedule_allocations
ADD CONSTRAINT fk_alloc_course
FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE;

ALTER TABLE schedule_allocations
ADD CONSTRAINT fk_alloc_lecturer
FOREIGN KEY (lecturer_id) REFERENCES lecturers(lecturer_id) ON DELETE CASCADE;

ALTER TABLE schedule_allocations
ADD CONSTRAINT fk_alloc_room
FOREIGN KEY (room_id) REFERENCES rooms(room_id) ON DELETE CASCADE;

ALTER TABLE schedule_allocations
ADD CONSTRAINT fk_alloc_timeslot
FOREIGN KEY (timeslot_id) REFERENCES timeslots(timeslot_id) ON DELETE CASCADE;

-- Add Constraints to prevent conflicts
-- 1. Room Conflict: A room cannot be booked for two different courses at the same time.
ALTER TABLE schedule_allocations
ADD CONSTRAINT unique_room_booking
UNIQUE (room_id, timeslot_id);

-- 2. Lecturer Conflict: A lecturer cannot teach two courses at the same time.
ALTER TABLE schedule_allocations
ADD CONSTRAINT unique_lecturer_booking
UNIQUE (lecturer_id, timeslot_id);
