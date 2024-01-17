DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS user_subscription CASCADE;
DROP TABLE IF EXISTS subscription CASCADE;
DROP TABLE IF EXISTS ticket CASCADE;
DROP TABLE IF EXISTS route CASCADE;
DROP TABLE IF EXISTS route_category CASCADE;
DROP TABLE IF EXISTS route_station CASCADE;
DROP TABLE IF EXISTS station CASCADE;
DROP TABLE IF EXISTS route_schedule CASCADE;
DROP TABLE IF EXISTS train CASCADE;
DROP TABLE IF EXISTS news CASCADE;
DROP TABLE IF EXISTS offers CASCADE;
DROP TABLE IF EXISTS user CASCADE;


CREATE TABLE user(
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(40) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  email VARCHAR(40) NOT NULL,
  password VARCHAR(50) NOT NULL,
  phone_number VARCHAR(12) NOT NULL
);
CREATE TABLE admin(
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(50) NOT NULL
);
CREATE TABLE subscription(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  price DECIMAL(10,2) NOT NULL
);
CREATE TABLE user_subscription(
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  subscription_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (subscription_id) REFERENCES subscription(id)
);
CREATE TABLE station(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  address VARCHAR(40) NOT NULL
);

CREATE TABLE route(
  id INT PRIMARY KEY AUTO_INCREMENT,
  duration TIME NOT NULL,
  distance DECIMAL(10,2) NOT NULL,
  name VARCHAR(40) NOT NULL
);
CREATE TABLE route_category(
  id INT PRIMARY KEY AUTO_INCREMENT,
  route_id INT NOT NULL,
  subscription_id INT NOT NULL,
  FOREIGN KEY (route_id) REFERENCES route(id),
  FOREIGN KEY (subscription_id) REFERENCES subscription(id)
);
CREATE TABLE route_station(
  id INT PRIMARY KEY AUTO_INCREMENT,
  route_id INT NOT NULL,
  station_id INT NOT NULL,
  duration TIME NOT NULL,
  distance DECIMAL(10,2) NOT NULL,
  order_number INT NOT NULL,
  FOREIGN KEY (route_id) REFERENCES route(id),
  FOREIGN KEY (station_id) REFERENCES station(id)
);

CREATE TABLE train(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  capacity INT NOT NULL
);
CREATE TABLE route_schedule(
  id INT PRIMARY KEY AUTO_INCREMENT,
  route_id INT NOT NULL,
  train_id INT NOT NULL,
  departure_time TIME NOT NULL,
  FOREIGN KEY (route_id) REFERENCES route(id),
  FOREIGN KEY (train_id) REFERENCES train(id)
);
CREATE TABLE ticket(
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  route_schedule_id INT NOT NULL,
  station_id INT NOT NULL,
  seat_number INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (route_schedule_id) REFERENCES route_schedule(id),
  FOREIGN KEY (station_id) REFERENCES station(id)
);

CREATE TABLE news(
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(40) NOT NULL,
  content TEXT NOT NULL,
  initial_date DATE NOT NULL,
  final_date DATE NOT NULL
);

CREATE TABLE offers(
  id INT PRIMARY KEY AUTO_INCREMENT,
  class VARCHAR (40) NOT NULL,
  name VARCHAR(40) NOT NULL,
  body TEXT NOT NULL,
  more TEXT,
  discount_code VARCHAR(30) NOT NULL
);

-- Users
INSERT INTO user (first_name, last_name, email, password, phone_number) VALUES
('John', 'Doe', 'john.doe@example.com', 'password123', '123-456-7890'),
('Jane', 'Smith', 'jane.smith@example.com', 'securepass', '987-654-3210');

-- Admins
INSERT INTO admin (username, password) VALUES
('admin1', 'adminpass'),
('admin2', 'admin123');

-- Subscriptions
INSERT INTO subscription (name, price) VALUES
('Basic', 9.99),
('Premium', 19.99),
('Gold', 29.99);

-- Routes
INSERT INTO route (duration, distance, name) VALUES
('03:30:00', 300.50, 'Express Route'),
('02:45:00', 200.75, 'Local Route'),
('04:00:00', 400.25, 'Regional Route');

-- Stations
INSERT INTO station (name, address) VALUES
('Station A', '123 Main St'),
('Station B', '456 Elm St'),
('Station C', '789 Oak St'),
('Station D', '101 Pine St'),
('Station E', '202 Maple St'),
('Station F', '303 Cedar St'),
('Station G', '404 Birch St'),
('Station H', '505 Walnut St');

-- Trains
INSERT INTO train (name, capacity) VALUES
('Express Train', 200),
('Local Train', 150),
('Regional Train', 250);

-- User Subscriptions
INSERT INTO user_subscription (user_id, subscription_id, start_date, end_date) VALUES
(1, 1, '2024-01-01', '2024-01-31'),
(2, 2, '2024-02-15', '2024-03-15');

-- Route Categories
INSERT INTO route_category (route_id, subscription_id) VALUES
(1, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3),
(3, 3);

-- Route Schedules
INSERT INTO route_schedule (route_id, train_id, departure_time) VALUES
(1, 1, '08:00:00'),
(2, 2, '10:30:00'),
(3, 3, '12:45:00');

-- Tickets
INSERT INTO ticket (user_id, route_schedule_id, station_id, seat_number, price) VALUES
(1, 1, 1, 10, 25.00),
(2, 2, 2, 5, 30.00);

-- Route Stations
INSERT INTO route_station (route_id, station_id, duration, distance, order_number) VALUES
(1, 1, '01:30:00', 100.25, 1),
(1, 2, '02:00:00', 200.25, 2),
(1, 3, '00:45:00', 50.75, 3),
(2, 2, '01:15:00', 150.50, 1),
(2, 1, '01:30:00', 100.25, 2),
(2, 4, '00:45:00', 50.75, 3),
(3, 3, '02:00:00', 200.25, 1),
(3, 4, '01:30:00', 150.50, 2),
(3, 5, '00:45:00', 50.75, 3);

-- News
INSERT INTO news(id, title, content, initial_date, final_date) VALUES
(1, 'Soppressione tratta Padova - Bassano', ' La tratta Padova - Bassano sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno 
      #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-04-12', '2024-04-16'),
(2, 'Soppressione tratta Roma - Napoli', 'La tratta Roma - Napoli sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno 
      #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-01-15', '2024-01-17');

-- Offers
INSERT INTO offers(class, name, body, more, discount_code) VALUES
('-', 'christmas-gift', 'Con <span lang="en-US">Christmas Gifts</span> risparmi oltre il 20% nell acquisto di un nuovo abbonamento', 'Non lasciarti scappare questa offerta', 'CG20Y2023' ),
('-', 'winter-days', 'Con i <span lang="en-US">Winter Days</span> oltre il 60% di sconto', 'Scopri di più', 'WD60Y2024' ),
('super','-','Celebra il Natale con stile! Risparmia il 15% su tutte le prenotazioni.', ' Regalati una vacanza indimenticabile con noi!', '-' );