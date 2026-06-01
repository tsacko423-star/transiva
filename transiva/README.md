# Transiva — Site de transport Laravel + SQL Server

## Prérequis

- PHP 8.1+
- Composer
- SQL Server 2019+ / SQL Server Management Studio 22
- Extension PHP `sqlsrv` et `pdo_sqlsrv` (driver Microsoft)

---

## Installation

### 1. Installer les dépendances PHP

```bash
composer install
```

### 2. Configurer l'environnement

Copiez `.env` et ajustez vos paramètres SQL Server :

```env
DB_CONNECTION=sqlsrv
DB_HOST=localhost
DB_PORT=1433
DB_DATABASE=Transiva
DB_USERNAME=sa
DB_PASSWORD=VotreMotDePasse
```

### 3. Générer la clé de l'application

```bash
php artisan key:generate
```

### 4. Créer la base de données

Dans SQL Server Management Studio, exécutez le script SQL fourni (`database/transiva.sql`).

### 5. Lancer le serveur

```bash
php artisan serve
```

Le site est accessible sur : http://localhost:8000

---

## Pages disponibles

| URL | Description |
|-----|-------------|
| `/` | Page d'accueil — liste des lignes |
| `/lignes/{id}` | Détail d'une ligne avec horaires |
| `/reserver/{horaire_id}` | Formulaire de réservation |
| `/mon-espace` | Espace voyageur (recherche par email) |
| `/admin` | Dashboard admin |
| `/admin/lignes` | Gestion des lignes |
| `/admin/horaires` | Gestion des horaires |
| `/admin/reservations` | Gestion des réservations |
| `/admin/voyageurs` | Liste des voyageurs |

---

## Installer le driver SQL Server pour PHP

### Windows
Téléchargez le driver officiel Microsoft :
https://learn.microsoft.com/fr-fr/sql/connect/php/download-drivers-php-sql-server

Ajoutez dans `php.ini` :
```
extension=php_sqlsrv_81_ts.dll
extension=php_pdo_sqlsrv_81_ts.dll
```

### Linux (Ubuntu)
```bash
sudo apt-get install php8.1-sqlsrv php8.1-pdo-sqlsrv
```

---

## Structure du projet

```
transiva/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php      # Toutes les actions admin
│   │   ├── LigneController.php      # Affichage lignes
│   │   ├── ReservationController.php# Réservation + billet
│   │   └── VoyageurController.php   # Espace voyageur
│   └── Models/
│       ├── Ligne.php
│       ├── Horaire.php
│       ├── Voyageur.php
│       ├── Reservation.php
│       └── Billet.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php            # Layout public
│   │   └── admin.blade.php          # Layout admin
│   ├── lignes/
│   ├── reservations/
│   ├── voyageur/
│   └── admin/
├── routes/web.php                   # Toutes les routes
└── .env                             # Configuration BD
```

---

## Tarif par défaut

Le prix est fixé à **500 FCFA par place** dans `ReservationController.php`.
Pour modifier, cherchez : `$request->nb_places * 500`
