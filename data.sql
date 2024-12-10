USE Car_rental_system;

-- Insert data into office table
INSERT INTO office (office_id, office_name, location)
VALUES
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

-- Insert data into user table (default user_type = 'C' for customers)
INSERT INTO user (customer_id, first_name, last_name, email, phone_number, address, username, password)
VALUES
(1, 'John', 'Doe', 'johndoe@example.com', '1234567890', '123 Main St', 'johndoe', 'password123'),
(2, 'Jane', 'Smith', 'janesmith@example.com', '0987654321', '456 Oak Rd', 'janesmith', 'securepassword'),
(3, 'Alice', 'Johnson', 'alicej@example.com', '1112223333', '789 Pine Ave', 'alicej', 'mypassword'),
(4, 'Bob', 'Brown', 'bobb@example.com', '4445556666', '100 Sunset Blvd', 'bobb', 'password456'),
(5, 'Emma', 'Williams', 'emmaw@example.com', '7778889999', '200 Ocean Dr', 'emmaw', 'secure123'),
(6, 'Oliver', 'Martinez', 'oliverm@example.com', '3332221110', '300 Maple Rd', 'oliverm', 'password789'),
(7, 'Lily', 'Anderson', 'lilya@example.com', '6665554443', '500 Birch Lane', 'lilya', 'passsecure'),
(8, 'Lucas', 'Walker', 'lucasw@example.com', '9998887776', '700 Willow Blvd', 'lucasw', 'lucaspass'),
(9, 'Sophia', 'Davis', 'sophiad@example.com', '9988776655', '88 Ocean Ave', 'sophiad', 'sophiapass'),
(10, 'Liam', 'Wilson', 'liamw@example.com', '8877665544', '99 Highland St', 'liamw', 'liamsecure');

-- Insert admin users explicitly specifying user_type = 'A'
INSERT INTO user (customer_id, first_name, last_name, email, phone_number, address, username, password, user_type)
VALUES
(11, 'Admin', 'Smith', 'adminsmith@example.com', '8889990000', 'Admin HQ', 'adminsmith', 'adminpassword', 'A'),
(12, 'Admin', 'Brown', 'adminbrown@example.com', '7776665555', 'Admin Plaza', 'adminbrown', 'adminsecure', 'A'),
(13, 'Ethan', 'Harris', 'ethanh@example.com', '5554443332', 'Admin Office', 'ethanh', 'ethanpass', 'A');

-- Insert data into car table
INSERT INTO car (car_id, model, year, plate_number, status, office_id)
VALUES
(1, 'Toyota Corolla', 2020, 'ABC123', 'Active', 1),
(2, 'Honda Civic', 2019, 'XYZ789', 'Active', 2),
(3, 'Ford Focus', 2021, 'LMN456', 'Rented', 1),
(4, 'BMW X5', 2022, 'DEF789', 'Out of Service', 3),
(5, 'Tesla Model 3', 2023, 'TES123', 'Active', 2),
(6, 'Chevrolet Spark', 2021, 'GHJ567', 'Rented', 3),
(7, 'Hyundai Sonata', 2020, 'JKL890', 'Out of Service', 4),
(8, 'Kia Rio', 2019, 'MNO123', 'Active', 4),
(9, 'Nissan Altima', 2022, 'PQR456', 'Rented', 5),
(10, 'Audi A4', 2023, 'STU789', 'Active', 5),
(11, 'Volkswagen Passat', 2021, 'VWX456', 'Active', 6),
(12, 'Mazda CX-5', 2020, 'YZA123', 'Out of Service', 7),
(13, 'Subaru Outback', 2019, 'BCD789', 'Rented', 8),
(14, 'Jeep Wrangler', 2022, 'EFG456', 'Active', 9),
(15, 'Mercedes-Benz C-Class', 2023, 'HIJ123', 'Active', 10);

-- Insert data into reservation table
INSERT INTO reservation (reservation_id, customer_id, car_id, reservation_date, pickup_date, return_date, total_amount)
VALUES
(1, 1, 1, '2024-12-01 10:00:00', '2024-12-05 09:00:00', '2024-12-10 18:00:00', 250.00),
(2, 2, 2, '2024-12-02 14:00:00', '2024-12-07 10:00:00', '2024-12-15 20:00:00', 300.00),
(3, 3, 3, '2024-11-30 08:00:00', '2024-12-01 12:00:00', '2024-12-05 16:00:00', 150.00),
(4, 4, 6, '2024-12-04 09:00:00', '2024-12-10 12:00:00', '2024-12-15 15:00:00', 180.00),
(5, 5, 7, '2024-12-05 11:00:00', '2024-12-08 14:00:00', '2024-12-12 18:00:00', 220.00),
(6, 6, 8, '2024-12-06 10:00:00', '2024-12-07 09:00:00', '2024-12-14 18:00:00', 300.00),
(7, 7, 9, '2024-12-07 11:00:00', '2024-12-08 08:00:00', '2024-12-15 19:00:00', 320.00),
(8, 8, 10, '2024-12-08 09:00:00', '2024-12-09 10:00:00', '2024-12-16 20:00:00', 350.00),
(9, 9, 11, '2024-12-09 12:00:00', '2024-12-10 13:00:00', '2024-12-17 21:00:00', 400.00),
(10, 10, 12, '2024-12-10 14:00:00', '2024-12-11 15:00:00', '2024-12-18 22:00:00', 450.00);

-- Insert data into payment table
INSERT INTO payment (payment_id, reservation_id, amount, payment_date)
VALUES
(1, 1, 250.00, '2024-12-01 11:00:00'),
(2, 2, 300.00, '2024-12-02 15:00:00'),
(3, 3, 150.00, '2024-11-30 09:00:00'),
(4, 4, 180.00, '2024-12-04 10:00:00'),
(5, 5, 220.00, '2024-12-05 12:00:00'),
(6, 6, 300.00, '2024-12-06 12:00:00'),
(7, 7, 320.00, '2024-12-07 13:00:00'),
(8, 8, 350.00, '2024-12-08 14:00:00'),
(9, 9, 400.00, '2024-12-09 15:00:00'),
(10, 10, 450.00, '2024-12-10 16:00:00');