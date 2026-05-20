# TODO Complet - Gestion des congés TechMada RH (CI4 + SQLite)

## Employee

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



## Espace RH - À réaliser

### 1. Contrôleur RH

- [x] Création de app/Controllers/RhController.php

- [x] Méthode dashboard() - Vue d'ensemble RH

- [x] Méthode index() - Liste des demandes à traiter

- [x] Méthode approuver($id) - Approuver une demande + déduire solde

- [x] Méthode refuser($id) - Refuser une demande + commentaire obligatoire

- [x] Méthode historique() - Demandes déjà traitées

- [x] Méthode soldes() - Visualisation des soldes par employé

- [x] Méthode filtrer() - Filtrage par département/statut

### 2. Routes RH

- [x] $routes->get('rh/dashboard', 'RhController::dashboard')

- [x] $routes->get('rh/demandes', 'RhController::index')

- [x] $routes->post('rh/approuver/(:num)', 'RhController::approuver/$1')

- [x] $routes->post('rh/refuser/(:num)', 'RhController::refuser/$1')

- [x] $routes->get('rh/historique', 'RhController::historique')

- [x] $routes->get('rh/soldes', 'RhController::soldes')

- [x] Protection des routes avec filtre role:rh,admin


### 3. Vues RH

- app/Views/rh/dashboard.php - Dashboard RH

    - [x] Cartes statistiques (demandes en attente, approuvées mois, employés actifs, absents aujourd'hui)

    - [x] Graphique demandes par type

    - [x] Liste des demandes récentes<br>

---

- app/Views/rh/demandes.php - Liste des demandes à traiter

    - [x] Tableau avec toutes les demandes en attente

    - [x] Filtres par employé et département

    - [x] Boutons Approuver/Refuser

    - [x] Modal pour refus avec commentaire

    - [x] Modal pour confirmation approbation
---

- app/Views/rh/historique.php - Historique

    - [x] Tableau des demandes traitées

    - [x] Filtre par statut (approuvées, refusées, annulées)

    - [x] Affichage des commentaires RH

---

- app/Views/rh/soldes.php - Soldes employés

    - [x] Tableau récapitulatif des soldes

    - [x] Barres de progression visuelles


## Nouveautés réalisées (Suite Employé)

### 1. Calendrier des congés

- [x] Intégration de FullCalendar

- [x] Vue Mois (dayGridMonth)

- [x] Vue Semaine (timeGridWeek)

- [x] Vue Jour (timeGridDay)

- [x] Affichage des congés approuvés uniquement

- [x] Couleurs par type de congé (Vert: Annuel, Bleu: Maladie, Violet: Spécial)

- [x] Affichage du motif dans le tooltip au survol

- [x] Popup de détails au clic

- [x] Légende des couleurs

- [x] Navigation entre semaines/mois/jours

- [x] JavaScript externalisé dans calendar.js


### 2. Statistiques & Graphiques

- [x] Cartes récapitulatives (Total demandes, Approuvées, En attente, Jours restants)

- [x] Graphique camembert (Pie Chart) - Demandes par type de congé

- [x] Graphique barres (Bar Chart) - Jours de congé par mois

- [x] Graphique barres (Bar Chart) - Jours de congé par jour de semaine

- [x] Graphique anneau (Doughnut Chart) - Répartition des statuts

- [x] Intégration de Chart.js en local

- [x] JavaScript externalisé dans statistiques.js

- [x] Design cohérent avec le thème existant

### 3. Fichiers créés

- [x] app/Controllers/EmployeController.php (méthodes calendrier + statistiques)

- [x] app/Views/employe/calendrier.php

- [x] app/Views/employe/statistiques.php

- [x] public/assets/js/calendar.js

- [x] public/assets/js/statistiques.js

- [x] public/assets/js/chart.umd.js (Chart.js local)

- [x] app/Config/Routes.php (routes mises à jour)

- [x] app/Views/layouts/app.php (CDN et scripts mis à jour)


### 4. Organisation du code

- [x] JavaScript séparé des vues PHP

- [x] CSS inline supprimé (déplacé dans les balises style des vues)

- [x] Chart.js téléchargé localement

- [x] FullCalendar reste en CDN (trop lourd)



### 5. Tests à effectuer


- [x] Vérifier que le calendrier s'affiche correctement

- [x] Vérifier que les couleurs correspondent aux types

- [x] Vérifier que les tooltips fonctionnent

- [x] Vérifier que les graphiques se chargent correctement

- [x] Vérifier que les données des graphiques concordent avec la base

### 6. A faire

- [x] Mise à jour du README (à faire)