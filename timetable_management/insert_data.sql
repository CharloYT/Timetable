USE timetable_db;

-- Insert Departments
INSERT INTO departments (dept_name, dept_code) VALUES 
('Computer Science', 'CS'),
('Electrical Engineering', 'EE'),
('Business Administration', 'BA');

-- Insert Courses
INSERT INTO courses (course_code, course_title, credits, dept_id) VALUES 
('CS101', 'Introduction to Programming', 3, 1),
('CS102', 'Data Structures', 3, 1),
('EE201', 'Circuit Theory', 3, 2),
('BA101', 'Principles of Management', 3, 3),
('CS301', 'Database Systems', 3, 1);

-- Insert Lecturers
INSERT INTO lecturers (first_name, last_name, email, dept_id) VALUES 
('Chukwu', 'Okonkwo', 'chukwu.okonkwo@uni.edu', 1),
('Amara', 'Adeyemi', 'amara.adeyemi@uni.edu', 1),
('Kofi', 'Awolowo', 'kofi.awolowo@uni.edu', 2),
('Zainab', 'Ibrahim', 'zainab.ibrahim@uni.edu', 3);

-- Insert Rooms
INSERT INTO rooms (room_name, capacity, room_type) VALUES 
('Bucodel Room 1', 100, 'Lecture Hall'),
('E202', 150, 'Laboratory'),
('WRA ', 500, 'Seminar Room'),
('Bucodel Room 3', 150, 'Lecture Hall');

-- Insert Timeslots
INSERT INTO timeslots (day_of_week, start_time, end_time) VALUES 
('Monday', '09:00:00', '10:30:00'),
('Monday', '11:00:00', '12:30:00'),
('Tuesday', '09:00:00', '10:30:00'),
('Tuesday', '14:00:00', '15:30:00'),
('Wednesday', '10:00:00', '11:30:00');

-- Insert Schedule Allocations
-- CS101 by John Doe in Hall A on Mon 9:00
INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES 
(1, 1, 1, 1);

-- CS102 by Jane Smith in Lab 1 on Mon 11:00
INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES 
(2, 2, 2, 2);

-- EE201 by Robert Brown in Hall B on Tue 9:00
INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES 
(3, 3, 4, 3);

-
INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES 
(4, 4, 3, 4);


INSERT INTO schedule_allocations (course_id, lecturer_id, room_id, timeslot_id) VALUES 
(5, 1, 1, 5);
