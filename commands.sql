-- Create the database
CREATE DATABASE Car_rental_system;

USE Car_rental_system;

CREATE TABLE office (
    office_id INT PRIMARY KEY,
    office_name VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL
);

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

CREATE TABLE car (
    car_id INT PRIMARY KEY,
    -- SEE HERE
    -- make VARCHAR(100) NOT NULL,
    -- no_of_seats INT NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    plate_number VARCHAR(20) UNIQUE NOT NULL,
    status VARCHAR(50) NOT NULL,
    office_id INT,
    -- FIX
    -- day_rate DECIMAL(10,2) NOT NULL
);

CREATE TABLE reservation (
    reservation_id INT PRIMARY KEY,
    customer_id INT NOT NULL,
    car_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    pickup_date DATETIME NOT NULL,
    return_date DATETIME NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL
);

CREATE TABLE payment (
    payment_id INT PRIMARY KEY,
    reservation_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATETIME NOT NULL
);

INSERT INTO
    office (office_id, office_name, location)
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

INSERT INTO
    user (
        customer_id,
        first_name,
        last_name,
        email,
        phone_number,
        address,
        username,
        password
    )
VALUES
    (
        1,
        'John',
        'Doe',
        'johndoe@example.com',
        '1234567890',
        '123 Main St',
        'johndoe',
        'password123'
    ),
    (
        2,
        'Jane',
        'Smith',
        'janesmith@example.com',
        '0987654321',
        '456 Oak Rd',
        'janesmith',
        'securepassword'
    ),
    (
        3,
        'Alice',
        'Johnson',
        'alicej@example.com',
        '1112223333',
        '789 Pine Ave',
        'alicej',
        'mypassword'
    ),
    (
        4,
        'Bob',
        'Brown',
        'bobb@example.com',
        '4445556666',
        '100 Sunset Blvd',
        'bobb',
        'password456'
    ),
    (
        5,
        'Emma',
        'Williams',
        'emmaw@example.com',
        '7778889999',
        '200 Ocean Dr',
        'emmaw',
        'secure123'
    ),
    (
        6,
        'Oliver',
        'Martinez',
        'oliverm@example.com',
        '3332221110',
        '300 Maple Rd',
        'oliverm',
        'password789'
    ),
    (
        7,
        'Lily',
        'Anderson',
        'lilya@example.com',
        '6665554443',
        '500 Birch Lane',
        'lilya',
        'passsecure'
    ),
    (
        8,
        'Lucas',
        'Walker',
        'lucasw@example.com',
        '9998887776',
        '700 Willow Blvd',
        'lucasw',
        'lucaspass'
    ),
    (
        9,
        'Sophia',
        'Davis',
        'sophiad@example.com',
        '9988776655',
        '88 Ocean Ave',
        'sophiad',
        'sophiapass'
    ),
    (
        10,
        'Liam',
        'Wilson',
        'liamw@example.com',
        '8877665544',
        '99 Highland St',
        'liamw',
        'liamsecure'
    );

INSERT INTO
    user (
        customer_id,
        first_name,
        last_name,
        email,
        phone_number,
        address,
        username,
        password,
        user_type
    )
VALUES
    (
        11,
        'Admin',
        'Smith',
        'adminsmith@example.com',
        '8889990000',
        'Admin HQ',
        'adminsmith',
        'adminpassword',
        'A'
    ),
    (
        12,
        'Admin',
        'Brown',
        'adminbrown@example.com',
        '7776665555',
        'Admin Plaza',
        'adminbrown',
        'adminsecure',
        'A'
    ),
    (
        13,
        'Ethan',
        'Harris',
        'ethanh@example.com',
        '5554443332',
        'Admin Office',
        'ethanh',
        'ethanpass',
        'A'
    );

INSERT INTO
    car (
        car_id,
        model,
        year,
        plate_number,
        status,
        office_id
    )
VALUES
    (1, 'Toyota Corolla', 2020, 'ABC123', 'Active', 1),
    (2, 'Honda Civic', 2019, 'XYZ789', 'Active', 2),
    (3, 'Ford Focus', 2021, 'LMN456', 'Rented', 1),
    (4, 'BMW X5', 2022, 'DEF789', 'Out of Service', 3),
    (5, 'Tesla Model 3', 2023, 'TES123', 'Active', 2),
    (
        6,
        'Chevrolet Spark',
        2021,
        'GHJ567',
        'Rented',
        3
    ),
    (
        7,
        'Hyundai Sonata',
        2020,
        'JKL890',
        'Out of Service',
        4
    ),
    (8, 'Kia Rio', 2019, 'MNO123', 'Active', 4),
    (9, 'Nissan Altima', 2022, 'PQR456', 'Rented', 5),
    (10, 'Audi A4', 2023, 'STU789', 'Active', 5),
    (
        11,
        'Volkswagen Passat',
        2021,
        'VWX456',
        'Active',
        6
    ),
    (
        12,
        'Mazda CX-5',
        2020,
        'YZA123',
        'Out of Service',
        7
    ),
    (
        13,
        'Subaru Outback',
        2019,
        'BCD789',
        'Rented',
        8
    ),
    (14, 'Jeep Wrangler', 2022, 'EFG456', 'Active', 9),
    (
        15,
        'Mercedes-Benz C-Class',
        2023,
        'HIJ123',
        'Active',
        10
    );

INSERT INTO
    reservation (
        reservation_id,
        customer_id,
        car_id,
        reservation_date,
        pickup_date,
        return_date,
        total_amount
    )
VALUES
    (
        1,
        1,
        1,
        '2024-12-01 10:00:00',
        '2024-12-05 09:00:00',
        '2024-12-10 18:00:00',
        250.00
    ),
    (
        2,
        2,
        2,
        '2024-12-02 14:00:00',
        '2024-12-07 10:00:00',
        '2024-12-15 20:00:00',
        300.00
    ),
    (
        3,
        3,
        3,
        '2024-11-30 08:00:00',
        '2024-12-01 12:00:00',
        '2024-12-05 16:00:00',
        150.00
    ),
    (
        4,
        4,
        6,
        '2024-12-04 09:00:00',
        '2024-12-10 12:00:00',
        '2024-12-15 15:00:00',
        180.00
    ),
    (
        5,
        5,
        7,
        '2024-12-05 11:00:00',
        '2024-12-08 14:00:00',
        '2024-12-12 18:00:00',
        220.00
    ),
    (
        6,
        6,
        8,
        '2024-12-06 10:00:00',
        '2024-12-07 09:00:00',
        '2024-12-14 18:00:00',
        300.00
    ),
    (
        7,
        7,
        9,
        '2024-12-07 11:00:00',
        '2024-12-08 08:00:00',
        '2024-12-15 19:00:00',
        320.00
    ),
    (
        8,
        8,
        10,
        '2024-12-08 09:00:00',
        '2024-12-09 10:00:00',
        '2024-12-16 20:00:00',
        350.00
    ),
    (
        9,
        9,
        11,
        '2024-12-09 12:00:00',
        '2024-12-10 13:00:00',
        '2024-12-17 21:00:00',
        400.00
    ),
    (
        10,
        10,
        12,
        '2024-12-10 14:00:00',
        '2024-12-11 15:00:00',
        '2024-12-18 22:00:00',
        450.00
    );

INSERT INTO
    payment (payment_id, reservation_id, amount, payment_date)
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

ALTER TABLE
    car
ADD
    CONSTRAINT FK_car_office FOREIGN KEY (office_id) REFERENCES office (office_id);

ALTER TABLE
    reservation
ADD
    CONSTRAINT FK_reservation_user FOREIGN KEY (customer_id) REFERENCES user (customer_id);

ALTER TABLE
    reservation
ADD
    CONSTRAINT FK_reservations_car FOREIGN KEY (car_id) REFERENCES car (car_id);

ALTER TABLE
    payment
ADD
    CONSTRAINT FK_payment_reservation FOREIGN KEY (reservation_id) REFERENCES reservation (reservation_id);

ALTER TABLE
    user
ADD
    CONSTRAINT chk_user_type CHECK (user_type IN ('C', 'A'));

ALTER TABLE
    car
ADD
    CONSTRAINT chk_car_status CHECK (status IN ('Active', 'Out of Service', 'Rented'));