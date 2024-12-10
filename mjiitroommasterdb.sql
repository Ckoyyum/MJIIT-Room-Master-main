-- Use or create the MJIIT RoomMaster Database
CREATE DATABASE IF NOT EXISTS mjiitroommasterdb;
USE mjiitroommasterdb;

-- Table for storing user information
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Consider storing hashed passwords
    email VARCHAR(255) NOT NULL UNIQUE
);

-- Table for storing room information
CREATE TABLE IF NOT EXISTS rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    equipment TEXT,  -- List of equipment can be stored as a comma-separated string
    pricing DECIMAL(10, 2) DEFAULT 0.00,
    image VARCHAR(255),  -- Path or URL to the room's picture
    status ENUM('Available', 'Booked', 'Maintenance') NOT NULL DEFAULT 'Available'
);

-- Table for storing bookings
CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('Confirmed', 'Cancelled', 'Completed') NOT NULL DEFAULT 'Confirmed',
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);

-- Sample users (passwords should be hashed using a secure method like bcrypt in a real application)
-- Inserting sample users into the 'users' table
INSERT INTO users (username, password, email) VALUES
('mjadmin', 'admin123', 'admin@mjiit.edu.my'),  -- Admin user
('ahmad_ali', 'password123', 'ahmad.ali@graduate.utm.my'),  -- UTM graduate/student
('nurul_huda', 'password123', 'nurul.huda@graduate.utm.my'),  -- UTM graduate/staff
('guest_user1', 'guestpass', 'guest1@example.com'),  -- Guest user
('siti_zahara', 'password123', 'siti.zahara@company.com'),  -- External/private user
('john_doe', 'password123', 'john.doe@graduate.utm.my'),  -- UTM graduate/affiliate user
('corporate_user', 'securepass', 'corp_user@business.com');  -- Corporate user

-- Sample rooms
-- Insert new room entries into the rooms table
INSERT INTO rooms (room_name, location, capacity, equipment, pricing, image, status) VALUES
('Bilik Kuliah 1', '02.31.01 / 02', 40, 'Projector, Whiteboard', 'bk2.png', NULL, 'Available'),
('Bilik Kuliah 2', '02.36.01 / 02', 40, 'Projector, Whiteboard', 'bk8.png', NULL, 'Available'),
('Bilik Kuliah 3', '02.37.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 4', '03.63.01 / 02', 40, 'Projector, Whiteboard', 'bk2.png', NULL, 'Available'),
('Bilik Kuliah 5', '03.64.01 / 02', 40, 'Projector, Whiteboard', 'bk8.png', NULL, 'Available'),
('Bilik Kuliah 6', '04.37.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 7', '04.38.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 8', '04.41.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 9', '05.44.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 10', '05.45.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 12', '06.50.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 13', '06.51.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 14', '06.56.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 15', '06.57.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 16', '06.63.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 17', '06.62.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 19', '08.44.01 / 08.44.02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 20', '08.47.01 / 08.47.02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 21', '08.45.01 / 08.45.02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Kuliah 22', '08.49.01 / 02 & 08.48.01 / 02', 40, 'Projector, Whiteboard', 'bk20.png', NULL, 'Available'),
('Bilik Sindiket 1', '04.24.01 / 02', 20, 'Projector, Whiteboard', 'seminarroom.png', NULL, 'Available'),
('Bilik Sindiket 2', '04.25.01', 20, 'Projector, Whiteboard', 0.00, 'meetingroom.png', 'Available'),
('Bilik Sindiket 3', '04.26.01', 20, 'Projector, Whiteboard', 0.00, 'seminarroom.png', 'Available'),
('Bilik Sindiket 4', '04.27.01', 20, 'Projector, Whiteboard', 0.00, 'seminarroom.png', 'Available'),
('Bilik Sindiket 5', '04.28.01', 20, 'Projector, Whiteboard', 0.00, 'seminarroom.png', 'Available'),
('Bilik Sindiket 6', '04.29.01', 20, 'Projector, Whiteboard', 0.00, 'seminarroom.png', 'Available');

-- Sample bookings
INSERT INTO bookings (user_id, room_id, booking_date, start_time, end_time, status) VALUES
(1, 1, CURDATE(), '09:00:00', '11:00:00', 'Confirmed'),
(2, 3, CURDATE(), '12:00:00', '14:00:00', 'Confirmed');
