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
CREATE TABLE users (
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
    reservation_date DATETIME NOT NULL,
    pickup_date DATETIME NOT NULL,
    return_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    CONSTRAINT chk_reservation_dates CHECK (reservation_date < return_date)
);

-- Create payment table
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATETIME NOT NULL
);

-- Insert data into office table
INSERT INTO office (office_name, location) VALUES
('Downtown Office', 'Downtown City Center'),
('Airport Office', 'International Airport'),
('Suburban Office', 'Suburban Mall'),
('Central Office', 'City Center Plaza'),
('Seaside Office', 'Coastal Area'),
('Mountain Office', 'Mountain Region'),
('Tech Hub Office', 'Technology Park'),
('Old Town Office', 'Historic District'),
('Luxury Office', 'Luxury Estates'),
('Countryside Office', 'Rural Area');

-- Insert data into users table
INSERT INTO users (first_name, last_name, email, phone_number, address, username, password, user_type) VALUES
('John', 'Doe', 'johndoe@example.com', '1234567890', '123 Main St, Downtown City Center', 'johndoe', 'password123', 'C'),
('Jane', 'Smith', 'janesmith@example.com', '0987654321', '456 Oak Rd, Suburban Mall', 'janesmith', 'securepassword', 'C'),
('Admin', 'Smith', 'adminsmith@example.com', '8889990000', 'Admin HQ, City Center Plaza', 'adminsmith', 'adminpassword', 'A');

-- Insert data into car table
INSERT INTO car (make, model, no_of_seats, year, plate_number, status, office_id, day_rate) VALUES
('Toyota', 'Corolla', 5, 2021, 'ABC123', 'Active', 1, 45.00),
('Tesla', 'Model 3', 5, 2023, 'TES123', 'Active', 4, 90.00);

-- Insert data into reservation table
INSERT INTO reservation (customer_id, car_id, reservation_date, pickup_date, return_date, total_amount) VALUES
(1, 1, '2024-12-01 10:00:00', '2024-12-05 09:00:00', '2024-12-10 18:00:00', 225.00);

-- Insert data into payment table
INSERT INTO payment (reservation_id, amount, payment_date) VALUES
(1, 225.00, '2024-12-01 10:00:00');

-- Foreign Key Constraints
ALTER TABLE car
ADD CONSTRAINT FK_car_office FOREIGN KEY (office_id) REFERENCES office (office_id) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservation_user FOREIGN KEY (customer_id) REFERENCES users (customer_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE reservation
ADD CONSTRAINT FK_reservations_car FOREIGN KEY (car_id) REFERENCES car (car_id) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE payment
ADD CONSTRAINT FK_payment_reservation FOREIGN KEY (reservation_id) REFERENCES reservation (reservation_id) ON DELETE CASCADE ON UPDATE CASCADE;
