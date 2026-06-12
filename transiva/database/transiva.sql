-- Script SQL Transiva (Refonte Place de Marché Multi-Opérateurs)
-- À exécuter dans SQL Server

-- Suppression de la base si elle existe déjà pour réinitialiser proprement
IF EXISTS (SELECT name FROM sys.databases WHERE name = N'Transiva')
BEGIN
    ALTER DATABASE Transiva SET SINGLE_USER WITH ROLLBACK IMMEDIATE;
    DROP DATABASE Transiva;
END
GO

CREATE DATABASE Transiva;
GO
USE Transiva;
GO

-- Table: Users (Utilisateurs de la plateforme)
-- Note: Les variables et clés sont en anglais pour correspondre au modèle Eloquent standard de Laravel (name, email, password, role)
CREATE TABLE Users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL, -- 'Traveler', 'Operator', 'Admin'
    telephone VARCHAR(20) NULL,
    created_at DATETIME DEFAULT GETDATE()
);

-- Table: Operators (Compagnies de transport enregistrées)
CREATE TABLE Operators (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    nom_compagnie VARCHAR(100) NOT NULL,
    description TEXT NULL,
    logo_url VARCHAR(255) NULL,
    commission_rate DECIMAL(5,2) DEFAULT 10.00,
    statut VARCHAR(20) DEFAULT 'En attente', -- 'En attente', 'Valide', 'Suspendu'
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

-- Table: Vehicles (Flotte de véhicules gérée par chaque opérateur)
CREATE TABLE Vehicles (
    id INT IDENTITY(1,1) PRIMARY KEY,
    operator_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    immatriculation VARCHAR(50) NOT NULL,
    capacite INT NOT NULL,
    type VARCHAR(50) DEFAULT 'Bus', -- 'Bus', 'Minibus', 'Ferry', 'Train'
    FOREIGN KEY (operator_id) REFERENCES Operators(id) ON DELETE CASCADE
);

-- Table: Routes (Lignes / Trajets de transport définis par chaque opérateur)
CREATE TABLE Routes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    operator_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    depart VARCHAR(100) NOT NULL,
    arrivee VARCHAR(100) NOT NULL,
    duree_min INT NOT NULL,
    FOREIGN KEY (operator_id) REFERENCES Operators(id) ON DELETE CASCADE
);

-- Table: Trips (Voyages spécifiques planifiés, horaires & tarifs flexibles)
CREATE TABLE Trips (
    id INT IDENTITY(1,1) PRIMARY KEY,
    route_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    jours VARCHAR(100) NOT NULL, -- ex: 'Tous les jours', 'Lun - Ven'
    prix DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (route_id) REFERENCES Routes(id) ON DELETE NO ACTION,
    FOREIGN KEY (vehicle_id) REFERENCES Vehicles(id) ON DELETE NO ACTION
);

-- Table: Reservations (Réservations de places)
-- Note: ON DELETE NO ACTION pour éviter les cascades multiples sous SQL Server
CREATE TABLE Reservations (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    trip_id INT NOT NULL,
    date_voyage DATE NOT NULL,
    nb_places INT NOT NULL,
    sieges VARCHAR(100) NULL, -- ex: '12, 13'
    statut VARCHAR(20) DEFAULT 'En attente', -- 'En attente', 'Payee', 'Annulee'
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE NO ACTION,
    FOREIGN KEY (trip_id) REFERENCES Trips(id) ON DELETE NO ACTION
);

-- Table: Tickets (Billets numériques générés)
CREATE TABLE Tickets (
    id INT IDENTITY(1,1) PRIMARY KEY,
    reservation_id INT NOT NULL,
    code_qr VARCHAR(255) NOT NULL,
    code_reservation VARCHAR(50) UNIQUE NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    date_emission DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE
);

-- Table: Payments (Paiements mobiles ou cartes)
CREATE TABLE Payments (
    id INT IDENTITY(1,1) PRIMARY KEY,
    reservation_id INT NOT NULL,
    mode_paiement VARCHAR(50) NOT NULL, -- 'Mobile Money', 'Carte Bancaire'
    montant DECIMAL(10,2) NOT NULL,
    statut VARCHAR(20) NOT NULL, -- 'Reussi', 'Echoue'
    reference_transaction VARCHAR(100) NOT NULL,
    date_paiement DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (reservation_id) REFERENCES Reservations(id) ON DELETE CASCADE
);

-- Table: Reviews (Avis & Notes laissés par les voyageurs)
-- Note: ON DELETE NO ACTION pour éviter les cascades multiples sous SQL Server
CREATE TABLE Reviews (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    operator_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5) NOT NULL,
    commentaire TEXT NULL,
    date_avis DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE NO ACTION,
    FOREIGN KEY (operator_id) REFERENCES Operators(id) ON DELETE NO ACTION
);

-- ============================================================
-- Données de test (Jeux de données initiaux)
-- ============================================================

-- 1. Utilisateurs
INSERT INTO Users (name, email, password, role, telephone) VALUES
    ('Directeur Transiva (Admin)', 'admin@transiva.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', '+223 20 22 44 66'),
    ('Gérant Sama Transport', 'sama@transiva.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator', '+223 76 11 22 33'),
    ('Gérant Africa Express', 'express@transiva.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator', '+223 66 55 44 33'),
    ('Fatoumata Sidibé (Client)', 'fatou@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Traveler', '+223 70 88 99 00'),
    ('Ousmane Diarra (Client)', 'ousmane@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Traveler', '+223 60 77 66 55');

-- 2. Opérateurs
INSERT INTO Operators (user_id, nom_compagnie, description, logo_url, commission_rate, statut) VALUES
    (2, 'Sama Transport', 'Compagnie leader du transport par autocars climatisés au Mali.', NULL, 10.00, 'Valide'),
    (3, 'Africa Express', 'Sécurité, ponctualité et confort sur toutes nos lignes nationales.', NULL, 12.00, 'Valide');

-- 3. Véhicules
INSERT INTO Vehicles (operator_id, nom, immatriculation, capacite, type) VALUES
    (1, 'Autocar VIP Sama 01', 'AB-5678-MD', 40, 'Bus'),
    (1, 'Minibus Express Sama 02', 'CD-9012-MD', 15, 'Minibus'),
    (2, 'Grand Car Africa Exp 01', 'EF-3456-MD', 50, 'Bus');

-- 4. Lignes (Routes)
INSERT INTO Routes (operator_id, nom, depart, arrivee, duree_min) VALUES
    (1, 'Ligne Bamako - Ségou', 'Bamako', 'Ségou', 240),
    (1, 'Ligne Bamako - Mopti', 'Bamako', 'Mopti', 420),
    (2, 'Ligne Bamako - Sikasso', 'Bamako', 'Sikasso', 300),
    (2, 'Ligne Ségou - Mopti', 'Ségou', 'Mopti', 180);

-- 5. Horaires / Voyages (Trips)
INSERT INTO Trips (route_id, vehicle_id, heure_depart, heure_arrivee, jours, prix) VALUES
    (1, 1, '06:00', '10:00', 'Tous les jours', 6000),
    (1, 1, '14:00', '18:00', 'Tous les jours', 6000),
    (2, 2, '07:00', '14:00', 'Lun - Ven', 9000),
    (3, 3, '06:30', '11:30', 'Lun - Sam', 7000),
    (4, 3, '08:00', '11:00', 'Tous les jours', 4000);

-- 6. Réservations
INSERT INTO Reservations (user_id, trip_id, date_voyage, nb_places, sieges, statut) VALUES
    (4, 1, '2026-06-20', 2, '3,4', 'Payee');

-- 7. Billets
INSERT INTO Tickets (reservation_id, code_qr, code_reservation, prix_total, date_emission) VALUES
    (1, 'TRV-4-1-20260620-SAMA', 'TRV-XYZ-7890', 12000, GETDATE());

-- 8. Paiements
INSERT INTO Payments (reservation_id, mode_paiement, montant, statut, reference_transaction) VALUES
    (1, 'Mobile Money', 12000, 'Reussi', 'TXN-MM-654321');

-- 9. Avis
INSERT INTO Reviews (user_id, operator_id, note, commentaire) VALUES
    (4, 1, 5, 'Excellent service ! Chauffeur prudent, bus très propre.');
