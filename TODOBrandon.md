# Back Office - Realise


### 1. Configuration du projet
 
- [x] Configuration de app/Config/Database.php
- [x] Configuration de app/Config/Routes.php (routes back office)
- [x] Configuration de app/Config/Filters.php (filtres auth et role)

### 2. Base de donnee

- [x] Création du script SQL complet (initdb_brandon.sql)
- [x] Table User (id, nom, email, password, genre, taille, poids, IMC, balance, role)
- [x] Table Regime (id, nom, description, prix_par_jour, duree_jours, variation_poids_grammes)
- [x] Table RegimeComposition (id, idRegime, type_viande, pourcentage)
- [x] Table Sport (id, nom, description, variation_poids_grammes, calories_par_heure)
- [x] Table Code (id, code, valeur, utilise, expire_le)
- [x] Table Parametre (id, cle, valeur, description)
- [x] Table Option (id, label, prix, reduction)
- [x] Table OptionUser (id, idUser, idOption, date_achat)
- [x] Table UserHealth (id, idUser, taille_cm, poids_kg, imc, date_enregistrement)
- [x] Insertion des données minimales (5 users, 5 régimes, 5 sports, 15 codes, 4 paramètres)

### 3. Filtres

- [x] Création du filtre AuthFilter.php (vérifie si utilisateur connecté)
- [x] Création du filtre RoleFilter.php (vérifie le rôle admin)

### 4. Authentification

- [x] Création de AuthController.php
- [x] Page de login stylisée (auth/login.php)
- [x] Vérification email/mot de passe (mots de passe simples : 1234 pour admin)
- [x] Stockage des infos utilisateur en session (id, nom, email, role)
- [x] Redirection admin vers /admin/dashboard
- [x] Redirection user vers /login (en attendant le Front Office)
- [x] Déconnexion (/logout)

### 5. Dashboard Admin

- [x] Création de DashboardController.php
- [x] Vue dashboard.php avec cartes statistiques
- [x] Affichage du nombre total d'utilisateurs
- [x] Affichage du nombre total de régimes
- [x] Affichage du nombre total de sports
- [x] Affichage du nombre total de codes
- [x] Affichage des codes utilisés/non utilisés
- [x] Affichage des derniers utilisateurs inscrits
- [x] Graphique variation de poids par régime
- [x] Graphique impact des sports
- [x] Chargement des données via AJAX
- [x] Auto-refresh des graphiques toutes les 30 secondes

### 6. CRUD Régimes

- [x] Création de RegimeController.php
- [x] Création de RegimeModel.php
- [x] Création de RegimeCompositionModel.php
- [x] Liste des régimes avec DataTable
- [x] Formulaire ajout/modification
- [x] Ajout d'un régime
- [x] Modification d'un régime
- [x] Suppression d'un régime
- [x] Gestion du prix par jour
- [x] Gestion de la durée en jours
- [x] Gestion de la variation de poids (grammes/jour) 
- [x] Gestion des pourcentages viande/poisson/volaille
- [x] Validation de la somme des pourcentages = 100%
- [x] Affichage des badges de composition

### 7. CRUD Sports

- [x] Création de SportController.php
- [x] Création de SportModel.php
- [x] Liste des sports avec DataTable
- [x] Formulaire ajout/modification
- [x] Ajout d'un sport
- [x] Modification d'un sport
- [x] Suppression d'un sport
- [x] Gestion de la variation de poids
- [x] Gestion des calories par heure (optionnel)

### 8. CRUD Codes

- [x] Création de CodeController.php
- [x] Création de CodeModel.php
- [x] Liste des codes avec DataTable
- [x] Formulaire ajout/modification
- [x] Ajout d'un code
- [x] Modification d'un code
- [x] Suppression d'un code
- [x] Gestion du code unique
- [x] Gestion de la valeur en euros
- [x] Gestion de la date d'expiration (optionnelle)
- [x] Affichage du statut (Disponible/Utilisé)

### 9. CRUD Paramètres

- [x] Création de ParametreController.php
- [x] Création de ParametreModel.php
- [x] Liste des paramètres
- [x] Formulaire modification
- [x] Modification des valeurs des paramètres
- [x] Paramètres par défaut (gold_prix, gold_reduction, site_name, contact_email)

### 10. Design & Intégration

- [x] Création du layout admin (admin/layouts/admin_layout.php)
- [x] Sidebar avec navigation (Dashboard, Régimes, Sports, Codes, Paramètres)
- [x] Affichage des informations utilisateur connecté dans la sidebar
- [x] Bouton de déconnexion
- [x] Loading overlay pour les requêtes AJAX
- [x] Notifications toast (succès/erreur)
- [x] Aucun CSS/JS dans les vues PHP (tout dans public/assets/)

### 11. Fichiers CSS

- [x] public/assets/css/admin.css (styles principaux)
- [x] public/assets/css/login.css (page de connexion)
- [x] public/assets/css/responsive.css (responsive mobile/tablette)

### 12. Fichiers JavaScript

- [x] public/assets/js/admin.js (fonctions globales)
- [x] public/assets/js/dashboard.js (graphiques et stats)
- [x] public/assets/js/regimes.js (CRUD régimes AJAX)
- [x] public/assets/js/sports.js (CRUD sports AJAX)
- [x] public/assets/js/codes.js (CRUD codes AJAX)
- [x] public/assets/js/parametres.js (CRUD paramètres AJAX)

### 13. HomeController

- [x] Création de HomeController.php
- [x] Redirection vers /login par défaut
- [x] Si admin déjà connecté, redirection vers /admin/dashboard

### 14. Validation et sécurité

- [x] Validation des formulaires (côté serveur)
- [x] Messages d'erreur personnalisés
- [x] Protection CSRF
- [x] Filtre d'authentification pour routes protégées
- [x] Filtre de rôle pour les routes admin

### 15. Responsive

- [x] Sidebar réductible sur mobile
- [x] Grille responsive des cartes statistiques
- [x] Tableaux adaptatifs
- [x] Formulaires adaptatifs