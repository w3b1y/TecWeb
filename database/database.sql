DROP TABLE IF EXISTS offers CASCADE;
DROP TABLE IF EXISTS news CASCADE;
DROP TABLE IF EXISTS ticket CASCADE;
DROP TABLE IF EXISTS route_schedule CASCADE;
DROP TABLE IF EXISTS train CASCADE;
DROP TABLE IF EXISTS route_station CASCADE;
DROP TABLE IF EXISTS route CASCADE;
DROP TABLE IF EXISTS station CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS user CASCADE;


CREATE TABLE user(
  id INT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(40) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  email VARCHAR(40) NOT NULL UNIQUE,
  password VARCHAR(50) NOT NULL,
  birthday DATE NOT NULL
);
CREATE TABLE admin(
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(40) NOT NULL,
  password VARCHAR(50) NOT NULL
);

CREATE TABLE station(
  name VARCHAR(40) PRIMARY KEY  NOT NULL,
  address VARCHAR(100) NOT NULL
);

CREATE TABLE route(
  id INT PRIMARY KEY AUTO_INCREMENT,
  duration TIME NOT NULL,
  name VARCHAR(40) NOT NULL
);
CREATE TABLE route_station(
  id INT PRIMARY KEY AUTO_INCREMENT,
  route_id INT NOT NULL,
  station_id VARCHAR(40) NOT NULL,
  duration TIME NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  order_number INT NOT NULL,
  FOREIGN KEY (route_id) REFERENCES route(id),
  FOREIGN KEY (station_id) REFERENCES station(name)
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
  user_id INT,
  route_schedule_id INT NOT NULL,
  departure_station_id VARCHAR(40) NOT NULL,
  arrival_station_id VARCHAR(40) NOT NULL,
  departure_time DATETIME NOT NULL,
  category INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (route_schedule_id) REFERENCES route_schedule(id),
  FOREIGN KEY (departure_station_id) REFERENCES station(name),
  FOREIGN KEY (arrival_station_id) REFERENCES station(name)
);

CREATE TABLE news(
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(150) NOT NULL,
  content TEXT NOT NULL,
  initial_date DATE NOT NULL,
  final_date DATE NOT NULL
);

CREATE TABLE offers(
  id INT PRIMARY KEY AUTO_INCREMENT,
  class VARCHAR (40) NOT NULL,
  nome VARCHAR(40) NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  discount_code VARCHAR(30) NOT NULL,
  final_date DATE NOT NULL,
  discount INT NOT NULL,
  people_number INT
);

-- Users
INSERT INTO user (first_name, last_name, email, password, birthday) VALUES
('user', 'user', 'user@user.com', 'user', '2001-02-15'),
('John', 'Doe', 'john.doe@example.com', 'password123', '2001-02-15'),
('Jane', 'Smith', 'jane.smith@example.com', 'securepass', '2004-04-15'),
('Alice', 'Johnson', 'alice.johnson@example.com', 'passalice123', '1995-07-21'),
('Bob', 'Miller', 'bob.miller@example.com', 'bobpass456', '1988-11-30'),
('Eva', 'Clark', 'eva.clark@example.com', 'evapassword789', '1992-04-05'),
('David', 'Taylor', 'david.taylor@example.com', 'davidpass321', '1985-09-15'),
('Sophia', 'Roberts', 'sophia.roberts@example.com', 'sophiapass777', '1998-02-20'),
('Michael', 'White', 'michael.white@example.com', 'mikepass111', '1982-06-12'),
('Olivia', 'Anderson', 'olivia.anderson@example.com', 'oliviapass222', '1990-08-25'),
('Henry', 'Brown', 'henry.brown@example.com', 'henrypass999', '1987-03-08'),
('Emma', 'Garcia', 'emma.garcia@example.com', 'emmapass444', '1993-12-01'),
('Liam', 'Smith', 'liam.smith@example.com', 'liampass555', '1996-10-18');

-- Admins
INSERT INTO admin (email, password) VALUES
('admin@admin.com', 'admin'),
('admin1@iberu.com', 'adminpass'),
('admin2@iberu.com', 'admin123');

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

-- Routes
INSERT INTO route (duration, name) VALUES
('03:30:00', 'Roma-Milano'),
('03:30:00', 'Milano-Roma'),
('02:45:00', 'Napoli-Torino'),
('02:45:00', 'Torino-Napoli'),
('04:15:00', 'Palermo-Genova'),
('04:15:00', 'Genova-Palermo'),
('01:30:00', 'Bologna-Firenze'),
('01:30:00', 'Firenze-Bologna'),
('02:00:00', 'Ancona-Cagliari'),
('02:00:00', 'Cagliari-Ancona');

-- Route Stations
INSERT INTO route_station (route_id, station_id, duration, price, order_number) VALUES
(1, 'Roma', '00:00:00', 30, 1),
(1, 'Firenze', '02:00:00', 22, 2),
(1, 'Bologna', '03:30:00', 15, 3),
(1, 'Milano', '05:00:00', 0, 4),
(2, 'Milano', '00:00:00', 30, 1),
(2, 'Bologna', '01:30:00', 22, 2),
(2, 'Firenze', '03:30:00', 15, 3),
(2, 'Roma', '05:00:00', 0, 4),
(3, 'Napoli', '00:00:00', 30, 1),
(3, 'Roma', '02:00:00', 25, 2),
(3, 'Firenze', '04:30:00', 20, 3),
(3, 'Bologna', '06:30:00', 15, 4),
(3, 'Milano', '08:30:00', 10, 5),
(3, 'Torino', '10:30:00', 0, 6),
(4, 'Torino', '00:00:00', 30, 1),
(4, 'Milano', '02:00:00', 25, 2),
(4, 'Bologna', '04:00:00', 20, 3),
(4, 'Firenze', '06:00:00', 15, 4),
(4, 'Roma', '08:30:00', 10, 5),
(4, 'Napoli', '10:30:00', 0, 6),
(5, 'Palermo', '00:00:00', 30, 1),
(5, 'Catanzaro', '02:00:00', 25, 2),
(5, 'Bari', '04:30:00', 20, 3),
(5, 'Cagliari', '06:30:00', 15, 4),
(5, 'Ancona', '08:30:00', 10, 5),
(5, 'Genova', '10:30:00', 0, 6),
(6, 'Genova', '00:00:00', 30, 1),
(6, 'Ancona', '02:00:00', 25, 2),
(6, 'Cagliari', '04:00:00', 20, 3),
(6, 'Bari', '06:00:00', 15, 4),
(6, 'Catanzaro', '08:30:00', 10, 5),
(6, 'Palermo', '10:30:00', 0, 6),
(7, 'Bologna', '00:00:00', 8, 1),
(7, 'Firenze', '01:30:00', 0, 2),
(8, 'Firenze', '00:00:00', 8, 1),
(8, 'Bologna', '01:30:00', 0, 2),
(9, 'Ancona', '00:00:00', 30, 1),
(9, 'Bologna', '01:30:00', 25, 2),
(9, 'Roma', '03:30:00', 15, 3),
(9, 'Cagliari', '05:30:00', 0, 4),
(10, 'Cagliari', '00:00:00', 30, 1),
(10, 'Roma', '01:30:00', 25, 2),
(10, 'Bologna', '03:30:00', 15, 3),
(10, 'Ancona', '05:30:00', 0, 4);

-- Trains
INSERT INTO train (name, capacity) VALUES
('Fulmine Argento', 200),
('Stellare Express', 200),
('Locomotiva Sognante', 150),
('Veloce Nebuloso', 150),
('Turbo Cosmico', 250),
('Arcobaleno Volante', 250),
('Astronave Velocissima', 180),
('Incanto Ferroviario', 180),
('Velocità Celeste', 220),
('Eclissi Ferroviaria', 190);

-- Route Schedules
INSERT INTO route_schedule (route_id, train_id, departure_time) VALUES
(1, 1, '08:00:00'),
(1, 2, '09:00:00'),
(1, 2, '12:00:00'),
(1, 3, '14:30:00'),
(1, 4, '17:00:00'),
(2, 5, '09:30:00'),
(2, 6, '11:00:00'),
(2, 7, '14:00:00'),
(2, 8, '16:30:00'),
(2, 9, '18:00:00'),
(3, 1, '10:30:00'),
(3, 2, '13:00:00'),
(3, 3, '15:30:00'),
(3, 4, '18:00:00'),
(3, 5, '20:00:00'),
(4, 9, '09:45:00'),
(4, 10, '11:30:00'),
(4, 1, '14:00:00'),
(4, 2, '17:30:00'),
(4, 3, '20:00:00'),
(5, 3, '08:30:00'),
(5, 4, '10:00:00'),
(5, 5, '13:00:00'),
(5, 6, '15:30:00'),
(5, 7, '18:00:00'),
(6, 8, '09:15:00'),
(6, 9, '11:30:00'),
(6, 10, '14:00:00'),
(6, 1, '16:30:00'),
(6, 2, '19:00:00'),
(7, 5, '08:45:00'),
(7, 6, '10:30:00'),
(7, 7, '13:00:00'),
(7, 8, '15:30:00'),
(7, 9, '17:45:00'),
(8, 10, '09:00:00'),
(8, 1, '11:00:00'),
(8, 2, '14:00:00'),
(8, 3, '16:30:00'),
(8, 4, '19:00:00'),
(9, 5, '10:00:00'),
(9, 6, '12:30:00'),
(9, 7, '15:00:00'),
(9, 8, '17:30:00'),
(9, 9, '20:00:00'),
(10, 10, '11:00:00'),
(10, 1, '13:30:00'),
(10, 2, '16:00:00'),
(10, 3, '18:30:00'),
(10, 4, '21:00:00');

-- Tickets
INSERT INTO ticket(user_id, route_schedule_id, departure_station_id, arrival_station_id, departure_time, category) VALUES
(1, 1, 'Roma', 'Milano', '2024-01-15', 1),
(1, 1, 'Bologna', 'Milano', '2024-01-16', 1),
(1, 1, 'Roma', 'Milano', '2024-02-15', 1),
(2, 2, 'Roma', 'Milano', '2024-03-15', 1);

-- News
INSERT INTO news(id, title, content, initial_date, final_date) VALUES
(1, 'Miglioramenti Infrastrutturali: Nuovi treni veloci sulla tratta Roma - Firenze', 'Siamo lieti di annunciare l''introduzione di nuovi treni veloci sulla tratta Roma - Firenze. I viaggiatori beneficeranno di tempi di percorrenza più brevi e servizi migliorati. Il nuovo servizio entrerà in funzione a partire dal giorno #i.', '2024-03-01', '2024-03-31'),
(2, 'Soppressione tratta Roma - Napoli', 'La tratta Roma - Napoli sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno 
      #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-01-15', '2024-01-17'),
(3, 'Soppressione tratta Milano - Torino', 'La tratta Milano - Torino sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-04-12', '2024-04-16'),
(4, 'Soppressione tratta Roma - Milano', 'La tratta Roma - Milano sarà soppressa, causa lavori alla linea ferroviaria, a partire dal giorno #i fino al giorno #f. Ci scusiamo per il disagio.', '2024-01-15', '2024-01-17'),
(5, 'Sospensione temporanea servizio Firenze - Napoli', 'Il servizio ferroviario tra Firenze e Napoli sarà temporaneamente sospeso per lavori sulla linea. La sospensione avrà luogo dal giorno #i al giorno #f. Ci scusiamo per l''inconveniente.', '2024-03-05', '2024-03-10'),
(6, 'Nuova tratta diretta Bologna - Venezia', 'Siamo lieti di annunciare l''apertura di una nuova tratta diretta tra Bologna e Venezia, che inizierà a operare dal giorno #i. Migliorerà la connettività tra le due importanti città italiane.', '2024-02-20', '2024-02-28'),
(7, 'Modifiche orari servizio Genova - Palermo', 'Si avvisano gli utenti che ci saranno modifiche agli orari del servizio ferroviario tra Genova e Palermo a partire dal giorno #i per ottimizzare la gestione delle corse. Si prega di verificare gli orari aggiornati.', '2024-05-01', '2024-05-05'),
(8, 'Sciopero del Personale: Sospensione temporanea di treni sulla tratta Napoli - Bari', 'A causa di uno sciopero del personale ferroviario, alcuni treni sulla tratta Napoli - Bari saranno sospesi a partire dal giorno #i fino al giorno #f. Gli utenti sono invitati a pianificare i propri viaggi di conseguenza.', '2024-03-20', '2024-03-22'),
(9, 'Caduta di Alberi: Interruzione del servizio sulla tratta Genova - Milano', 'A causa della caduta di alberi sulla linea ferroviaria, il servizio tra Genova e Milano è temporaneamente interrotto. I tecnici sono al lavoro per ripristinare la normale operatività. Si prevede che la situazione sarà risolta entro il giorno #f.', '2024-04-05', '2024-04-06'),
(10, 'Forti Ritardi: Attesa prolungata su diverse tratte ferroviarie', 'A causa di problemi tecnici sulla rete ferroviaria, si verificano ritardi significativi su diverse tratte, inclusi percorsi tra Milano, Roma e Firenze. I viaggiatori sono invitati a consultare gli annunci in stazione per informazioni aggiornate.', '2024-02-10', '2024-02-11'),
(11, 'Incidente Ferroviario: Interruzione del servizio sulla tratta Torino - Venezia', 'A seguito di un incidente ferroviario sulla tratta Torino - Venezia, il servizio è temporaneamente interrotto. I passeggeri sono invitati a considerare alternative di viaggio. Le autorità stanno indagando sull''incidente.', '2024-05-15', '2024-05-17');

-- Offers
INSERT INTO offers(class, nome, title, content, discount_code, final_date, discount, people_number) VALUES
('super', 'christmas-gift', 'Celebra il Natale con stile! Risparmia il 15% su tutte le prenotazioni.', 'Regalati una vacanza indimenticabile con noi!', 'ABC1234', '2023-12-25', 15, NULL),
('super', 'new-year', 'Dai il benvenuto al nuovo anno con un''offerta speciale!', 'Prenota entro il 27 dicembre 2023 e risparmia il 20% su tutte le destinazioni. Inizia il 2024 con una vacanza da sogno!', 'DEF5678', '2024-12-27', 20, NULL),
('special', 'love', 'Vivi momenti romantici con le nostre Offerte di Coppia! Sconto del 5%', 'Prenota per il tuo anniversario o una fuga romantica entro il 14 febbraio e regalati un viaggio indimenticabile con la tua dolce metà!', 'GHI9012', '2024-02-14', 5, NULL),
('special', 'young', 'Esplora il mondo con le nostre Offerte Speciali per i Giovani!', 'Sconto imperdibile del 10%, riservato ai viaggiatori under 25. Dai il via alla tua prossima avventura con servizi esclusivi e risparmia mentre crei ricordi indimenticabili!', 'JKL3456', '2024-06-01', 10, NULL),
('special', 'old', 'Esplora il mondo con le nostre Offerte Speciali per i Senior!', 'Sconto speciale del 15%, dedicato ai viaggiatori sopra i 60 anni. Approfitta dei vantaggi e riscopri il lato meraviglioso del viaggio.', 'MNO7890', '2024-12-31', 15, NULL),
('group', 'family', 'Offerta Famiglia: un''avventura insieme!', 'Pacchetto per due genitori e due bambini sotto i 10 anni con sconto speciale del 15%. Perfetto per creare ricordi indimenticabili per tutta la famiglia!', 'PQR1234', '2024-05-31', 15, 4),
('group', 'group', 'Offerta Gruppi: più si è, più si risparmia!', 'Prenota per un gruppo di oltre 8 persone e approfitta di uno sconto esclusivo del 20%. Rendete il vostro viaggio un''esperienza straordinaria!', 'STU5678', '2024-12-31', 20, 8),
('group', 'school', 'Offerta Scolastica: Un''esperienza educativa straordinaria!', 'Prenota ora per la tua classe, con due professori e oltre 20 alunni, e ricevi uno sconto esclusivo del 30%. Viaggiate e imparate insieme a tariffe speciali!', 'VWX9012', '2024-06-10', 30, 22);
-- aggiungere class student
