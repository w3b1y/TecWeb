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
  nome VARCHAR(40) NOT NULL,
  title TEXT NOT NULL,
  content TEXT,
  discount_code VARCHAR(30) NOT NULL,
  final_date DATE NOT NULL
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
('Roma', 'Via Giovanni Giolitti, 40, 00185 Roma RM'),
('Milano', 'Via della Moscova, 13, 20121 Milano MI'),
('Napoli', 'Via Toledo, 156, 80132 Napoli NA'),
('Torino', 'Via Po, 14, 10123 Torino TO'),
('Palermo', 'Via Vittorio Emanuele, 351, 90134 Palermo PA'),
('Genova', 'Piazza Raffaele De Ferrari, 1, 16123 Genova GE'),
('Bologna', 'Piazza Medaglie d''Oro, 2, 40124 Bologna BO'),
('Firenze', 'Piazzale della Stazione, 50122 Firenze FI'),
('Ancona', 'Corso Stamira, 10, 60123 Ancona AN'),
('Cagliari', 'Via Sassari, 7A, 09123 Cagliari CA'),
('Bari', 'Piazza Aldo Moro, 70123 Bari BA'),
('Catanzaro', 'Via T. Campanella, 56, 88100 Catanzaro CZ'),
('Trieste', 'Piazza della Libertà, 11, 34135 Trieste TS'),
('Potenza', 'Via Pretoria, 46, 85100 Potenza PZ'),
('Campobasso', 'Via San Giorgio, 21, 86100 Campobasso CB'),
('Aosta', 'Piazza Chanoux, 9, 11100 Aosta AO'),
('Perugia', 'Corso Vannucci, 97, 06121 Perugia PG'),
('Aquila', 'Piazzale della Stazione, 67100 L''Aquila AQ');


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
      #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-01-15', '2024-01-17'),
(3, 'Soppressione tratta Milano - Torino', 'La tratta Milano - Torino sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-04-12', '2024-04-16'),
(4, 'Soppressione tratta Roma - Milano', 'La tratta Roma - Milano sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-01-15', '2024-01-17'),
(5, 'Sospensione temporanea servizio Firenze - Napoli', 'Il servizio ferroviario tra Firenze e Napoli sarà temporaneamente sospeso per lavori sulla linea. La sospensione avrà luogo dal giorno #i al giorno #f. Ci scusiamo per l''inconveniente.', '2024-03-05', '2024-03-10'),
(6, 'Nuova tratta diretta Bologna - Venezia', 'Siamo lieti di annunciare l''apertura di una nuova tratta diretta tra Bologna e Venezia, che inizierà a operare dal giorno #i. Migliorerà la connettività tra le due importanti città italiane.', '2024-02-20', NULL),
(7, 'Modifiche orari servizio Genova - Palermo', 'Si avvisano gli utenti che ci saranno modifiche agli orari del servizio ferroviario tra Genova e Palermo a partire dal giorno #i per ottimizzare la gestione delle corse. Si prega di verificare gli orari aggiornati.', '2024-05-01', '2024-05-05'),
(8, 'Sciopero del Personale: Sospensione temporanea di treni sulla tratta Napoli - Bari', 'A causa di uno sciopero del personale ferroviario, alcuni treni sulla tratta Napoli - Bari saranno sospesi a partire dal giorno #i fino al giorno #f. Gli utenti sono invitati a pianificare i propri viaggi di conseguenza.', '2024-03-20', '2024-03-22'),
(9, 'Caduta di Alberi: Interruzione del servizio sulla tratta Genova - Milano', 'A causa della caduta di alberi sulla linea ferroviaria, il servizio tra Genova e Milano è temporaneamente interrotto. I tecnici sono al lavoro per ripristinare la normale operatività. Si prevede che la situazione sarà risolta entro il giorno #f.', '2024-04-05', '2024-04-06'),
(10, 'Forti Ritardi: Attesa prolungata su diverse tratte ferroviarie', 'A causa di problemi tecnici sulla rete ferroviaria, si verificano ritardi significativi su diverse tratte, inclusi percorsi tra Milano, Roma e Firenze. I viaggiatori sono invitati a consultare gli annunci in stazione per informazioni aggiornate.', '2024-02-10', '2024-02-11'),
(11, 'Incidente Ferroviario: Interruzione del servizio sulla tratta Torino - Venezia', 'A seguito di un incidente ferroviario sulla tratta Torino - Venezia, il servizio è temporaneamente interrotto. I passeggeri sono invitati a considerare alternative di viaggio. Le autorità stanno indagando sull''incidente.', '2024-05-15', NULL),
(12, 'Miglioramenti Infrastrutturali: Nuovi treni veloci sulla tratta Roma - Firenze', 'Siamo lieti di annunciare l''introduzione di nuovi treni veloci sulla tratta Roma - Firenze. I viaggiatori beneficeranno di tempi di percorrenza più brevi e servizi migliorati. Il nuovo servizio entrerà in funzione a partire dal giorno #i.', '2024-03-01', NULL);


-- Offers
INSERT INTO offers(class, nome, title, content, discount_code, final_date) VALUES
('super', 'christmas-gift', 'Celebra il Natale con stile! Risparmia il 15% su tutte le prenotazioni.', 'Regalati una vacanza indimenticabile con noi!', 'ABC1234', '2023-12-25'),
('super', 'new-year', 'Dai il benvenuto al nuovo anno con un''offerta speciale!', 'Prenota entro il 27 dicembre 2023 e risparmia il 20% su tutte le destinazioni. Inizia il 2024 con una vacanza da sogno!', 'DEF5678', '2024-12-27'),
('special', 'love', 'Vivi momenti romantici con le nostre Offerte di Coppia! Sconto del 5%', 'Prenota per il tuo anniversario o una fuga romantica entro il 14 febbraio e regalati un viaggio indimenticabile con la tua dolce metà!', 'GHI9012', '2024-02-14'),
('special', 'young', 'Esplora il mondo con le nostre Offerte Speciali per i Giovani!', 'Sconto imperdibile del 10%, riservato ai viaggiatori under 25. Dai il via alla tua prossima avventura con servizi esclusivi e risparmia mentre crei ricordi indimenticabili!', 'JKL3456', '2024-06-01'),
('special', 'old', 'Esplora il mondo con le nostre Offerte Speciali per i Senior!', 'Sconto speciale del 15%, dedicato ai viaggiatori sopra i 60 anni. Approfitta dei vantaggi e riscopri il lato meraviglioso del viaggio.', 'MNO7890', '2024-12-31'),
('groups', 'family', 'Offerta Famiglia: un''avventura insieme!', 'Pacchetto per due genitori e due bambini sotto i 10 anni con sconto speciale del 15%. Perfetto per creare ricordi indimenticabili per tutta la famiglia!', 'PQR1234', '2024-05-31'),
('groups', 'group', 'Offerta Gruppi: più si è, più si risparmia!', 'Prenota per un gruppo di oltre 8 persone e approfitta di uno sconto esclusivo del 20%. Rendete il vostro viaggio un''esperienza straordinaria!', 'STU5678', '2024-12-31'),
('groups', 'school', 'Offerta Scolastica: Un''esperienza educativa straordinaria!', 'Prenota ora per la tua classe, con due professori e oltre 20 alunni, e ricevi uno sconto esclusivo del 30%. Viaggiate e imparate insieme a tariffe speciali!', 'VWX9012', '2024-06-10'),
('carnet', 'five', 'Esplora senza limiti con il nostro Carnet da 5 Viaggi! Acquista ora e risparmia 5%.', 'Viaggia quando vuoi, come vuoi, con tariffe bloccate e servizi esclusivi. Un''offerta imperdibile per gli amanti dell''avventura!', 'YZA2345', '2024-12-31'),
('carnet', 'ten', 'Esplora senza limiti con il nostro Carnet da 10 Viaggi! Acquista ora e risparmia 12%.', 'Viaggia quando vuoi, come vuoi, con tariffe bloccate e servizi esclusivi. Un''offerta imperdibile per gli amanti dell''avventura!', 'BCD6789', '2024-12-31'),
('carnet', 'fifteen', 'Esplora senza limiti con il nostro Carnet da 15 Viaggi! Acquista ora e risparmia 20%.', 'Viaggia quando vuoi, come vuoi, con tariffe bloccate e servizi esclusivi. Un''offerta imperdibile per gli amanti dell''avventura!', 'EFG0123', '2024-12-31');
