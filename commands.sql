-- Create the database
CREATE DATABASE Car_rental_system;

USE Car_rental_system;

-- Create office table
CREATE TABLE office (
    office_id INT AUTO_INCREMENT PRIMARY KEY,
    office_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL
);

-- Create users table
CREATE TABLE `user` (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    address VARCHAR(255) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type CHAR(1) NOT NULL DEFAULT 'C',
    CONSTRAINT chk_user_type CHECK (user_type IN ('C', 'A'))
);

-- Create car table
CREATE TABLE car (
    car_id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    no_of_seats INT NOT NULL,
    year INT NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    status VARCHAR(50) NOT NULL,
    office_id INT NULL,
    day_rate DECIMAL(10, 2) NOT NULL,
    last_update_date DATE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_car_status CHECK (status IN ('Active', 'Out of Service', 'Rented'))
);


-- Create reservation table
CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    CONSTRAINT chk_reservation_dates CHECK (reservation_date <= pickup_date AND pickup_date < return_date)
);


INSERT INTO office (office_id, office_name, location) VALUES
(1, 'Downtown Office', 'Downtown City Center'),
(2, 'Airport Office', 'International Airport'),
(3, 'Suburban Office', 'Suburban Mall'),
(4, 'Central Office', 'City Center Plaza'),
(5, 'Seaside Office', 'Coastal Area'),
(6, 'Mountain Office', 'Mountain Region'),
(7, 'Tech Hub Office', 'Technology Park'),
(8, 'Old Town Office', 'Historic District'),
(9, 'Luxury Office', 'Luxury Estates'),
(10, 'Countryside Office', 'Rural Area');


INSERT INTO `user` (user_id, first_name, last_name, email, phone_number, address, username, password, user_type) VALUES
(1, 'John', 'Doe', 'johndoe@example.com', '1234567890', '123 Main St, Downtown City Center', 'johndoe', 'password123', 'C'),
(2, 'Jane', 'Smith', 'janesmith@example.com', '0987654321', '456 Oak Rd, Suburban Mall', 'janesmith', 'securepassword', 'C'),
(3, 'Alice', 'Johnson', 'alicej@example.com', '1112223333', '789 Pine Ave, Airport Region', 'alicej', 'mypassword', 'C'),
(4, 'Bob', 'Brown', 'bobb@example.com', '4445556666', '100 Sunset Blvd, Coastal Area', 'bobb', 'password456', 'C'),
(5, 'Emma', 'Williams', 'emmaw@example.com', '7778889999', '200 Ocean Dr, City Center Plaza', 'emmaw', 'secure123', 'C'),
(6, 'Oliver', 'Martinez', 'oliverm@example.com', '3332221110', '300 Maple Rd, Mountain Region', 'oliverm', 'password789', 'C'),
(7, 'Lily', 'Anderson', 'lilya@example.com', '6665554443', '500 Birch Lane, Technology Park', 'lilya', 'passsecure', 'C'),
(8, 'Lucas', 'Walker', 'lucasw@example.com', '9998887776', '700 Willow Blvd, Luxury Estates', 'lucasw', 'lucaspass', 'C'),
(9, 'Sophia', 'Davis', 'sophiad@example.com', '9988776655', '88 Ocean Ave, Historic District', 'sophiad', 'sophiapass', 'C'),
(10, 'Liam', 'Wilson', 'liamw@example.com', '8877665544', '99 Highland St, Rural Area', 'liamw', 'liamsecure', 'C'),
(11, 'Admin', 'Smith', 'adminsmith@example.com', '8889990000', 'Admin HQ, City Center Plaza', 'adminsmith', 'adminpassword', 'A'),
(12, 'Admin', 'Brown', 'adminbrown@example.com', '7776665555', 'Admin Plaza, Technology Park', 'adminbrown', 'adminsecure', 'A'),
(13, 'Ethan', 'Harris', 'ethanh@example.com', '5554443332', 'Admin Office, Downtown City Center', 'ethanh', 'ethanpass', 'A');


INSERT INTO car (car_id, make, model, no_of_seats, year, plate_number, status, office_id, day_rate) VALUES
-- Toyota
(1, 'Toyota', 'Corolla', 5, 2021, 'TOY123', 'Active', 1, 45.00),
(2, 'Toyota', 'Camry', 7, 2022, 'TOY124', 'Active', 2, 55.00),
(3, 'Toyota', 'Supra', 2, 2023, 'TOY125', 'Rented', 3, 100.00),
(4, 'Toyota', 'Sienna', 8, 2020, 'TOY126', 'Out of Service', 4, 85.00),

-- Honda
(5, 'Honda', 'Civic', 4, 2022, 'HON123', 'Active', 5, 50.00),
(6, 'Honda', 'Accord', 5, 2021, 'HON124', 'Rented', 6, 60.00),
(7, 'Honda', 'Odyssey', 8, 2023, 'HON125', 'Active', 7, 75.00),

-- Ford
(8, 'Ford', 'Focus', 4, 2020, 'FOR123', 'Out of Service', 8, 48.00),
(9, 'Ford', 'Explorer', 7, 2023, 'FOR124', 'Rented', 9, 85.00),
(10, 'Ford', 'Mustang', 2, 2021, 'FOR125', 'Active', 10, 90.00),

-- BMW
(11, 'BMW', 'X3', 5, 2022, 'BMW123', 'Active', 1, 100.00),
(12, 'BMW', 'X5', 6, 2023, 'BMW124', 'Rented', 2, 120.00),

-- Mercedes
(13, 'Mercedes', 'C-Class', 4, 2021, 'MER123', 'Active', 3, 90.00),
(14, 'Mercedes', 'E-Class', 5, 2022, 'MER124', 'Active', 4, 110.00),
(15, 'Mercedes', 'Sprinter', 8, 2023, 'MER125', 'Out of Service', 5, 130.00);




INSERT INTO reservation (reservation_id, user_id, car_id, reservation_date, pickup_date, return_date, total_amount) VALUES
(1, 1, 1, '2024-12-01', '2024-12-05', '2024-12-10', 225.0),
(2, 2, 2, '2024-12-02', '2024-12-07', '2024-12-15', 400.0),
(3, 3, 3, '2024-11-30', '2024-12-01', '2024-12-05', 192.0),
(4, 4, 6, '2024-12-04', '2024-12-10', '2024-12-15', 200.0),
(5, 5, 7, '2024-12-05', '2024-12-08', '2024-12-12', 220.0),
(6, 6, 8, '2024-12-06', '2024-12-07', '2024-12-14', 350.0),
(7, 7, 9, '2024-12-07', '2024-12-08', '2024-12-15', 420.0),
(8, 8, 10, '2024-12-08', '2024-12-09', '2024-12-16', 560.0),
(9, 9, 11, '2024-12-09', '2024-12-10', '2024-12-17', 455.0),
(10, 10, 12, '2024-12-10', '2024-12-11', '2024-12-18', 490.0),
(11, 1, 2, '2024-12-01', '2024-12-12', '2024-12-16', 250.0),
(12, 1, 3, '2024-12-01', '2024-12-20', '2024-12-25', 300.0),
(13, 2, 4, '2024-12-02', '2024-12-10', '2024-12-12', 170.0),
(14, 2, 5, '2024-12-02', '2024-12-15', '2024-12-20', 450.0),
(15, 3, 6, '2024-12-03', '2024-12-18', '2024-12-22', 500.0),
(16, 4, 1, '2024-12-01', '2024-12-10', '2024-12-15', 300.0),
(17, 5, 2, '2024-12-01', '2024-12-10', '2024-12-12', 220.0),
(18, 6, 3, '2024-12-01', '2024-12-12', '2024-12-16', 260.0),
(19, 7, 1, '2024-12-03', '2024-12-15', '2024-12-20', 340.0),
(20, 8, 1, '2024-12-04', '2024-12-18', '2024-12-25', 400.0),
(21, 9, 2, '2024-12-05', '2024-12-20', '2024-12-24', 450.0),
(22, 10, 3, '2024-12-06', '2024-12-08', '2024-12-15', 180.0),
(23, 11, 4, '2024-12-06', '2024-12-10', '2024-12-20', 300.0),
(24, 12, 4, '2024-12-06', '2024-12-12', '2024-12-18', 350.0),
(25, 1, 7, '2024-12-01', '2024-12-15', '2024-12-20', 400.0),
(26, 2, 8, '2024-12-02', '2024-12-12', '2024-12-18', 500.0),
(27, 3, 9, '2024-12-03', '2024-12-20', '2024-12-25', 300.0),
(28, 4, 10, '2024-12-04', '2024-12-10', '2024-12-15', 600.0),
(29, 5, 11, '2024-12-05', '2024-12-18', '2024-12-22', 450.0),
(30, 6, 12, '2024-12-06', '2024-12-20', '2024-12-25', 550.0);




-- Foreign Key Constraints
ALTER TABLE car
ADD CONSTRAINT FK_car_office FOREIGN KEY (office_id) REFERENCES office (office_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservation_user FOREIGN KEY (user_id) REFERENCES `user` (user_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservations_car FOREIGN KEY (car_id) REFERENCES car (car_id) ON DELETE CASCADE ON UPDATE CASCADE;
