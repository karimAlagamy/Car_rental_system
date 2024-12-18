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
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
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
    CONSTRAINT chk_car_status CHECK (status IN ('Active', 'Out of Service', 'Rented'))
);

-- Create reservation table
CREATE TABLE reservation (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    car_id INT NOT NULL,
    reservation_date DATE NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    CONSTRAINT chk_reservation_dates CHECK (reservation_date < pickup_date AND pickup_date < return_date)
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


INSERT INTO `user` (customer_id, first_name, last_name, email, phone_number, address, username, password, user_type) VALUES
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
(1, 'Toyota', 'Corolla', 5, 2021, 'ABC123', 'Active', 1, 45.00),
(2, 'Toyota', 'Camry', 5, 2022, 'XYZ789', 'Active', 1, 50.00),
(3, 'Ford', 'Focus', 5, 2021, 'LMN456', 'Rented', 2, 48.00),
(4, 'Ford', 'Explorer', 7, 2023, 'DEF789', 'Out of Service', 3, 85.00),
(5, 'Tesla', 'Model 3', 5, 2023, 'TES123', 'Active', 4, 90.00),
(6, 'Tesla', 'Model Y', 7, 2022, 'GHJ567', 'Rented', 4, 100.00),
(7, 'Hyundai', 'Sonata', 5, 2020, 'JKL890', 'Out of Service', 5, 55.00),
(8, 'Hyundai', 'Elantra', 5, 2021, 'MNO123', 'Active', 5, 50.00),
(9, 'Nissan', 'Altima', 5, 2022, 'PQR456', 'Rented', 6, 60.00),
(10, 'Nissan', 'Sentra', 5, 2020, 'STU789', 'Active', 6, 55.00),
(11, 'Volkswagen', 'Passat', 5, 2021, 'VWX456', 'Active', 7, 65.00),
(12, 'Volkswagen', 'Jetta', 5, 2019, 'YZA123', 'Out of Service', 7, 60.00),
(13, 'Jeep', 'Wrangler', 5, 2022, 'BCD789', 'Rented', 8, 75.00),
(14, 'Jeep', 'Cherokee', 5, 2023, 'EFG456', 'Active', 8, 70.00),
(15, 'Mercedes-Benz', 'C-Class', 5, 2023, 'HIJ123', 'Active', 9, 95.00),
(16, 'Mercedes-Benz', 'E-Class', 5, 2022, 'KLM789', 'Active', 9, 100.00),
(17, 'Toyota', 'Highlander', 7, 2022, 'NOP456', 'Rented', 10, 85.00),
(18, 'Toyota', 'RAV4', 5, 2023, 'QRS789', 'Active', 10, 75.00),
(19, 'Tesla', 'Model X', 7, 2023, 'TUV123', 'Active', 1, 120.00),
(20, 'Ford', 'Mustang', 4, 2021, 'UVW456', 'Active', 2, 90.00);


INSERT INTO reservation (reservation_id, customer_id, car_id, reservation_date, pickup_date, return_date, total_amount) VALUES
(1, 1, 1, '2024-12-01', '2024-12-05', '2024-12-10', 225.0),
(2, 2, 2, '2024-12-02', '2024-12-07', '2024-12-15', 400.0),
(3, 3, 3, '2024-11-30', '2024-12-01', '2024-12-05', 192.0),
(4, 4, 6, '2024-12-04', '2024-12-10', '2024-12-15', 200.0),
(5, 5, 7, '2024-12-05', '2024-12-08', '2024-12-12', 220.0),
(6, 6, 8, '2024-12-06', '2024-12-07', '2024-12-14', 350.0),
(7, 7, 9, '2024-12-07', '2024-12-08', '2024-12-15', 420.0),
(8, 8, 10, '2024-12-08', '2024-12-09', '2024-12-16', 560.0),
(9, 9, 11, '2024-12-09', '2024-12-10', '2024-12-17', 455.0),
(10, 10, 12, '2024-12-10', '2024-12-11', '2024-12-18', 490.0);



-- Foreign Key Constraints
ALTER TABLE car
ADD CONSTRAINT FK_car_office FOREIGN KEY (office_id) REFERENCES office (office_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservation_user FOREIGN KEY (customer_id) REFERENCES `user` (customer_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservations_car FOREIGN KEY (car_id) REFERENCES car (car_id) ON DELETE CASCADE ON UPDATE CASCADE;
