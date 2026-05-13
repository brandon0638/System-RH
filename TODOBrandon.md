# TODO Complet - Gestion des congés TechMada RH (CI4 + SQLite)

### 1. Configuration du projet

- [x] Configuration de app/Config/Database.php (SQLite)

- [x] Configuration de app/Config/Routes.php (routes employé)

- [x] Configuration de app/Config/Filters.php (filtres auth et role)

- [x] Vérification que PDO SQLite est activé

- [x] Création de app/Controllers/BaseController.php

### 2. Base de données (Migrations SQLite)

- [x] Migration departements

- [x] Migration types_conge

- [x] Migration employes

- [x] Migration soldes

- [x] Migration conges

### 3. Filtres

- [x] Création du filtre AuthFilter.php (vérifie si utilisateur connecté)

- [x] Création du filtre RoleFilter.php (vérifie le rôle)

### 4. Authentification

- [x] Création de AuthController.php

- [x] Page de login stylisée (auth/login.php)

- [x] Vérification email/mot de passe

- [x] Stockage des infos utilisateur en session

- [x] Redirection selon rôle (employe, rh, admin)

- [x] Déconnexion (/logout)

### 5. Modèles

- [x] EmployeModel.php

- [x] CongeModel.php

- [x] SoldeModel.php

- [x] TypeCongeModel.php

### 6. Vues Employé

- [x] layouts/app.php

- [x] auth/login.php

- [x] employe/dashboard.php

- [x] employe/create.php

- [x] employe/index.php

- [x] employe/profil.php


### 7. Espace Employé - Fonctionnalités


- [x] Dashboard avec cartes statistiques

- [x] Affichage des soldes de congés

- [x] Affichage des dernières demandes

- [x] Formulaire de demande de congé

- [x] Validation solde suffisant

- [x] Validation pas de chevauchement

- [x] Calcul automatique du nombre de jours

- [x] Liste des demandes de congé

- [x] Annulation d'une demande en attente

- [x] Changement de mot de passe

- [x] Messages flash succès/erreur


### 8. Fichiers Assets

- [x] public/assets/css/style.css

- [x] public/assets/js/main.js


### 9. Seeders

- [x] Création du seeder FitspaceSeeder.php

- [x] Insertion des départements (IT, RH, Finance, Marketing)

- [x] Insertion des types de congé (Annuel, Maladie, Spécial)

- [x] Insertion des employés (admin, rh, employe)

- [x] Insertion des soldes initiaux