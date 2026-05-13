contexte : codeIgniter4

on doit ajouter une page pour s'inscrire en tant qu'utilisateur 

voici les routes a ajouter dans Routes.php

// register
$routes->get('/register', 'UserController::register');
$routes->post('/registerTreatment', 'UserController::registerPost');

// register health
$routes->get('/register/health', 'UserController::registerHealth');
$routes->post('/register/health', 'UserController::registerHealthPost');


pour resumer, la page register est divisee en deux , la premiere consiste a input les informations
personnelles (nom, mail, mot de passe sans hash dans la db)

la deuxieme partie input les informations de sante (la taille en m , le poids en kg)

voici mes demandes : 
je vais te donner les pages register personnal and register health , tu devras ensuite creer un lien depuis login qui mene
vers register , dans la page register , il y aura l'input des donnees personnelles (nom etc)
le mail doit etre un mail et le mot de passe a la condition : au moins 4 characteres (il sera non hashe dans
la db)
une fois confirme , il creera un objet user avec ces attributs donnes
il menera ensuite vers register health ou on insert nos donnees  , confirmer inserera et remplira les informations manquantes (poids , taille et le IMC qui vaut poids en kg / taille (en metre ) ^ 2 corrige si cela est faux) de l'user precedent
une fois confirmee , cet user sera sauvegarde dans la db , et on retournera dans la page login

