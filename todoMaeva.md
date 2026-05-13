# Projet application de regime alimentaire

## Attente
### Login
- Formulaire:
    champs: email, password
    -> required
    lien vers inscription si new user

### Inscription
-  Formulaire:
    - 1ere page: information de l'user
        Champs: nom, email, genre, password
        -> still required
    - 2eme page: taille (m), poids(kg)
        -> calcul de l'IMC:
            - IMC = poids(kg)/taille au carree(m)
            -> valeur de l'IMC
                - IMC < 18.5: poids insuffisant
                - 18.5 < IMC < 24.9: poids sante
                - 25 < IMC < 29.9: surpoids
                - IMC > 30: obesite

- Completion du profil de l'utilisateur ????


## A faire
- [ok] Creer un fichier sql dans lequel il y a un ajout de la colonne "password" dans la table User

### Routes
- [ok] Ajouter les routes necessaires dans Routes.php
    - [ok] GET /login
    - [ok] POST /login
    - [ok] GET /register
    - [ok] POST /register
    - [ok] GET /register/health
    - [ok] POST /register/health
    - [ok] GET /logout

### Model
- [ok] Creer le model correspondant a la table User: UserModel.php
    - [ok] table: User
    - [ok] primary key: id
    - [ok] allowedFields:
        - nom
        - email
        - genre
        - taille
        - poids
        - IMC
        - balance
        - password
    - [ok] validations:
        - nom obligatoire
        - nom >= 3 caracteres
        - email valide
        - email unique
        - password >= 8 caracteres
        - password avec caractere special
        - taille numerique > 0
        - poids numerique > 0
    - [ok] messages erreurs personnalises
    - [ok] methodes:
        - [ok] authenticateUser(email, password)
        - [ok] createUser(info)

### Controller
- Creer le controleur: UserController.php
    - [ok] fonction affichage login
    - [ok] fonction traitement login
        - authenticateUser()
        - creer session utilisateur
        - stocker id utilisateur
        - si login reussi redirect dashboard sinon back
    - [ok] fonction affichage register page 1
    - [ok] fonction traitement register page 1
        - sauvegarder donnees en session temporaire
        - redirect register page 2 I guess
    - [ok] fonction affichage register page 2
    - [ok] fonction traitement register page 2
        - [Noah va faire mais ok] calcul IMC
        - [Noah va faire mais ok] determiner balance 
        - hasher password (ne pas faire pour l'instant)
        - createUser()
        - redirect login
    - [ok] logout()
        - destroy session

### View
- [ok] Creer l'ensemble des vues: 
    - [ok] layout principal
        - navbar
        - flash messages
        - contenu dynamique
    - [ok] login.php
        - email
        - password
    - [ok] register_personal.php
        - nom
        - email
        - genre
        - password
        - bouton suivant
        - -> utiliser session temporaire
                            session()->set('signup_data', $data);
        - indicateur etape 1/2
    - [ok] register_health.php
        - taille
        - poids
        - bouton precedent
        - bouton valider
        - indicateur etape 2/2



#### Interface utilisateur simple et intuitive
    - Design epure et moderne
    - Utilisation de couleurs apaisantes et motivantes
    - Logo representant la sante et le bien etre
        - pour chaque formulaire
            - mettre la section title
            - mettre la section content
                - logo (maybe)
                - h2 "DietFit: votre compagnon de regime alimentaire (ou DietBalance je sais pas encore)"
                - petite phrase de login
                - formulaire: POST/GET
                - validation
                - affichage joli d'autres alternatives (continuer avec google, Apple)
                - mettre des icones sur la connexion tout court et celle avec google/apple
                - lien vers l'inscription si sur la page de login et vice versa
                - pour la page d'inscription, ajouter un indicateur d'etape (1/2, 2/2) mais bien joli

    - pour la deuxieme page d'inscription, ajouter un bouton "precedent" qui redirige vers la page precedente et affiche les donnees deja saisies
    - ajouter des flash messages pour les erreurs de validation et les succes d'inscription/login
                            

