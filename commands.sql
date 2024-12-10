-- Create the database
CREATE DATABASE Car_rental_system;
USE Car_rental_system;

-- Create the office table
CREATE TABLE office (
    office_id INT PRIMARY KEY,
    office_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL
);

-- Create the user table (formerly customer table)
CREATE TABLE user (
    customer_id INT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    address VARCHAR(255) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type CHAR(1) NOT NULL DEFAULT 'C' -- 'C' for customer, 'A' for admin
);

-- Add a domain constraint for user_type
ALTER TABLE user
ADD CONSTRAINT chk_user_type CHECK (user_type IN ('C', 'A'));

-- Create the car table
CREATE TABLE car (
    car_id INT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    status VARCHAR(50) NOT NULL,
    office_id INT
);

-- Add a domain constraint for car status
ALTER TABLE car
ADD CONSTRAINT chk_car_status CHECK (status IN ('Active', 'Out of Service', 'Rented'));

-- Create the reservation table
CREATE TABLE reservation (
    reservation_id INT PRIMARY KEY,
    customer_id INT NOT NULL,
    car_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    pickup_date DATETIME NOT NULL,
    return_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL
);

-- Create the payment table
CREATE TABLE payment (
    payment_id INT PRIMARY KEY,
    reservation_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATETIME NOT NULL
);

-- Add foreign key constraints
ALTER TABLE car
ADD CONSTRAINT FK_car_office FOREIGN KEY (office_id)
REFERENCES office (office_id);

ALTER TABLE reservation
ADD CONSTRAINT FK_reservation_user FOREIGN KEY (customer_id)
REFERENCES user (customer_id);

ALTER TABLE reservation
ADD CONSTRAINT FK_reservations_car FOREIGN KEY (car_id)
REFERENCES car (car_id);

ALTER TABLE payment
ADD CONSTRAINT FK_payment_reservation FOREIGN KEY (reservation_id)
REFERENCES reservation (reservation_id);