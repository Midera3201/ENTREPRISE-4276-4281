# TechMada RH — Système de Gestion des Congés

Application de gestion des congés développée avec **CodeIgniter 4**, incluant les espaces Employé, RH et Administrateur.

---

## 📋 Sommaire

- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration de la base de données SQLite](#configuration-sqlite)
- [Migrations](#migrations)
- [Seeders (données de test)](#seeders)
- [Lancement du serveur](#lancement-du-serveur)
- [Comptes de test](#comptes-de-test)
- [Architecture du projet](#architecture-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Commandes utiles](#commandes-utiles)
- [Tests](#tests)

---

## Prérequis

- **PHP** ≥ 8.1
- **Composer** ≥ 2.0
- **SQLite3** (extension PHP activée)
- **Git** (optionnel)

Vérifier les extensions PHP requises :

```bash
php -m | grep -E "sqlite3|pdo_sqlite|mbstring|intl|json"
```

## Installation

### 1. Cloner le projet

```bash
git clone <url-du-depot> techmada-rh
cd techmada-rh
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Vérifier la configuration CodeIgniter

```bash
cp env .env
```

S'assurer que le fichier `.env` contient :

```ini
CI_ENVIRONMENT = development
```

## Configuration SQLite

### Éditer `app/Config/Database.php`

Remplacer ou compléter la configuration du tableau `$default` :

```php
public $default = [
    'DSN'      => '',
    'hostname' => '',
    'username' => '',
    'password' => '',
    'database' => WRITEPATH . 'database' . DIRECTORY_SEPARATOR . 'ci4rh.sqlite3',
    'DBDriver' => 'SQLite3',
    'DBPrefix' => '',
    'port'     => 3306,
];
```

Alternativement, utiliser le fichier `app/Database/Connection.php` si vous préférez une configuration centralisée :

```php
// app/Database/Connection.php
<?php
namespace Config;

use CodeIgniter\Database\Config;

class Connection extends Config
{
    public $defaultGroup = 'default';

    public $default = [
        'DSN'      => '',
        'hostname' => '',
        'username' => '',
        'password' => '',
        'database' => WRITEPATH . 'database/ci4rh.sqlite3',
        'DBDriver' => 'SQLite3',
        'DBPrefix' => '',
        'port'     => 3306,
    ];
}
```

> **Note** : Le fichier SQLite sera créé automatiquement lors de la première migration. Assurez-vous que le répertoire `writable/database/` existe et est accessible en écriture :
> ```bash
> mkdir -p writable/database
> chmod 775 writable/database
> ```

## Migrations

Les migrations sont situées dans `app/Database/Migrations/`.

### Tout exécuter (dans l'ordre)

```bash
php spark migrate
```

### Tout réinitialiser puis migrer

```bash
php spark migrate:rollback --all
php spark migrate
```

### Migrations disponibles

| Fichier | Description |
|---------|-------------|
| `2026-05-13-000001_CreateDepartementsTable.php` | Table `departements` |
| `2026-05-13-000002_CreateEmployesTable.php` | Table `employes` |
| `2026-05-13-000003_CreateTypesCongeTable.php` | Table `types_conge` |
| `2026-05-13-000004_CreateSoldesTable.php` | Table `soldes` |
| `2026-05-13-000005_CreateCongesTable.php` | Table `conges` |

### Schéma de la base de données

```
┌──────────────┐       ┌──────────────┐
│  departements │       │  types_conge │
├──────────────┤       ├──────────────┤
│ id (PK)      │       │ id (PK)      │
│ nom (UNIQUE) │       │ libelle      │
│ description  │       │ jours_annuels│
│ created_at   │       │ deductible   │
│ updated_at   │       │ created_at   │
└──────┬───────┘       │ updated_at   │
       │               └──────┬───────┘
       │                      │
       │   ┌──────────────────┘
       │   │
┌──────▼───▼──────────┐  ┌──────────────┐  ┌──────────────────┐
│       employes       │  │    soldes     │  │     conges       │
├──────────────────────┤  ├──────────────┤  ├──────────────────┤
│ id (PK)              │  │ id (PK)      │  │ id (PK)          │
│ nom                  │  │ employe_id   │──│ employe_id       │
│ prenom               │  │ type_conge_id│──│ type_conge_id    │
│ email (UNIQUE)       │  │ annee (UK)   │  │ date_debut       │
│ password_hash        │  │ jours_attrib.│  │ date_fin         │
│ role                 │  │ jours_pris   │  │ nb_jours         │
│ departement_id (FK)  │  │ created_at   │  │ statut           │
│ date_embauche        │  │ updated_at   │  │ commentaire_rh   │
│ actif                │  └──────────────┘  │ date_soumission  │
│ created_at           │                    │ created_at       │
│ updated_at           │                    │ updated_at       │
└──────────────────────┘                    └──────────────────┘

Contraintes :
  - employes.role IN ('employe', 'rh', 'admin')
  - conges.statut IN ('en_attente', 'approuve', 'refuse', 'annule')
  - conges.date_fin >= conges.date_debut
  - soldes UNIQUE(employe_id, type_conge_id, annee)
  - soldes.jours_attribues >= 0
  - soldes.jours_pris >= 0
  - conges.nb_jours > 0
```

## Seeders

Les seeders remplissent la base avec des données de test.

### Exécuter tous les seeders

```bash
# Ordre recommandé
php spark db:seed DepartementsSeeder
php spark db:seed TypesCongeSeeder
php spark db:seed EmployesSeeder
php spark db:seed SoldesSeeder
php spark db:seed CongesSeeder
```

### Tout exécuter d'un coup

```bash
php spark db:seed DatabaseSeeder
```

### Vider toutes les tables

```bash
php spark db:seed --truncate
```

## Lancement du serveur

```bash
# Serveur de développement (par défaut : http://localhost:8080)
php spark serve

# Port personnalisé
php spark serve --port=9000

# Adresse personnalisée
php spark serve --host=0.0.0.0
```

Puis ouvrez votre navigateur à l'adresse : **http://localhost:8080**

## Comptes de test

| Rôle | Email | Mot de passe | Accès |
|------|-------|--------------|-------|
| **Admin** | `admin@techmada.mg` | `admin123` | `/admin/*` (tout) |
| **RH** | `rh@techmada.mg` | `rh12345` | `/rh/*` + `/dashboard` |
| **Employé** | `hery@techmada.mg` | `employe123` | `/employee/*` + `/dashboard` |
| **Employé** | `mina@techmada.mg` | `employe123` | `/employee/*` + `/dashboard` |
| **Employé** | `lala@techmada.mg` | `employe123` | `/employee/*` + `/dashboard` |

## Architecture du projet

```
app/
├── Controllers/
│   ├── Admin/
│   │   ├── AdminBaseController.php      ← Vérification rôle admin
│   │   ├── AdminEmployesController.php   ← CRUD employés
│   │   ├── AdminDepartementsController.php ← CRUD départements
│   │   ├── AdminTypesCongeController.php ← CRUD types de congé
│   │   ├── AdminSoldesController.php     ← Gestion soldes
│   │   └── AdminStatsController.php      ← Dashboard statistiques
│   ├── Employee/
│   │   ├── EmployeeBaseController.php    ← Vérification auth employé
│   │   └── EmployeeController.php        ← Dashboard, demandes, profil
│   ├── RH/
│   │   ├── RHBaseController.php          ← Vérification auth RH
│   │   └── RHController.php              ← Traitement demandes, soldes
│   ├── Auth.php                           ← Login / Logout
│   ├── Dashboard.php                      ← Page d'accueil publique
│   └── Home.php                           ← Redirection
├── Models/
│   ├── EmployeModel.php
│   ├── DepartementModel.php
│   ├── TypeCongeModel.php
│   ├── SoldeModel.php
│   └── CongeModel.php
├── Services/
│   └── CongeService.php                   ← Logique métier centralisée
│       ├── calculerNbJours()              ← Jours ouvrables
│       ├── calculerNbJoursCalendaires()   ← Jours calendaires
│       ├── verifierSoldeAvantApprobation()
│       ├── deduireSoldeApresApprobation()
│       ├── recrediterSoldeApresAnnulation()
│       ├── verifierChevauchement()
│       ├── calculerSoldeRestant()
│       ├── verifierSoldeInsuffisantMessage()
│       └── initialiserSoldes()
├── Filters/
│   ├── AuthFilter.php                     ← Vérifie session connectée
│   └── RoleFilter.php                     ← Vérifie rôle utilisateur
├── Views/
│   ├── layouts/
│   │   ├── app.php                        ← Layout principal (login)
│   │   ├── admin.php                      ← Layout admin + sidebar
│   │   ├── employee.php                   ← Layout employé + sidebar
│   │   └── rh.php                         ← Layout RH + sidebar
│   ├── auth/
│   │   └── login.php                      ← Formulaire de connexion
│   ├── employee/
│   │   ├── dashboard.php
│   │   ├── demande_form.php
│   │   ├── mes_demandes.php
│   │   └── profil.php
│   ├── rh/
│   │   ├── demandes_en_attente.php
│   │   ├── filtres.php
│   │   └── soldes_equipe.php
│   └── admin/
│       ├── employes/ (index, form, confirm_delete)
│       ├── departements/ (index, form, confirm_delete)
│       ├── types_conge/ (index, form, confirm_delete)
│       ├── soldes/ (index, form, confirm_delete)
│       └── stats/index.php
├── Config/
│   ├── App.php                            ← Configuration générale
│   ├── Database.php                        ← Connexion BD
│   ├── Filters.php                        ← Enregistrement des filtres
│   ├── Routes.php                         ← Définition des routes
│   ├── Security.php                       ← Configuration CSRF
│   └── Session.php                        ← Configuration des sessions
└── Database/
    ├── Migrations/                        ← Création des tables
    └── Seeds/                             ← Données de test
```

## Fonctionnalités

### Employé
- Consulson son tableau de bord (stats, dernières demandes)
- Soumettre une demande de congé (avec vérification solde et chevauchement)
- Voir l'historique de ses demandes avec filtres
- Annuler une demande (recrédit si déjà approuvée)
- Consulter son profil

### RH
- Voir toutes les demandes (avec filtres : statut, type, date, recherche)
- Approuver une demande (avec déduction automatique du solde)
- Refuser une demande (avec commentaire optionnel, sans déduction)
- Consulter les soldes de l'équipe (avec filtre par département)

### Administrateur
- Gestion complète CRUD des employés, départements, types de congé
- Initialisation et gestion des soldes
- Tableau de bord avec statistiques globales

### Règles métier
- Le solde n'est déduit qu'après approbation
- Une annulation après approbation recrédite le solde
- Les chevauchements de congés sont bloqués
- Le solde ne peut pas devenir négatif
- Les jours ouvrables (Lun-Ven) sont calculés automatiquement
- Toutes les modifications passent par des transactions SQL

## Commandes utiles

```bash
# Serveur de développement
php spark serve

# Migrations
php spark migrate                        # Exécuter les migrations
php spark migrate:rollback               # Annuler la dernière migration
php spark migrate:rollback --all         # Tout annuler
php spark migrate:refresh                # Rollback + migrate
php spark migrate:status                 # Voir l'état des migrations

# Seeders
php spark db:seed                        # Exécuter DatabaseSeeder
php spark db:seed --list                 # Lister les seeders disponibles
php spark db:seed DepartementsSeeder     # Exécuter un seeder spécifique

# Routes
php spark routes                         # Lister toutes les routes
php spark routes --columns=path,methods,handler

# Tests
php spark test                           # Exécuter tous les tests
php spark test --filter=NomTest          # Exécuter un test spécifique

# Debug
php spark db:seed                        # Réinitialiser les données
php spark cache:clear                    # Vider le cache
php spark debugtoolbar enable            # Activer la barre de debug
php spark debugtoolbar disable           # Désactiver la barre de debug

# Console interactive
php spark
```

## Tests

Consultez le fichier `TESTS_CHECKLIST.md` pour une liste complète de 50+ cas de test à exécuter.

---

*Développé avec CodeIgniter 4 et SQLite.*