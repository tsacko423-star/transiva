-- Script SQL Transiva
-- À exécuter dans SQL Server Management Studio 22

CREATE DATABASE Transiva;
GO
USE Transiva;
GO

CREATE TABLE Lignes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    depart VARCHAR(100) NOT NULL,
    arrivee VARCHAR(100) NOT NULL,
    duree_min INT NOT NULL
);

CREATE TABLE Horaires (
    id INT IDENTITY(1,1) PRIMARY KEY,
    ligne_id INT NOT NULL,
    heure_depart TIME NOT NULL,
    heure_arrivee TIME NOT NULL,
    jours VARCHAR(100) NOT NULL,
    FOREIGN KEY (ligne_id) REFERENCES Lignes(id)
);

CREATE TABLE Voyageurs (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telephone VARCHAR(20) NOT NULL
);

CREATE TABLE Reservations (
    id INT IDENTITY(1,1) PRIMARY KEY,
    voyageur_id INT NOT NULL,
    horaire_id INT NOT NULL,
    date_voyage DATE NOT NULL,
    nb_places INT NOT NULL,
    statut VARCHAR(20) DEFAULT 'En attente',
    FOREIGN KEY (voyageur_id) REFERENCES Voyageurs(id),
    FOREIGN KEY (horaire_id) REFERENCES Horaires(id)
);

CREATE TABLE Billets (
    id INT IDENTITY(1,1) PRIMARY KEY,
    reservation_id INT NOT NULL,
    code_qr VARCHAR(255) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    date_emission DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (reservation_id) REFERENCES Reservations(id)
);

-- ============================================================
-- Données de test (facultatif)
-- ============================================================

INSERT INTO Lignes (nom, depart, arrivee, duree_min) VALUES
    ('Ligne 1', 'Bamako', 'Ségou', 240),
    ('Ligne 2', 'Bamako', 'Mopti', 420),
    ('Ligne 3', 'Ségou', 'Mopti', 180),
    ('Ligne 4', 'Bamako', 'Sikasso', 300);

INSERT INTO Horaires (ligne_id, heure_depart, heure_arrivee, jours) VALUES
    (1, '06:00', '10:00', 'Tous les jours'),
    (1, '08:00', '12:00', 'Lun - Sam'),
    (1, '14:00', '18:00', 'Tous les jours'),
    (2, '07:00', '14:00', 'Lun - Ven'),
    (2, '09:00', '16:00', 'Sam - Dim'),
    (3, '08:00', '11:00', 'Tous les jours'),
    (4, '06:30', '11:30', 'Lun - Sam'),
    (4, '13:00', '18:00', 'Tous les jours');
